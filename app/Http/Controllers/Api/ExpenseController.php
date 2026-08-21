<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\BuktiPengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $partnerId = $user->partner->id;
        $status = $request->query('status', 'save');
        $startDate = $request->query('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate = $request->query('end_date', Carbon::today('Asia/Jakarta')->toDateString());

        try {
            $pengeluarans = Pengeluaran::with('buktiPengeluaran') 
                ->where('partner_id', $partnerId)
                ->where('status', $status)
                ->whereBetween('created_at', [
                    Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay(),
                    Carbon::parse($endDate, 'Asia/Jakarta')->endOfDay()
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            $galleryBukti = BuktiPengeluaran::whereIn('pengeluaran_id', $pengeluarans->pluck('id'))
                ->get()
                ->unique('path_bukti')
                ->values()
                ->map(fn($b) => [
                    'path_bukti'  => $b->path_bukti,
                    'kode'        => $b->kode,
                    'created_at'  => $b->created_at,
                ]);

            return response()->json([
                'success' => true,
                'data' => $pengeluarans,
                'gallery' => $galleryBukti
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        // Aturan validasi: pastikan bukti max 2MB agar aman dari error 422
        $request->validate([
            'kategori' => 'required|string',
            'items'    => 'required|string', // Dikirim sebagai JSON string array dari flutter
            'bukti'    => 'nullable|file|mimes:jpg,jpeg,png|max:2048', 
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $partnerId = $request->user()->partner->id;
                $kategori = $request->kategori;

                $pathBukti = null;
                $kodeBukti = null;
                // Logika Penyimpanan Gambar
                if ($request->hasFile('bukti')) {
                    $file = $request->file('bukti');
                    $filename = 'pengeluaran_' . time() . '_' . uniqid() . '.jpg';
                    
                    // Disk 'local' root-nya sudah storage/app/private — cukup subfolder langsung
                    $file->storeAs('pengeluaran', $filename);
                    $pathBukti = $filename;
                    // Generate kode unik 6 karakter (alfanumerik uppercase) sebagai label penanda
                    $kodeBukti = strtoupper(substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(6))), 0, 6));
                }

                // Decode JSON dari flutter ke array PHP
                $items = json_decode($request->items, true);
                
                foreach ($items as $item) {
                    if (!empty($item['nama_item'])) {
                        $pengeluaran = Pengeluaran::create([
                            'partner_id' => $partnerId,
                            'kategori'   => $kategori,
                            'keterangan' => $item['keterangan'] ?? null,
                            'item'       => $item['nama_item'],
                            'harga'      => $item['harga'] ?? 0,
                            'qty'        => $item['qty'] ?? 1,
                            'total'      => $item['total'] ?? 0,
                            'status'     => 'save'
                        ]);

                        if ($pathBukti) {
                            BuktiPengeluaran::create([
                                'pengeluaran_id' => $pengeluaran->id,
                                'path_bukti'     => $pathBukti,
                                'kode'           => $kodeBukti,
                            ]);
                        }
                    }
                }

                return response()->json(['success' => true, 'message' => 'Pengeluaran berhasil disimpan']);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pengeluaran = Pengeluaran::where('partner_id', $request->user()->partner->id)->findOrFail($id);
            $pengeluaran->update($request->only(['item', 'harga', 'qty', 'total', 'keterangan']));
            return response()->json(['success' => true, 'message' => 'Data diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui'], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $pengeluaran = Pengeluaran::where('partner_id', $request->user()->partner->id)->findOrFail($id);
            $status = $request->input('status');
            $pengeluaran->update(['status' => $status]);
            return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status'], 500);
        }
    }
}