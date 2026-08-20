<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Partner3PengeluaranSeeder extends Seeder
{
    const PARTNER_ID = 3;

    public function run(): void
    {
        // Rentang waktu: acak dalam 1 bulan terakhir
        $startDate = Carbon::now()->subMonth();
        $endDate   = Carbon::now();
        $totalSecs = (int) $startDate->diffInSeconds($endDate);

        // ─────────────────────────────────────────────────────────────
        // Daftar pengeluaran: [kategori, item, harga_satuan, qty, keterangan]
        // Dibuat realistis untuk usaha kafe/warung — bervariasi tiap minggu
        // ─────────────────────────────────────────────────────────────
        $template = [

            // BAHAN BAKU
            ['bahan baku', 'Biji Kopi Arabika 1kg',        85000,  2,    'Pembelian stok mingguan'],
            ['bahan baku', 'Biji Kopi Robusta 1kg',        65000,  2,    'Pembelian stok mingguan'],
            ['bahan baku', 'Susu Full Cream 1L',           18000,  12,   'Untuk kopi susu dan latte'],
            ['bahan baku', 'Gula Pasir 1kg',               14000,  5,    null],
            ['bahan baku', 'Gula Aren Cair 1L',            32000,  3,    'Untuk kopi gula aren'],
            ['bahan baku', 'Tepung Terigu 1kg',            12000,  4,    'Bahan waffle dan pancake'],
            ['bahan baku', 'Telur Ayam 1 Papan (30 btr)',  55000,  2,    null],
            ['bahan baku', 'Mentega 200gr',                22000,  3,    'Blue Band & Elle Vire'],
            ['bahan baku', 'Coklat Bubuk 250gr',           38000,  2,    'Untuk minuman coklat'],
            ['bahan baku', 'Matcha Powder 100gr',          95000,  1,    'Import grade A'],
            ['bahan baku', 'Taro Powder 500gr',            75000,  1,    'Untuk taro latte'],
            ['bahan baku', 'Simple Syrup 750ml',           28000,  4,    null],
            ['bahan baku', 'Santan Kara 200ml',            12000,  6,    null],
            ['bahan baku', 'Es Batu Balok',                15000,  8,    'Stok harian dari supplier'],
            ['bahan baku', 'Roti Tawar Kupas',             18000,  5,    'Untuk toast dan sandwich'],
            ['bahan baku', 'Keju Slice 10 lembar',         32000,  3,    null],
            ['bahan baku', 'Kentang 1kg',                  14000,  4,    'Untuk kentang goreng'],
            ['bahan baku', 'Daging Ayam Fillet 1kg',       38000,  3,    null],
            ['bahan baku', 'Sosis Ayam 10 pcs',            25000,  4,    null],
            ['bahan baku', 'Alpukat 1kg',                  22000,  3,    'Untuk jus dan toast'],
            ['bahan baku', 'Pisang Kepok 1 sisir',         18000,  3,    'Untuk pisang goreng'],
            ['bahan baku', 'Mie Kuning Basah 500gr',       12000,  4,    null],
            ['bahan baku', 'Beras 5kg',                    68000,  2,    'Pandan wangi premium'],
            ['bahan baku', 'Kopi Sachet Nescafe',          45000,  2,    'Cadangan untuk busy hour'],
            ['bahan baku', 'Teh Celup Sosro 25 pcs',       22000,  3,    null],

            // OPERASIONAL USAHA
            ['operasional usaha', 'Cup Paper Hot 8oz (50 pcs)',   35000,  3,  'Untuk minuman panas'],
            ['operasional usaha', 'Cup Plastik Cold 16oz (50 pcs)',38000,  4,  'Untuk minuman dingin'],
            ['operasional usaha', 'Lid Cup Plastik (50 pcs)',     18000,  4,  null],
            ['operasional usaha', 'Sedotan Paper (100 pcs)',      25000,  2,  'Ramah lingkungan'],
            ['operasional usaha', 'Kantong Plastik Kresek',       15000,  3,  null],
            ['operasional usaha', 'Paper Bag Logo (50 pcs)',      85000,  1,  'Branding kemasan takeaway'],
            ['operasional usaha', 'Tisu Makan (500 lembar)',      22000,  2,  null],
            ['operasional usaha', 'Sabun Cuci Piring 800ml',      28000,  2,  null],
            ['operasional usaha', 'Spons Cuci Piring (5 pcs)',    15000,  1,  null],
            ['operasional usaha', 'Lap Serbet Mikrofiber',        12000,  4,  null],
            ['operasional usaha', 'Hand Sanitizer 500ml',         35000,  1,  null],
            ['operasional usaha', 'Cairan Pembersih Lantai 1L',   22000,  2,  null],
            ['operasional usaha', 'Kantong Sampah Hitam (10 pcs)',18000,  2,  null],
            ['operasional usaha', 'Gas LPG 12kg',                190000, 1,  'Penggantian tabung'],
            ['operasional usaha', 'Minyak Goreng 2L',            38000,  3,  null],
            ['operasional usaha', 'Struk Thermal Roll 80mm',     28000,  2,  'Untuk printer kasir'],
            ['operasional usaha', 'Tinta Stempel',               15000,  1,  null],
            ['operasional usaha', 'Buku Kas Harian',             18000,  1,  null],

            // GAJI & SDM
            ['gaji & sdm', 'Gaji Karyawan Barista',      1800000, 1, 'Pembayaran bulan berjalan'],
            ['gaji & sdm', 'Gaji Karyawan Kasir',        1600000, 1, 'Pembayaran bulan berjalan'],
            ['gaji & sdm', 'Insentif Kinerja Tim',        300000, 1, 'Bonus target tercapai'],
            ['gaji & sdm', 'Uang Makan Karyawan',         25000,  26, 'Per hari kerja'],

            // UTILITAS
            ['utilitas', 'Tagihan Listrik',              450000, 1, 'PLN bulan berjalan'],
            ['utilitas', 'Tagihan Air PDAM',              85000, 1, 'Bulan berjalan'],
            ['utilitas', 'Paket Internet WiFi',          195000, 1, 'Untuk operasional & WiFi pelanggan'],
            ['utilitas', 'Isi Ulang Galon Air Minum',    20000,  4, 'Untuk staff'],

            // PERAWATAN & PERBAIKAN
            ['perawatan & perbaikan', 'Service Mesin Espresso',      150000, 1, 'Perawatan rutin bulanan'],
            ['perawatan & perbaikan', 'Penggantian Filter Air',       85000, 1, null],
            ['perawatan & perbaikan', 'Perbaikan Kursi Retak',        45000, 2, null],
            ['perawatan & perbaikan', 'Cat Ulang Dinding (partial)',  120000, 1, 'Area masuk pintu'],
            ['perawatan & perbaikan', 'Beli Gelas Pengganti (6 pcs)', 65000, 1, 'Ada yang pecah'],

            // MARKETING
            ['marketing', 'Cetak Flyer Promo A5 (100 lbr)', 95000,  1, 'Promo menu baru'],
            ['marketing', 'Endorse Food Blogger',           200000, 1, 'Kolaborasi konten IG'],
            ['marketing', 'Top Up Meta Ads',                150000, 1, 'Iklan Instagram & Facebook'],
            ['marketing', 'Desain Konten Sosmed',           100000, 1, 'Freelance desainer'],

            // LAIN-LAIN
            ['lain-lain', 'Parkir & Transportasi Belanja', 15000, 4, 'Belanja ke pasar/supplier'],
            ['lain-lain', 'Print & Fotokopi Dokumen',       8000, 3, null],
            ['lain-lain', 'Biaya Transfer Bank',             6500, 5, 'Fee transfer ke supplier'],
            ['lain-lain', 'Keperluan P3K',                 55000, 1, 'Kotak obat karyawan'],
        ];

        $rows = [];

        foreach ($template as [$kategori, $item, $harga, $qty, $keterangan]) {
            // Waktu acak dalam 1 bulan terakhir
            $createdAt = $startDate->copy()->addSeconds(random_int(0, $totalSecs));

            $rows[] = [
                'partner_id'  => self::PARTNER_ID,
                'kategori'    => $kategori,
                'item'        => $item,
                'harga'       => $harga,
                'qty'         => $qty,
                'total'       => $harga * $qty,
                'keterangan'  => $keterangan,
                'status'      => 'save',
                'created_at'  => $createdAt,
                'updated_at'  => $createdAt,
            ];
        }

        DB::table('pengeluarans')->insert($rows);

        $total = collect($rows)->sum('total');

        $this->command->info('✅ ' . count($rows) . ' data pengeluaran berhasil di-seed untuk partner ' . self::PARTNER_ID . '.');
        $this->command->info('   Total pengeluaran: Rp ' . number_format($total, 0, ',', '.'));
    }
}
