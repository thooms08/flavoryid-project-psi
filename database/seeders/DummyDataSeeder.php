<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $partnerId = 15; // partner milik user_id = 1
        $timezone  = 'Asia/Jakarta';

        // ──────────────────────────────────────────────
        //  1. 20 MENU DUMMY
        // ──────────────────────────────────────────────
        $menuList = [
            // [nama, kategori, harga, tipe_menu]
            ['Es Kopi Susu Gula Aren',  'Minuman', 18000, 'utama'],
            ['Americano Panas',         'Minuman', 15000, 'utama'],
            ['Matcha Latte',            'Minuman', 22000, 'best_seller'],
            ['Thai Tea Original',       'Minuman', 14000, 'utama'],
            ['Teh Tarik Spesial',       'Minuman', 12000, 'utama'],
            ['Jus Alpukat',             'Minuman', 16000, 'utama'],
            ['Coklat Panas Belgique',   'Minuman', 20000, 'best_seller'],
            ['Lemon Squash',            'Minuman', 13000, 'utama'],
            ['Nasi Goreng Spesial',     'Makanan', 25000, 'best_seller'],
            ['Mie Goreng Telur',        'Makanan', 20000, 'utama'],
            ['Ayam Geprek Sambel Ijo',  'Makanan', 28000, 'best_seller'],
            ['Ayam Bakar Madu',         'Makanan', 32000, 'utama'],
            ['Nasi Uduk Komplit',       'Makanan', 22000, 'utama'],
            ['Kentang Goreng Crispy',   'Snack',   12000, 'utama'],
            ['Pisang Keju Coklat',      'Snack',   14000, 'utama'],
            ['Roti Bakar Nutella',      'Snack',   16000, 'best_seller'],
            ['Cireng Bumbu Rujak',      'Snack',    9000, 'utama'],
            ['Dimsum Ayam 4 pcs',       'Snack',   18000, 'utama'],
            ['Paket Hemat A',           'Paket',   35000, 'best_seller'],
            ['Paket Hemat B',           'Paket',   42000, 'utama'],
        ];

        $menuIds = [];
        foreach ($menuList as [$nama, $kategori, $harga, $tipe]) {
            // skip jika sudah ada (idempotent)
            $existing = DB::table('menus')
                ->where('partner_id', $partnerId)
                ->where('nama_menu', $nama)
                ->first();

            if ($existing) {
                $menuIds[] = ['id' => $existing->id, 'harga' => $existing->harga];
                continue;
            }

            $id = DB::table('menus')->insertGetId([
                'partner_id'  => $partnerId,
                'nama_menu'   => $nama,
                'deskripsi'   => "Menu andalan {$nama} dengan cita rasa khas.",
                'kategori'    => $kategori,
                'harga'       => $harga,
                'tipe_menu'   => $tipe,
                'status_menu' => 'tersedia',
                'stok_menu'   => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $menuIds[] = ['id' => $id, 'harga' => $harga];
        }

        $this->command->info('✅ 20 menu dummy berhasil dibuat / ditemukan.');

        // ──────────────────────────────────────────────
        //  2. 100 TRANSAKSI DUMMY (status = success)
        //     Tersebar dalam 60 hari terakhir dengan
        //     pola realistis: ramai siang & sore,
        //     ramai Jumat–Sabtu, variasi omzet.
        // ──────────────────────────────────────────────
        $namaPelanggan = [
            'Budi Santoso', 'Siti Rahayu', 'Agus Wijaya', 'Dewi Lestari',
            'Rizky Pratama', 'Nur Hidayah', 'Fajar Kurniawan', 'Ayu Permata',
            'Dimas Saputra', 'Rina Fitriani', 'Hendra Gunawan', 'Maya Sari',
            'Arif Rahman', 'Fitri Andini', 'Bagas Setiawan', 'Laila Putri',
            'Yusuf Hakim', 'Citra Wulandari', 'Eko Prasetyo', 'Nadia Amalia',
        ];

        // Distribusi jam — lebih banyak di 10:00-14:00 & 17:00-21:00
        $jamDistribusi = array_merge(
            array_fill(0, 3,  10), array_fill(0, 4,  11),
            array_fill(0, 5,  12), array_fill(0, 5,  13),
            array_fill(0, 4,  14), array_fill(0, 2,  15),
            array_fill(0, 2,  16), array_fill(0, 5,  17),
            array_fill(0, 6,  18), array_fill(0, 6,  19),
            array_fill(0, 5,  20), array_fill(0, 3,  21)
        );

        $metodeBayar = ['cash', 'cash', 'cash', 'qris', 'qris', 'transfer'];

        // Hitung nomor pesanan awal (hindari duplikat)
        $lastNomor = DB::table('orders')
            ->where('partner_id', $partnerId)
            ->max('nomor_pesanan') ?? 0;

        $inserted = 0;

        for ($i = 1; $i <= 100; $i++) {
            // Tanggal acak dalam 60 hari terakhir
            $daysAgo = rand(0, 59);
            $jam     = $jamDistribusi[array_rand($jamDistribusi)];
            $menit   = rand(0, 59);
            $detik   = rand(0, 59);

            $createdAt = Carbon::now($timezone)
                ->subDays($daysAgo)
                ->setTime($jam, $menit, $detik);

            // Pilih 1–3 menu per transaksi
            $jumlahItem  = rand(1, 3);
            $pickedMenus = (array) array_rand($menuIds, min($jumlahItem, count($menuIds)));

            $subTotal      = 0;
            $orderDetails  = [];

            foreach ($pickedMenus as $idx) {
                $menu  = $menuIds[$idx];
                $qty   = rand(1, 3);
                $harga = $menu['harga'];
                $subTotal += $harga * $qty;

                $orderDetails[] = [
                    'menu_id' => $menu['id'],
                    'qty'     => $qty,
                    'harga'   => $harga,
                    'variant' => null,
                ];
            }

            // Diskon kadang ada (20% peluang)
            $nominalDiskon = 0;
            $jenisDiskon   = 'Rp';
            if (rand(1, 5) === 1) {
                $nominalDiskon = rand(1, 3) * 2000; // 2rb / 4rb / 6rb
                $nominalDiskon = min($nominalDiskon, $subTotal);
            }
            $totalBayar = $subTotal - $nominalDiskon;

            $metode    = $metodeBayar[array_rand($metodeBayar)];
            $uangBayar = $metode === 'cash'
                ? $totalBayar + (rand(0, 3) * 5000) // kembalian realistis
                : $totalBayar;
            $kembalian = $uangBayar - $totalBayar;

            $lastNomor++;

            $uuid = (string) Str::uuid();

            // Buat kode struk 6 karakter unik
            do {
                $kodeStruk = Str::lower(Str::random(6));
            } while (DB::table('orders')->where('kode_struk', $kodeStruk)->exists());

            $orderId = DB::table('orders')->insertGetId([
                'partner_id'     => $partnerId,
                'uuid'           => $uuid,
                'kode_struk'     => $kodeStruk,
                'nomor_pesanan'  => $lastNomor,
                'nama_pelanggan' => $namaPelanggan[array_rand($namaPelanggan)],
                'no_whatsapp'    => '08' . rand(100000000, 999999999),
                'no_meja'        => rand(1, 10),
                'keterangan'     => null,
                'total_harga'    => $totalBayar,
                'nominal_diskon' => $nominalDiskon,
                'jenis_diskon'   => $jenisDiskon,
                'metode_bayar'   => $metode,
                'uang_bayar'     => $uangBayar,
                'kembalian'      => $kembalian,
                'status'         => 'success',
                'created_at'     => $createdAt->toDateTimeString(),
                'updated_at'     => $createdAt->toDateTimeString(),
            ]);

            // Insert order details
            foreach ($orderDetails as $detail) {
                DB::table('order_details')->insert([
                    'order_id'   => $orderId,
                    'menu_id'    => $detail['menu_id'],
                    'qty'        => $detail['qty'],
                    'harga'      => $detail['harga'],
                    'variant'    => $detail['variant'],
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->toDateTimeString(),
                ]);
            }

            $inserted++;
        }

        $this->command->info("✅ {$inserted} transaksi dummy berhasil dibuat untuk partner_id = {$partnerId}.");
        $this->command->info('   Tersebar dalam 60 hari terakhir dengan pola jam realistis.');
    }
}
