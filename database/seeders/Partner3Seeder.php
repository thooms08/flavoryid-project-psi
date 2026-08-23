<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Partner3Seeder extends Seeder
{
    // ─────────────────────────────────────────────
    //  CONFIG
    // ─────────────────────────────────────────────
    const PARTNER_ID  = 2;
    const TOTAL_ORDER = 1000;
    const CHUNK_SIZE  = 100;

    public function run(): void
    {
        // ── 1. Ambil semua menu partner 3 secara dinamis ──────────────
        //    Tidak di-hardcode ID-nya — sistem bebas ambil menu apapun
        //    yang memang milik partner ini di DB saat seeder dijalankan.
        $menus = DB::table('menus')
            ->where('partner_id', self::PARTNER_ID)
            ->where('status_menu', 'tersedia')
            ->pluck('harga', 'id')   // ['id' => harga, ...]
            ->toArray();

        if (empty($menus)) {
            $this->command->error(
                'Partner ' . self::PARTNER_ID . ' belum memiliki menu tersedia. Seeder dibatalkan.'
            );
            return;
        }

        $menuIds   = array_keys($menus);
        $menuCount = count($menuIds);

        $this->command->info('Menu ditemukan: ' . $menuCount . ' item untuk partner ' . self::PARTNER_ID);

        // ── 2. Nomor pesanan dilanjutkan dari yang sudah ada ──────────
        $lastNomor = (int) DB::table('orders')
            ->where('partner_id', self::PARTNER_ID)
            ->max('nomor_pesanan');

        $nomorCounter = $lastNomor + 1;

        // ── 3. Rentang waktu: acak dalam 1 bulan terakhir ─────────────
        $startDate = Carbon::now()->subMonth();
        $endDate   = Carbon::now();
        $totalSecs = (int) $startDate->diffInSeconds($endDate);

        // ── 4. Data pendukung acak ────────────────────────────────────
        $namaList = [
            'Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hendra',
            'Indah', 'Joko', 'Kartika', 'Lina', 'Marko', 'Nina', 'Omar', 'Putri',
            'Raka', 'Sari', 'Tono', 'Umar', 'Vina', 'Wati', 'Yudi', 'Zara',
            'Aldi', 'Bella', 'Candra', 'Dina', 'Erwin', 'Fina',
        ];

        $metodeList = ['cash', 'qris', 'transfer'];

        $mejaList = array_merge(
            array_map(fn($l) => 'Meja ' . $l, range('A', 'F')),
            array_map(fn($n) => 'Meja ' . $n, range(1, 15))
        );

        // ── 5. Generate & insert per-chunk ────────────────────────────
        $this->command->info('Seeding ' . self::TOTAL_ORDER . ' orders...');

        $orderRows     = [];
        $detailsByUuid = [];

        for ($i = 0; $i < self::TOTAL_ORDER; $i++) {

            // Waktu order: titik acak dalam rentang 1 bulan terakhir
            $createdAt = $startDate->copy()->addSeconds(random_int(0, $totalSecs));
            $updatedAt = $createdAt->copy()->addMinutes(random_int(1, 15));

            // 1–5 menu unik per order, dipilih acak dari menu yang ada
            $itemCount    = random_int(1, min(5, $menuCount));
            $shuffled     = $menuIds;
            shuffle($shuffled);
            $pickedMenus  = array_slice($shuffled, 0, $itemCount);

            $subTotal    = 0;
            $tempDetails = [];

            foreach ($pickedMenus as $menuId) {
                $qty       = random_int(1, 4);
                $harga     = (int) $menus[$menuId];
                $subTotal += $qty * $harga;

                $tempDetails[] = [
                    'menu_id'    => $menuId,
                    'qty'        => $qty,
                    'harga'      => $harga,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ];
            }

            // Diskon: ~20% probabilitas order dapat diskon
            $nominalDiskon = 0;
            $jenisDiskon   = 'Rp';
            $totalHarga    = $subTotal;

            if (random_int(0, 4) === 0) {
                $jenisDiskon   = random_int(0, 1) ? '%' : 'Rp';
                $nominalDiskon = $jenisDiskon === '%'
                    ? random_int(5, 20)
                    : (round(random_int(2000, 15000) / 1000) * 1000);

                $potongan   = $jenisDiskon === '%'
                    ? ($subTotal * $nominalDiskon / 100)
                    : $nominalDiskon;
                $totalHarga = max(0, $subTotal - $potongan);
            }

            // Uang bayar & kembalian
            $metode    = $metodeList[array_rand($metodeList)];
            $uangBayar = $metode === 'cash'
                ? ceil($totalHarga / 1000) * 1000 + (random_int(0, 3) * 5000)
                : $totalHarga;
            $kembalian = max(0, $uangBayar - $totalHarga);

            $uuid      = (string) Str::uuid();
            $kodeStruk = strtolower(Str::random(8));

            $orderRows[] = [
                'uuid'           => $uuid,
                'kode_struk'     => $kodeStruk,
                'partner_id'     => self::PARTNER_ID,
                'nomor_pesanan'  => $nomorCounter++,
                'nama_pelanggan' => $namaList[array_rand($namaList)],
                'no_whatsapp'    => '08' . random_int(100000000, 999999999),
                'no_meja'        => $mejaList[array_rand($mejaList)],
                'keterangan'     => null,
                'total_harga'    => $totalHarga,
                'nominal_diskon' => $nominalDiskon,
                'jenis_diskon'   => $jenisDiskon,
                'uang_bayar'     => $uangBayar,
                'metode_bayar'   => $metode,
                'kembalian'      => $kembalian,
                'bukti_bayar'    => null,
                'status'         => 'success',
                'created_at'     => $createdAt,
                'updated_at'     => $updatedAt,
            ];

            $detailsByUuid[$uuid] = $tempDetails;

            // Insert per chunk agar tidak overload memory
            if (count($orderRows) === self::CHUNK_SIZE) {
                $this->insertChunk($orderRows, $detailsByUuid);
                $orderRows     = [];
                $detailsByUuid = [];
            }
        }

        // Insert sisa chunk terakhir
        if (!empty($orderRows)) {
            $this->insertChunk($orderRows, $detailsByUuid);
        }

        $this->command->info('✅ ' . self::TOTAL_ORDER . ' orders berhasil di-seed untuk partner ' . self::PARTNER_ID . '.');
    }

    // ─────────────────────────────────────────────
    //  HELPER: insert satu chunk order + detail-nya
    // ─────────────────────────────────────────────
    private function insertChunk(array $orderRows, array $detailsByUuid): void
    {
        DB::table('orders')->insert($orderRows);

        // Ambil id yang baru diinsert berdasarkan uuid
        $uuids    = array_column($orderRows, 'uuid');
        $inserted = DB::table('orders')
            ->whereIn('uuid', $uuids)
            ->pluck('id', 'uuid')
            ->toArray();

        $detailBatch = [];

        foreach ($detailsByUuid as $uuid => $details) {
            $orderId = $inserted[$uuid] ?? null;
            if (!$orderId) continue;

            foreach ($details as $d) {
                $detailBatch[] = [
                    'order_id'   => $orderId,
                    'menu_id'    => $d['menu_id'],
                    'qty'        => $d['qty'],
                    'harga'      => $d['harga'],
                    'variant'    => null,
                    'created_at' => $d['created_at'],
                    'updated_at' => $d['updated_at'],
                ];
            }
        }

        if (!empty($detailBatch)) {
            DB::table('order_details')->insert($detailBatch);
        }
    }
}
