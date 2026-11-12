<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ComprehensivePartnerSeeder extends Seeder
{
    // ─────────────────────────────────────────────
    //  CONFIG
    // ─────────────────────────────────────────────
    const KASIR_USERNAME = 'kasir-flavory';
    const KASIR_PASSWORD = 'password123';
    const OWNER_USERNAME = 'owner-flavory';
    const OWNER_PASSWORD = 'password123';

    const TOTAL_MENU = 50;
    const TOTAL_SUCCESS_ORDERS = 1000;
    const TOTAL_FAILED_ORDERS = 200;
    const TOTAL_PENGELUARAN = 400;
    const TOTAL_MODAL = 10;

    // Periode data: Juli 2026 - Oktober 2026
    const START_DATE = '2026-07-01';
    const END_DATE = '2026-10-31';

    public function run(): void
    {
        // ── 1. Hapus data lama jika ada ─────────────────────
        $this->command->info('Menghapus data lama...');

        // Hapus dalam urutan yang benar untuk menghindari constraint violation
        DB::table('order_details')->delete();
        DB::table('orders')->delete();
        DB::table('variant_level')->delete();
        DB::table('menus')->delete();
        DB::table('bukti_pengeluarans')->delete();
        DB::table('pengeluarans')->delete();
        DB::table('stok_harian')->delete();
        DB::table('stok_barang')->delete();
        DB::table('modal')->delete();
        DB::table('verifikasi')->delete();
        DB::table('media_qr')->delete();
        DB::table('informasi_outlet')->delete();
        DB::table('owner_kasir')->delete();
        DB::table('partners')->delete();
        DB::table('users')->whereIn('username', [self::KASIR_USERNAME, self::OWNER_USERNAME])->delete();

        $this->command->info('✅ Data lama berhasil dihapus');

        // ── 2. Buat User Kasir ──────────────────────────────
        $kasirUserId = DB::table('users')->insertGetId([
            'username'   => self::KASIR_USERNAME,
            'password'   => Hash::make(self::KASIR_PASSWORD),
            'role'       => 'kasir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── 3. Buat User Owner ──────────────────────────────
        $ownerUserId = DB::table('users')->insertGetId([
            'username'   => self::OWNER_USERNAME,
            'password'   => Hash::make(self::OWNER_PASSWORD),
            'role'       => 'owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── 4. Buat Partner untuk Kasir ─────────────────────
        $partnerId = DB::table('partners')->insertGetId([
            'user_id'      => $kasirUserId,
            'uuid'         => (string) Str::uuid(),
            'nama_manager' => 'Ahmad Surya',
            'no_wa'        => '0812' . random_int(1000000, 9999999),
            'email'        => 'ahmad.surya@flavory.id',
            'show_ikhtisar_bisnis' => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // ── 5. Hubungkan Owner dengan Kasir ─────────────────
        DB::table('owner_kasir')->insert([
            'owner_id'    => $ownerUserId,
            'kasir_id'    => $kasirUserId,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ── 6. Informasi Outlet ─────────────────────────────
        $outletId = DB::table('informasi_outlet')->insertGetId([
            'partner_id'    => $partnerId,
            'jenis_outlet'  => 'cafe',
            'nama_outlet'   => 'Flavory Café Premium',
            'foto_outlet'   => 'outlet/flavory-premium.jpg',
            'page_name'     => 'flavory-cafe-premium',
            'deskripsi'     => 'Kafe modern dengan konsep nongkrong yang nyaman dan menu kopi berkualitas tinggi.',
            'alamat_lengkap'=> 'Jl. Sudirman No. 123, Jakarta Pusat',
            'kota'          => 'Jakarta',
            'provinsi'      => 'DKI Jakarta',
            'maps'          => 'https://maps.google.com/?q=Flavory+Cafe+Premium',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ── 7. Media QR (status aktif) ──────────────────────
        DB::table('media_qr')->insert([
            'informasi_id'   => $outletId,
            'qr_code_design' => 'original',
            'paket'          => 'eksklusif',
            'status'         => 'aktif',
            'payment_status' => 'success',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // ── 8. Verifikasi (sudah terkonfirmasi) ─────────────
        DB::table('verifikasi')->insert([
            'partner_id'      => $partnerId,
            'kode_verifikasi' => strtoupper(Str::random(6)),
            'konfirmasi'      => true,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ── 9. Seed 50 Menu ─────────────────────────────────
        $this->command->info('Membuat 50 menu...');
        $menuIds = $this->seedMenus($partnerId);

        // ── 10. Seed 1000 Transaksi Berhasil & 200 Gagal ─────
        $this->command->info('Membuat 1000 transaksi berhasil dan 200 gagal...');
        $this->seedOrders($partnerId, $menuIds);

        // ── 11. Seed 400 Data Pengeluaran ───────────────────
        $this->command->info('Membuat 400 data pengeluaran...');
        $this->seedPengeluaran($partnerId);

        // ── 12. Seed 10 Data Modal ──────────────────────────
        $this->command->info('Membuat 10 data modal...');
        $this->seedModal($partnerId);

        // ── 13. Seed Data Stok Barang ───────────────────────
        $this->command->info('Membuat data stok barang...');
        $this->seedStokBarang($partnerId);

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('📋 Detail akun:');
        $this->command->info('  Kasir - Username: ' . self::KASIR_USERNAME . ' | Password: ' . self::KASIR_PASSWORD);
        $this->command->info('  Owner - Username: ' . self::OWNER_USERNAME . ' | Password: ' . self::OWNER_PASSWORD);
        $this->command->info('📊 Statistik data:');
        $this->command->info('  - 50 menu');
        $this->command->info('  - 1000 transaksi berhasil');
        $this->command->info('  - 200 transaksi gagal');
        $this->command->info('  - 400 data pengeluaran');
        $this->command->info('  - 10 data modal');
        $this->command->info('  - Data stok barang');
        $this->command->info('📅 Periode data: Juli 2026 - Oktober 2026');
    }

    // ─────────────────────────────────────────────
    //  MENU (50 MENU)
    // ─────────────────────────────────────────────
    private function seedMenus(int $partnerId): array
    {
        $menuCategories = [
            'Kopi Panas' => [
                ['Kopi Hitam Tubruk', 8000],
                ['Americano', 18000],
                ['Kopi Susu Tradisional', 20000],
                ['Cappuccino', 22000],
                ['Latte', 23000],
            ],
            'Kopi Dingin' => [
                ['Es Kopi Susu', 22000],
                ['Cold Brew', 25000],
                ['Ice Latte', 25000],
                ['Dalgona Coffee', 27000],
                ['Es Kopi Gula Aren', 24000],
            ],
            'Non-Kopi' => [
                ['Matcha Latte', 26000],
                ['Teh Tarik', 15000],
                ['Coklat Panas', 20000],
                ['Es Coklat', 22000],
                ['Taro Latte', 27000],
            ],
            'Makanan Berat' => [
                ['Nasi Goreng Spesial', 25000],
                ['Mie Goreng Jawa', 22000],
                ['Nasi Uduk Komplit', 28000],
                ['Ayam Geprek', 27000],
                ['Sandwich Telor', 20000],
            ],
            'Camilan' => [
                ['Kentang Goreng', 18000],
                ['Pisang Goreng Coklat', 16000],
                ['Cireng Bumbu Rujak', 14000],
                ['Onion Ring', 20000],
                ['Tahu Sutra Crispy', 17000],
            ],
            'Dessert' => [
                ['Es Krim 2 Scoop', 25000],
                ['Pudding Coklat', 18000],
                ['Brownies Fudge', 22000],
                ['Mochi Ice Cream', 28000],
                ['Cheesecake Slice', 35000],
            ],
            'Minuman Segar' => [
                ['Jus Alpukat', 22000],
                ['Jus Mangga', 20000],
                ['Jus Semangka', 18000],
                ['Jus Nanas', 18000],
                ['Es Jeruk Peras', 15000],
            ],
            'Paket Hemat' => [
                ['Paket Kopi + Snack', 35000],
                ['Paket Sarapan', 30000],
                ['Paket Makan Siang', 45000],
                ['Paket Sore', 40000],
                ['Paket Meeting', 25000],
            ],
            'Minuman Botol' => [
                ['Air Mineral 600ml', 5000],
                ['Teh Kotak', 8000],
                ['Yakult', 6000],
                ['Pocari Sweat', 12000],
                ['Coca-Cola', 13000],
            ],
            'Spesial' => [
                ['Signature Coffee', 35000],
                ['Special Blend Tea', 28000],
                ['Premium Dessert', 45000],
                ['Chef Special', 50000],
                ['Seasonal Fruit Drink', 30000],
            ],
        ];

        $menuIds = [];
        $counter = 0;

        foreach ($menuCategories as $kategori => $items) {
            foreach ($items as [$nama, $harga]) {
                if ($counter >= self::TOTAL_MENU) break;

                $deskripsi = "Menu $kategori berkualitas dengan cita rasa premium";
                // ENUM: hanya 'best_seller' atau 'utama'
                $tipeMenu = $counter < 10 ? 'best_seller' : 'utama';

                $menuIds[] = DB::table('menus')->insertGetId([
                    'partner_id'  => $partnerId,
                    'nama_menu'   => $nama,
                    'deskripsi'   => $deskripsi,
                    'kategori'    => $kategori,
                    'harga'       => $harga,
                    'tipe_menu'   => $tipeMenu,
                    'status_menu' => 'tersedia',
                    'stok_menu'   => null,
                    'gambar'      => null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                $counter++;
            }
            if ($counter >= self::TOTAL_MENU) break;
        }

        $this->command->info('✅ ' . count($menuIds) . ' menu berhasil dibuat');
        return $menuIds;
    }

    // ─────────────────────────────────────────────
    //  ORDERS (1000 SUCCESS + 200 FAILED)
    // ─────────────────────────────────────────────
    private function seedOrders(int $partnerId, array $menuIds): void
    {
        $startDate = Carbon::parse(self::START_DATE);
        $endDate = Carbon::parse(self::END_DATE);
        $totalDays = (int) $startDate->diffInDays($endDate);

        $customerNames = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hendra', 'Indah', 'Joko'];
        $paymentMethods = ['cash', 'qris', 'transfer'];
        $tables = ['A1', 'A2', 'A3', 'B1', 'B2', 'B3', 'C1', 'C2', 'C3', 'Meja 1', 'Meja 2', 'Take Away'];

        $totalOrders = self::TOTAL_SUCCESS_ORDERS + self::TOTAL_FAILED_ORDERS;
        $menuCount = count($menuIds);

        $orderChunks = [];
        $detailChunks = [];
        $orderCounter = 1;

        for ($i = 0; $i < $totalOrders; $i++) {
            // Generate random date within July-October 2026
            $randomDay = random_int(0, $totalDays);
            $orderDate = $startDate->copy()->addDays($randomDay);

            // Random time between 08:00 and 22:00
            $randomHour = random_int(8, 21);
            $randomMinute = random_int(0, 59);
            $createdAt = $orderDate->copy()->setTime($randomHour, $randomMinute);
            $updatedAt = $createdAt->copy()->addMinutes(random_int(1, 15));

            // 200 pertama adalah failed, sisanya success
            // ENUM status: 'pending', 'success', 'fail'
            $isFailed = $i < self::TOTAL_FAILED_ORDERS;
            $status = $isFailed ? 'fail' : 'success';

            // 1-4 items per order
            $itemCount = random_int(1, min(4, $menuCount));
            $selectedMenus = [];
            $subtotal = 0;

            // Randomly select menus
            for ($j = 0; $j < $itemCount; $j++) {
                $menuId = $menuIds[array_rand($menuIds)];
                $quantity = random_int(1, 3);
                $price = $this->getRandomMenuPrice();

                $selectedMenus[] = [
                    'menu_id' => $menuId,
                    'qty' => $quantity,
                    'harga' => $price,
                ];

                $subtotal += $quantity * $price;
            }

            // Apply discount (20% chance, hanya untuk success)
            $discount = 0;
            $discountType = 'Rp';
            if (random_int(1, 5) === 1 && !$isFailed) {
                $discountType = random_int(0, 1) ? '%' : 'Rp';
                $discount = $discountType === '%'
                    ? random_int(5, 15)
                    : round(random_int(2000, 8000) / 1000) * 1000;
            }

            // Calculate total
            if ($discountType === '%') {
                $total = $subtotal - ($subtotal * $discount / 100);
            } else {
                $total = max(0, $subtotal - $discount);
            }

            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            // For failed orders, set appropriate values
            if ($isFailed) {
                $total = 0;
                $discount = 0;
                $paymentMethod = 'cash';
            }

            $uuid = (string) Str::uuid();
            $receiptCode = strtoupper(Str::random(6));

            $orderChunks[] = [
                'partner_id'    => $partnerId,
                'uuid'          => $uuid,
                'kode_struk'    => $receiptCode,
                'nomor_pesanan' => $orderCounter++,
                'nama_pelanggan'=> $customerNames[array_rand($customerNames)],
                'no_whatsapp'   => '08' . random_int(100000000, 999999999),
                'no_meja'       => $tables[array_rand($tables)],
                'keterangan'    => $isFailed ? 'Pesanan dibatalkan' : null,
                'total_harga'   => $total,
                'nominal_diskon'=> $discount,
                'jenis_diskon'  => $discountType,
                'uang_bayar'    => $total,
                'metode_bayar'  => $paymentMethod,
                'kembalian'     => 0,
                'status'        => $status,
                'created_at'    => $createdAt,
                'updated_at'    => $updatedAt,
            ];

            // Store details for later insertion
            $detailChunks[$uuid] = [
                'menus' => $selectedMenus,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            // Insert in chunks of 100
            if (count($orderChunks) >= 100) {
                $this->insertOrderChunk($orderChunks, $detailChunks);
                $orderChunks = [];
                $detailChunks = [];
            }
        }

        // Insert remaining orders
        if (!empty($orderChunks)) {
            $this->insertOrderChunk($orderChunks, $detailChunks);
        }

        $this->command->info('✅ ' . self::TOTAL_SUCCESS_ORDERS . ' transaksi berhasil dan ' . self::TOTAL_FAILED_ORDERS . ' transaksi gagal berhasil dibuat');
    }

    private function insertOrderChunk(array $orderChunks, array $detailChunks): void
    {
        // Insert orders
        DB::table('orders')->insert($orderChunks);

        // Get inserted order IDs
        $uuids = array_column($orderChunks, 'uuid');
        $insertedOrders = DB::table('orders')
            ->whereIn('uuid', $uuids)
            ->pluck('id', 'uuid')
            ->toArray();

        // Prepare order details
        $detailsToInsert = [];
        foreach ($detailChunks as $uuid => $detailData) {
            $orderId = $insertedOrders[$uuid] ?? null;
            if (!$orderId) continue;

            foreach ($detailData['menus'] as $menu) {
                $detailsToInsert[] = [
                    'order_id'   => $orderId,
                    'menu_id'    => $menu['menu_id'],
                    'qty'        => $menu['qty'],
                    'harga'      => $menu['harga'],
                    'variant'    => null,
                    'created_at' => $detailData['created_at'],
                    'updated_at' => $detailData['updated_at'],
                ];
            }
        }

        // Insert order details
        if (!empty($detailsToInsert)) {
            DB::table('order_details')->insert($detailsToInsert);
        }
    }

    private function getRandomMenuPrice(): int
    {
        $prices = [5000, 8000, 10000, 15000, 18000, 20000, 22000, 25000, 28000, 30000, 35000, 40000];
        return $prices[array_rand($prices)];
    }

    // ─────────────────────────────────────────────
    //  PENGELUARAN (400 DATA)
    // ─────────────────────────────────────────────
    private function seedPengeluaran(int $partnerId): void
    {
        $startDate = Carbon::parse(self::START_DATE);
        $endDate = Carbon::parse(self::END_DATE);
        $totalDays = (int) $startDate->diffInDays($endDate);

        $expenseCategories = [
            'operasional usaha' => [
                'items' => ['Kopi Arabika', 'Kopi Robusta', 'Susu Segar', 'Gula Pasir', 'Es Batu', 'Buah Segar', 'Sayuran', 'Daging Ayam', 'Ikan', 'Telur'],
                'price_range' => [50000, 500000],
            ],
            'operasional karyawan' => [
                'items' => ['Gaji Kasir', 'Gaji Barista', 'Gaji Waiters', 'Gaji Cleaning Service'],
                'price_range' => [1500000, 5000000],
            ],
            'lainnya' => [
                'items' => ['Listrik', 'Air', 'Gas', 'Internet', 'Telepon', 'Kebersihan', 'Keamanan', 'Parkir', 'Servis Mesin Kopi', 'Perbaikan AC', 'Iklan Social Media', 'Flyer', 'Spanduk'],
                'price_range' => [50000, 2000000],
            ],
        ];

        $expensesToInsert = [];

        for ($i = 0; $i < self::TOTAL_PENGELUARAN; $i++) {
            // Random date within period
            $randomDay = random_int(0, $totalDays);
            $expenseDate = $startDate->copy()->addDays($randomDay);

            // Random category
            $category = array_rand($expenseCategories);
            $categoryData = $expenseCategories[$category];

            // Random item from category
            $item = $categoryData['items'][array_rand($categoryData['items'])];

            // Random quantity and price
            $quantity = random_int(1, 10);
            $price = random_int($categoryData['price_range'][0], $categoryData['price_range'][1]);
            $total = $price * $quantity;

            // ENUM status: 'save' atau 'delete' (mayoritas save)
            $status = random_int(1, 10) <= 8 ? 'save' : 'delete';

            $expensesToInsert[] = [
                'partner_id' => $partnerId,
                'kategori'   => $category,
                'keterangan'  => "Pengeluaran untuk $item",
                'item'        => $item,
                'harga'       => $price,
                'qty'         => $quantity,
                'total'       => $total,
                'status'      => $status,
                'created_at'  => $expenseDate,
                'updated_at'  => $expenseDate,
            ];

            // Insert in chunks of 100
            if (count($expensesToInsert) >= 100) {
                DB::table('pengeluarans')->insert($expensesToInsert);
                $expensesToInsert = [];
            }
        }

        // Insert remaining
        if (!empty($expensesToInsert)) {
            DB::table('pengeluarans')->insert($expensesToInsert);
        }
    }

    // ─────────────────────────────────────────────
    //  MODAL (10 DATA - Juli-Agustus 2026)
    // ─────────────────────────────────────────────
    private function seedModal(int $partnerId): void
    {
        $startDate = Carbon::parse('2026-07-01');
        $endDate = Carbon::parse('2026-08-31');
        $totalDays = (int) $startDate->diffInDays($endDate);

        $modalTypes = [
            ['Investasi Awal', 50000000],
            ['Tambahan Modal', 25000000],
            ['Pinjaman Bank', 75000000],
            ['Investor', 100000000],
            ['Tabungan Pribadi', 30000000],
        ];

        $modalToInsert = [];

        for ($i = 0; $i < self::TOTAL_MODAL; $i++) {
            // Random date between July-August 2026
            $randomDay = random_int(0, $totalDays);
            $modalDate = $startDate->copy()->addDays($randomDay);

            // Random modal type
            $modalType = $modalTypes[array_rand($modalTypes)];

            // Random nominal (variation from base)
            $baseAmount = $modalType[1];
            $variation = random_int(-20, 20) * 1000000; // ±20 juta
            $nominal = max(10000000, $baseAmount + $variation); // Minimum 10 juta

            $modalToInsert[] = [
                'partner_id' => $partnerId,
                'nominal'     => $nominal,
                'keterangan'  => $modalType[0],
                'status'      => 'save', // ENUM: 'save' atau 'delete'
                'created_at'  => $modalDate,
                'updated_at'  => $modalDate,
            ];
        }

        DB::table('modal')->insert($modalToInsert);
    }

    // ─────────────────────────────────────────────
    //  STOK BARANG
    // ─────────────────────────────────────────────
    private function seedStokBarang(int $partnerId): void
    {
        $items = [
            ['Kopi Arabika', 'kg', 5, 20, 3, 5, 0, 3],
            ['Kopi Robusta', 'kg', 3, 15, 2, 3, 0, 2],
            ['Susu Segar', 'liter', 10, 30, 5, 10, 0, 5],
            ['Gula Pasir', 'kg', 8, 25, 4, 8, 0, 4],
            ['Es Batu', 'kg', 20, 50, 10, 20, 0, 10],
            ['Buah Segar', 'kg', 5, 15, 3, 5, 0, 3],
            ['Plastik Cup', 'pcs', 100, 500, 50, 100, 0, 50],
            ['Sedotan', 'pcs', 200, 1000, 100, 200, 0, 100],
            ['Kertas Receipt', 'roll', 2, 10, 1, 2, 0, 1],
            ['Kemasan Makanan', 'pcs', 50, 200, 25, 50, 0, 25],
        ];

        $stokBarangIds = [];

        // Insert stok barang
        foreach ($items as $item) {
            $stokBarangId = DB::table('stok_barang')->insertGetId([
                'partner_id'   => $partnerId,
                'nama_barang'  => $item[0],
                'satuan'       => $item[1],
                'min_hijau'    => $item[2],
                'max_hijau'    => $item[3],
                'min_kuning'   => $item[4],
                'max_kuning'   => $item[5],
                'min_merah'    => $item[6],
                'max_merah'    => $item[7],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $stokBarangIds[] = $stokBarangId;
        }

        // Seed stok harian untuk 4 bulan
        $this->seedStokHarian($partnerId, $stokBarangIds);
    }

    private function seedStokHarian(int $partnerId, array $stokBarangIds): void
    {
        $startDate = Carbon::parse(self::START_DATE);
        $endDate = Carbon::parse(self::END_DATE);
        $totalDays = (int) $startDate->diffInDays($endDate);

        $stokHarianToInsert = [];

        // Create stock data for each item every 3 days
        foreach ($stokBarangIds as $itemId) {
            for ($day = 0; $day <= $totalDays; $day += 3) {
                $stockDate = $startDate->copy()->addDays($day);

                // Random stock amount between 10-100
                $stockAmount = random_int(10, 100);

                $stokHarianToInsert[] = [
                    'stok_barang_id' => $itemId,
                    'tanggal'        => $stockDate->format('Y-m-d'),
                    'jumlah'         => $stockAmount,
                    'created_at'     => $stockDate,
                    'updated_at'     => $stockDate,
                ];

                // Insert in chunks of 100
                if (count($stokHarianToInsert) >= 100) {
                    DB::table('stok_harian')->insert($stokHarianToInsert);
                    $stokHarianToInsert = [];
                }
            }
        }

        // Insert remaining
        if (!empty($stokHarianToInsert)) {
            DB::table('stok_harian')->insert($stokHarianToInsert);
        }
    }
}
