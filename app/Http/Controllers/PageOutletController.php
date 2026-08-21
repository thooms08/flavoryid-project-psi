<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformasiOutlet;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PengaturanForm;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\OrderMasuk;
use Illuminate\Support\Facades\Cache;

class PageOutletController extends Controller
{
    /**
     * Menampilkan halaman Outlet
     * Cache outlet+menu 5 menit — di-invalidate saat ada perubahan menu dari KasirMenuController
     */
    public function show($page_name)
    {
        $cacheKey = "outlet_page_{$page_name}";
        $cached = Cache::remember($cacheKey, 300, function () use ($page_name) {
            $outlet = InformasiOutlet::with('mediaQr')
                ->where('page_name', $page_name)
                ->firstOrFail();
            $pengaturan = PengaturanForm::firstOrCreate(
                ['partner_id' => $outlet->partner_id],
                ['nama' => true, 'whatsapp' => true, 'meja' => true, 'keterangan' => true]
            );
            $menus = Menu::with('variants')
                ->where('partner_id', $outlet->partner_id)
                ->get();
            return compact('outlet', 'pengaturan', 'menus');
        });

        return view('page-outlet', $cached);
    }

    /**
     * Endpoint API AJAX untuk pencarian menu secara Real-Time
     */
    public function search(Request $request, $page_name)
    {
        $outlet  = InformasiOutlet::where('page_name', $page_name)->firstOrFail();
        $keyword = $request->input('q');

        $menus = Menu::with('variants')
            ->where('partner_id', $outlet->partner_id)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_menu', 'like', "%{$keyword}%")
                      ->orWhere('harga', 'like', "%{$keyword}%")
                      ->orWhere('status_menu', 'like', "%{$keyword}%")
                      ->orWhere('tipe_menu', 'like', "%{$keyword}%");
                });
            })
            ->get();

        return response()->json($menus);
    }

    /**
     * Halaman lihat semua menu (Placeholder)
     */
    public function allMenu($page_name)
    {
        return "Ini halaman Daftar Semua Menu untuk: " . $page_name;
    }

    /**
     * AJAX: Validasi kode voucher dari halaman publik outlet.
     * Mengembalikan data potongan agar JS bisa hitung total sisi client.
     */
    public function applyVoucher(Request $request, $page_name)
    {
        $outlet = InformasiOutlet::where('page_name', $page_name)->firstOrFail();

        // Cek apakah fitur voucher aktif untuk partner ini
        $pengaturan = PengaturanForm::where('partner_id', $outlet->partner_id)->first();
        if (!$pengaturan || !$pengaturan->voucher) {
            return response()->json(['valid' => false, 'message' => 'Fitur voucher tidak aktif.']);
        }

        $kode = strtoupper(trim($request->input('kode_voucher', '')));

        if (strlen($kode) !== 6) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher harus 6 karakter.']);
        }

        $voucher = Voucher::where('partner_id', $outlet->partner_id)
            ->where('kode_voucher', $kode)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return response()->json([
                'valid'   => false,
                'message' => 'Kode voucher <strong>' . $kode . '</strong> tidak ditemukan atau tidak aktif.',
            ]);
        }

        return response()->json([
            'valid'            => true,
            'message'          => 'Voucher <strong>' . $kode . '</strong> berhasil diterapkan!',
            'kode_voucher'     => $voucher->kode_voucher,
            'jenis_potongan'   => $voucher->jenis_potongan,
            'nominal_potongan' => (float) $voucher->nominal_potongan,
        ]);
    }

    /**
     * Menyimpan Pesanan dari Customer (Public)
     */
    public function storeOrder(Request $request, $page_name)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'cart'           => 'required|array|min:1',
            'kode_voucher'   => 'nullable|string|size:6',
        ]);

        try {
            return DB::transaction(function () use ($request, $page_name) {
                $outlet    = InformasiOutlet::where('page_name', $page_name)->firstOrFail();
                $partnerId = $outlet->partner_id;
                $now       = Carbon::now();

                // Generate Nomor Pesanan
                $lastOrder = Order::where('partner_id', $partnerId)
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->lockForUpdate()
                    ->orderBy('nomor_pesanan', 'desc')
                    ->first();
                $nextNumber = $lastOrder ? (int)$lastOrder->nomor_pesanan + 1 : 1;

                $totalHarga        = 0;
                $orderDetailsData  = [];

                foreach ($request->cart as $item) {
                    $menu = Menu::findOrFail($item['id']);

                    // Cek & Potong Stok
                    if (!is_null($menu->stok_menu)) {
                        if ($item['qty'] > $menu->stok_menu) {
                            throw new \Exception("Stok {$menu->nama_menu} tidak mencukupi.");
                        }
                        $menu->stok_menu -= $item['qty'];
                        if ($menu->stok_menu <= 0) {
                            $menu->stok_menu  = 0;
                            $menu->status_menu = 'habis';
                        }
                        $menu->save();
                    }

                    $hargaVariant = isset($item['variant_harga']) ? $item['variant_harga'] : 0;
                    $hargaSatuan  = $menu->harga + $hargaVariant;
                    $subtotal     = $hargaSatuan * $item['qty'];
                    $totalHarga  += $subtotal;

                    $orderDetailsData[] = [
                        'menu_id'  => $menu->id,
                        'qty'      => $item['qty'],
                        'harga'    => $hargaSatuan,
                        'variant'  => $item['variant_name'] ?? null,
                        'subtotal' => $subtotal,
                    ];
                }

                // --- LOGIKA VOUCHER (halaman publik tidak ada diskon manual) ---
                $kodeVoucher  = null;
                $nilaiVoucher = null;

                $kodeInput = strtoupper(trim($request->input('kode_voucher', '')));
                if ($kodeInput && strlen($kodeInput) === 6) {
                    $pengaturan = PengaturanForm::where('partner_id', $partnerId)->first();

                    if ($pengaturan && $pengaturan->voucher) {
                        $voucher = Voucher::where('partner_id', $partnerId)
                            ->where('kode_voucher', $kodeInput)
                            ->where('is_active', true)
                            ->first();

                        if ($voucher) {
                            $kodeVoucher = $kodeInput;
                            if ($voucher->jenis_potongan === '%') {
                                $persen       = min((float) $voucher->nominal_potongan, 100);
                                $nilaiVoucher = round(($totalHarga * $persen) / 100, 2);
                            } else {
                                $nilaiVoucher = min((float) $voucher->nominal_potongan, $totalHarga);
                            }
                            $totalHarga = $totalHarga - $nilaiVoucher;
                        }
                    }
                }

                // Simpan Order (Default status: pending)
                $order = Order::create([
                    'partner_id'     => $partnerId,
                    'nomor_pesanan'  => $nextNumber,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'no_meja'        => $request->no_meja,
                    'keterangan'     => $request->keterangan,
                    'total_harga'    => $totalHarga,
                    'kode_voucher'   => $kodeVoucher,
                    'nilai_voucher'  => $nilaiVoucher,
                    'status'         => 'pending',
                ]);

                // Simpan Order Details
                foreach ($orderDetailsData as $detail) {
                    $order->orderDetails()->create($detail);
                }

                $order->load('orderDetails.menu');
                broadcast(new OrderMasuk($order));

                // Invalidate cache outlet agar status stok menu ter-refresh
                Cache::forget("outlet_page_{$page_name}");

                return response()->json([
                    'success'      => true,
                    'message'      => 'Pesanan berhasil dibuat',
                    'redirect_url' => route('outlet.order.konfirmasi', [
                        'page_name' => $page_name,
                        'uuid'      => $order->uuid,
                    ])
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman Konfirmasi Pesanan Customer
     */
    public function konfirmasi($page_name, $uuid)
    {
        $outlet = InformasiOutlet::where('page_name', $page_name)->firstOrFail();
        $order  = Order::where('uuid', $uuid)->firstOrFail();
        return view('order-konfirm', compact('outlet', 'order'));
    }
}
