<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengeluaranSeeder extends Seeder
{
    /**
     * Ubah sesuai partner_id yang ingin di-seed.
     * Jalankan: php artisan db:seed --class=PengeluaranSeeder
     * Atau override via: PARTNER_ID=2 php artisan db:seed --class=PengeluaranSeeder
     */
    const PARTNER_ID   = 1;
    const TOTAL        = 1000;
    const RANGE_DAYS   = 30; // 1 bulan ke belakang dari hari ini

    public function run(): void
    {
        $partnerId = (int) (env('SEEDER_PARTNER_ID', self::PARTNER_ID));

        // Validasi partner tersedia
        $partnerExists = DB::table('partners')->where('id', $partnerId)->exists();
        if (!$partnerExists) {
            $this->command->error("Partner ID {$partnerId} tidak ditemukan. Hentikan seeder.");
            return;
        }

        $endDate   = Carbon::now('Asia/Jakarta');
        $startDate = $endDate->copy()->subDays(self::RANGE_DAYS - 1)->startOfDay();
        $totalSecs = (int) $startDate->diffInSeconds($endDate);

        $this->command->info("Seeding " . self::TOTAL . " pengeluaran untuk partner ID {$partnerId}...");
        $this->command->info("Range: {$startDate->toDateString()} → {$endDate->toDateString()}");

        // ──────────────────────────────────────────────────────────────
        // Template pengeluaran realistis untuk usaha kafe / warung
        // Format: [kategori, item, harga_min, harga_max, qty_min, qty_max, keterangan]
        // ──────────────────────────────────────────────────────────────
        $templates = [

            // ─── BAHAN BAKU ───────────────────────────────────────────
            ['operasional usaha', 'Biji Kopi Arabika 1kg',         80000, 95000,  1, 3,  'Stok mingguan'],
            ['operasional usaha', 'Biji Kopi Robusta 1kg',         60000, 70000,  1, 3,  'Stok mingguan'],
            ['operasional usaha', 'Susu Full Cream 1L',            16000, 20000,  6, 15, 'Kopi susu & latte'],
            ['operasional usaha', 'Susu Kental Manis 1 kaleng',    14000, 16000,  3, 8,  null],
            ['operasional usaha', 'Gula Pasir 1kg',                13000, 15000,  3, 6,  null],
            ['operasional usaha', 'Gula Aren Cair 1L',             30000, 35000,  2, 4,  'Untuk kopi gula aren'],
            ['operasional usaha', 'Tepung Terigu 1kg',             11000, 13000,  3, 5,  null],
            ['operasional usaha', 'Telur Ayam 1 papan (30 btr)',   52000, 58000,  1, 3,  null],
            ['operasional usaha', 'Mentega 200gr',                 20000, 24000,  2, 4,  null],
            ['operasional usaha', 'Coklat Bubuk 250gr',            35000, 42000,  1, 3,  null],
            ['operasional usaha', 'Matcha Powder 100gr',           90000, 100000, 1, 2,  'Import grade A'],
            ['operasional usaha', 'Taro Powder 500gr',             72000, 80000,  1, 2,  null],
            ['operasional usaha', 'Simple Syrup 750ml',            25000, 30000,  2, 5,  null],
            ['operasional usaha', 'Sirup Hazelnut 750ml',          55000, 65000,  1, 2,  'Untuk varian latte'],
            ['operasional usaha', 'Sirup Karamel 750ml',           50000, 60000,  1, 2,  null],
            ['operasional usaha', 'Santan Kara 200ml',             11000, 13000,  4, 8,  null],
            ['operasional usaha', 'Es Batu Balok',                 13000, 17000,  5, 12, 'Stok harian'],
            ['operasional usaha', 'Roti Tawar Kupas',              16000, 20000,  3, 6,  null],
            ['operasional usaha', 'Keju Slice (10 lembar)',        30000, 35000,  2, 4,  null],
            ['operasional usaha', 'Kentang 1kg',                   13000, 16000,  3, 6,  null],
            ['operasional usaha', 'Daging Ayam Fillet 1kg',        35000, 42000,  2, 4,  null],
            ['operasional usaha', 'Sosis Ayam (10 pcs)',           22000, 28000,  3, 6,  null],
            ['operasional usaha', 'Alpukat 1kg',                   20000, 25000,  2, 4,  null],
            ['operasional usaha', 'Pisang Kepok 1 sisir',          15000, 20000,  2, 4,  null],
            ['operasional usaha', 'Minyak Goreng 2L',              35000, 42000,  2, 4,  null],
            ['operasional usaha', 'Beras Premium 5kg',             65000, 75000,  1, 2,  null],
            ['operasional usaha', 'Teh Celup (25 pcs)',            20000, 25000,  2, 4,  null],
            ['operasional usaha', 'Kopi Sachet (10 pcs)',          40000, 48000,  1, 3,  'Cadangan busy hour'],
            ['operasional usaha', 'Mie Kuning Basah 500gr',        10000, 14000,  3, 6,  null],
            ['operasional usaha', 'Saos Tomat 340gr',              18000, 22000,  1, 3,  null],
            ['operasional usaha', 'Saos Sambal 340gr',             20000, 24000,  1, 3,  null],
            ['operasional usaha', 'Kecap Manis 600ml',             22000, 26000,  1, 3,  null],
            ['operasional usaha', 'Garam 500gr',                    5000,  7000,  1, 2,  null],
            ['operasional usaha', 'Merica Bubuk 100gr',            12000, 15000,  1, 2,  null],
            ['operasional usaha', 'Bawang Merah 1kg',              22000, 32000,  1, 3,  'Harga fluktuatif'],
            ['operasional usaha', 'Bawang Putih 1kg',              28000, 38000,  1, 3,  'Harga fluktuatif'],

            // ─── KEMASAN & PERLENGKAPAN SAJI ─────────────────────────
            ['operasional usaha', 'Cup Paper Hot 8oz (50 pcs)',    33000, 38000,  2, 4,  null],
            ['operasional usaha', 'Cup Plastik Cold 16oz (50 pcs)',36000, 42000,  3, 5,  null],
            ['operasional usaha', 'Lid Cup Plastik (50 pcs)',      16000, 20000,  3, 5,  null],
            ['operasional usaha', 'Sedotan Paper (100 pcs)',       22000, 28000,  1, 3,  'Ramah lingkungan'],
            ['operasional usaha', 'Kantong Plastik Kresek',        13000, 17000,  2, 4,  null],
            ['operasional usaha', 'Paper Bag Logo (50 pcs)',       80000, 90000,  1, 2,  'Kemasan takeaway'],
            ['operasional usaha', 'Tisu Makan (500 lembar)',       20000, 25000,  1, 3,  null],
            ['operasional usaha', 'Struk Thermal Roll 80mm',       25000, 30000,  1, 3,  'Printer kasir'],
            ['operasional usaha', 'Kotak Makan Kraft (25 pcs)',    45000, 55000,  1, 2,  null],
            ['operasional usaha', 'Sendok Plastik (50 pcs)',        8000, 12000,  1, 3,  null],
            ['operasional usaha', 'Garpu Plastik (50 pcs)',         8000, 12000,  1, 3,  null],

            // ─── KEBERSIHAN & SANITASI ─────────────────────────────
            ['operasional usaha', 'Sabun Cuci Piring 800ml',       25000, 30000,  1, 3,  null],
            ['operasional usaha', 'Spons Cuci (5 pcs)',            13000, 17000,  1, 2,  null],
            ['operasional usaha', 'Lap Serbet Mikrofiber',         10000, 14000,  3, 6,  null],
            ['operasional usaha', 'Hand Sanitizer 500ml',          32000, 38000,  1, 2,  null],
            ['operasional usaha', 'Cairan Pembersih Lantai 1L',    20000, 25000,  1, 2,  null],
            ['operasional usaha', 'Kantong Sampah Hitam (10 pcs)', 16000, 20000,  1, 3,  null],
            ['operasional usaha', 'Sabun Tangan Cair 250ml',       18000, 22000,  1, 2,  null],
            ['operasional usaha', 'Pewangi Ruangan Spray',         25000, 32000,  1, 2,  null],

            // ─── GAS & BAHAN BAKAR ────────────────────────────────
            ['operasional usaha', 'Gas LPG 12kg',                 185000, 195000, 1, 2, 'Penggantian tabung'],
            ['operasional usaha', 'Gas LPG 3kg',                   22000,  26000, 1, 3, 'Cadangan'],

            // ─── OPERASIONAL KARYAWAN ─────────────────────────────
            ['operasional karyawan', 'Gaji Barista',             1700000, 1900000, 1, 1, 'Pembayaran bulanan'],
            ['operasional karyawan', 'Gaji Kasir',               1500000, 1700000, 1, 1, 'Pembayaran bulanan'],
            ['operasional karyawan', 'Gaji Karyawan Dapur',      1400000, 1600000, 1, 1, 'Pembayaran bulanan'],
            ['operasional karyawan', 'Uang Makan Harian',          22000,   28000, 20, 30, 'Per hari kerja'],
            ['operasional karyawan', 'Insentif Kinerja',          200000,  500000, 1, 1, 'Bonus performa'],
            ['operasional karyawan', 'Uang Transport Karyawan',    50000,  100000, 1, 1, null],
            ['operasional karyawan', 'Perlengkapan APD Karyawan',  80000,  120000, 1, 1, 'Masker & sarung tangan'],

            // ─── UTILITAS ─────────────────────────────────────────
            ['lainnya', 'Tagihan Listrik PLN',       400000, 500000, 1, 1, 'Bulan berjalan'],
            ['lainnya', 'Tagihan Air PDAM',           75000,  95000, 1, 1, 'Bulan berjalan'],
            ['lainnya', 'Paket Internet & WiFi',     185000, 210000, 1, 1, 'Operasional & tamu'],
            ['lainnya', 'Isi Ulang Galon (19L)',       18000,  22000, 2, 5, 'Air minum staff'],

            // ─── PERAWATAN & PERBAIKAN ────────────────────────────
            ['lainnya', 'Service Mesin Espresso',    120000, 180000, 1, 1, 'Perawatan rutin'],
            ['lainnya', 'Penggantian Filter Air',     80000, 100000, 1, 1, null],
            ['lainnya', 'Perbaikan Perabot',          40000,  80000, 1, 2, null],
            ['lainnya', 'Beli Gelas Pengganti',       60000,  80000, 1, 1, 'Ada yang pecah'],
            ['lainnya', 'Tinta & Cartridge Printer',  55000,  75000, 1, 1, null],

            // ─── MARKETING ───────────────────────────────────────
            ['lainnya', 'Cetak Flyer Promo (100 lbr)', 90000, 110000, 1, 1, 'Promo menu baru'],
            ['lainnya', 'Top Up Meta Ads',            100000, 200000, 1, 1, 'Iklan Instagram'],
            ['lainnya', 'Desain Konten Sosmed',        80000, 120000, 1, 1, 'Freelance desainer'],
            ['lainnya', 'Endorse Food Blogger',       150000, 250000, 1, 1, 'Kolaborasi konten'],

            // ─── LAIN-LAIN ────────────────────────────────────────
            ['lainnya', 'Parkir & Transportasi Belanja', 10000, 20000, 2, 6, 'Belanja ke pasar'],
            ['lainnya', 'Biaya Transfer Bank',            5000,  8000, 3, 8, 'Fee ke supplier'],
            ['lainnya', 'Print & Fotokopi Dokumen',       5000, 12000, 1, 4, null],
            ['lainnya', 'Keperluan P3K',                 45000, 65000, 1, 1, 'Kotak obat karyawan'],
            ['lainnya', 'Buku Kas & ATK',                15000, 25000, 1, 2, null],
        ];

        $totalTemplates = count($templates);
        $rows           = [];
        $chunkSize      = 200;
        $grandTotal     = 0;

        for ($i = 0; $i < self::TOTAL; $i++) {
            // Pilih template secara acak
            [$kategori, $item, $hargaMin, $hargaMax, $qtyMin, $qtyMax, $keterangan]
                = $templates[$i % $totalTemplates === 0 && $i > 0
                    ? random_int(0, $totalTemplates - 1)
                    : $i % $totalTemplates];

            // Harga dibulatkan ke kelipatan 500 agar terlihat natural
            $harga = round(random_int($hargaMin, $hargaMax) / 500) * 500;
            $qty   = random_int($qtyMin, $qtyMax);
            $total = $harga * $qty;

            // Waktu acak dalam range 1 bulan
            $addSecs   = random_int(0, $totalSecs);
            $createdAt = $startDate->copy()->addSeconds($addSecs)->format('Y-m-d H:i:s');

            $rows[] = [
                'partner_id'  => $partnerId,
                'kategori'    => $kategori,
                'item'        => $item,
                'harga'       => $harga,
                'qty'         => $qty,
                'total'       => $total,
                'keterangan'  => $keterangan,
                'status'      => 'save',
                'created_at'  => $createdAt,
                'updated_at'  => $createdAt,
            ];

            $grandTotal += $total;

            // Insert per chunk agar tidak habis memory
            if (count($rows) === $chunkSize) {
                DB::table('pengeluarans')->insert($rows);
                $rows = [];
                $this->command->getOutput()->write('.');
            }
        }

        // Sisa data yang belum di-insert
        if (!empty($rows)) {
            DB::table('pengeluarans')->insert($rows);
        }

        $this->command->newLine();
        $this->command->info('✅ ' . self::TOTAL . ' pengeluaran berhasil di-seed untuk partner ID ' . $partnerId . '.');
        $this->command->info('   Periode  : ' . $startDate->toDateString() . ' → ' . $endDate->toDateString() . ' (' . self::RANGE_DAYS . ' hari)');
        $this->command->info('   Total    : Rp ' . number_format($grandTotal, 0, ',', '.'));
        $this->command->info('   Rata-rata: Rp ' . number_format($grandTotal / self::TOTAL, 0, ',', '.') . ' / transaksi');
    }
}
