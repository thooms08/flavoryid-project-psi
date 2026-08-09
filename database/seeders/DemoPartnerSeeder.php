<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoPartnerSeeder extends Seeder
{
    // ─────────────────────────────────────────────
    //  CONFIG
    // ─────────────────────────────────────────────
    const USERNAME  = 'kasir-contoh';
    const PASSWORD  = 'password123';
    const TOTAL_MENU    = 100;
    const TOTAL_ORDER   = 1000;

    public function run(): void
    {
        // ── 1. User ──────────────────────────────
        $userId = DB::table('users')->insertGetId([
            'username'   => self::USERNAME,
            'password'   => Hash::make(self::PASSWORD),
            'role'       => 'kasir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── 2. Partner ───────────────────────────
        $partnerId = DB::table('partners')->insertGetId([
            'user_id'      => $userId,
            'uuid'         => (string) Str::uuid(),
            'nama_manager' => 'Budi Santoso',
            'no_wa'         => '08' . random_int(10000000, 99999999),
            'email'        => 'kasir.contoh@flavory.id',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // ── 3. Informasi Outlet ──────────────────
        $outletId = DB::table('informasi_outlet')->insertGetId([
            'partner_id'    => $partnerId,
            'jenis_outlet'  => 'cafe',
            'nama_outlet'   => 'Warung Kopi Nusantara',
            'foto_outlet'   => 'outlet/demo-outlet.jpg',
            'page_name'     => 'warung-kopi-nusantara',
            'deskripsi'     => 'Kafe cozy dengan menu kopi pilihan nusantara dan makanan ringan yang bikin betah nongkrong seharian.',
            'alamat_lengkap'=> 'Jl. Raya Kuliner No. 88, Karawang',
            'kota'          => 'Karawang',
            'provinsi'      => 'Jawa Barat',
            'maps'          => 'https://maps.google.com',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ── 4. Media QR (status aktif) ───────────
        DB::table('media_qr')->insert([
            'informasi_id'   => $outletId,
            'qr_code_design' => 'original',
            'paket'          => 'eksklusif',
            'status'         => 'aktif',
            'payment_status' => 'success',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // ── 5. Verifikasi (sudah terkonfirmasi) ──
        DB::table('verifikasi')->insert([
            'partner_id'      => $partnerId,
            'kode_verifikasi' => strtoupper(Str::random(6)),
            'konfirmasi'      => true,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ── 6. 100 Menu ──────────────────────────
        $menuIds = $this->seedMenus($partnerId);

        // ── 7. 1000 Transaksi ────────────────────
        $this->seedOrders($partnerId, $menuIds);

        $this->command->info('✅ Demo partner seeded: username=' . self::USERNAME . ' | password=' . self::PASSWORD);
    }

    // ─────────────────────────────────────────────
    //  MENU
    // ─────────────────────────────────────────────
    private function seedMenus(int $partnerId): array
    {
        $menuData = $this->getMenuData();
        $menuIds  = [];

        foreach ($menuData as $item) {
            $menuIds[] = DB::table('menus')->insertGetId(array_merge($item, [
                'partner_id' => $partnerId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        return $menuIds;
    }

    private function getMenuData(): array
    {
        // 10 kategori × 10 item = 100 menu
        $categories = [
            'Kopi Panas' => [
                ['Kopi Hitam Tubruk',   8000,  'Kopi tubruk khas nusantara, bold dan kuat.'],
                ['Americano',           18000, 'Espresso dan air panas, pahit yang menyegarkan.'],
                ['Kopi Susu Tradisional',20000,'Kopi susu dengan gula aren pilihan.'],
                ['Cappuccino',          22000, 'Espresso dengan milk foam lembut.'],
                ['Latte',               23000, 'Espresso dengan susu steamed creamy.'],
                ['Flat White',          24000, 'Double ristretto dengan susu micro-foam.'],
                ['Kopi Jahe',           15000, 'Kopi arabika dengan jahe segar.'],
                ['Kopi Rempah',         18000, 'Kopi dengan campuran rempah pilihan.'],
                ['Cortado',             21000, 'Espresso dengan sedikit susu panas.'],
                ['Macchiato',           20000, 'Espresso dengan setetes foam susu.'],
            ],
            'Kopi Dingin' => [
                ['Es Kopi Susu',        22000, 'Kopi susu khas kafe, segar dan manis.'],
                ['Cold Brew',           25000, 'Kopi diseduh dingin 12 jam, smooth dan pekat.'],
                ['Es Americano',        20000, 'Americano di atas es batu, menyegarkan.'],
                ['Ice Latte',           25000, 'Espresso dengan susu segar dan es batu.'],
                ['Dalgona Coffee',      27000, 'Kopi kocok creamy di atas susu dingin.'],
                ['Es Kopi Gula Aren',   24000, 'Kopi arabika dengan gula aren asli.'],
                ['Vietnamese Drip Ice', 23000, 'Drip coffee ala Vietnam dengan susu kental.'],
                ['Brown Sugar Latte',   26000, 'Latte dengan karamel gula merah.'],
                ['Iced Cappuccino',     24000, 'Cappuccino versi dingin yang menyegarkan.'],
                ['Kopi Kelapa',         25000, 'Cold brew dengan air dan santan kelapa muda.'],
            ],
            'Non-Kopi' => [
                ['Matcha Latte',        26000, 'Teh hijau Jepang dengan susu creamy.'],
                ['Teh Tarik',           15000, 'Teh susu khas Malaysia yang ringan.'],
                ['Coklat Panas',        20000, 'Minuman coklat hangat yang memanjakan.'],
                ['Es Coklat',           22000, 'Coklat dingin dengan susu segar.'],
                ['Taro Latte',          27000, 'Talas ungu dengan susu, unik dan lezat.'],
                ['Red Velvet Latte',    27000, 'Minuman cantik dengan cita rasa kaya.'],
                ['Pandan Latte',        24000, 'Latte dengan ekstrak pandan segar.'],
                ['Es Teh Lemon',        15000, 'Teh dingin dengan perasan lemon segar.'],
                ['Milo Dinosaur',       23000, 'Milo es dengan taburan Milo bubuk.'],
                ['Strawberry Milk',     25000, 'Susu segar dengan sirup stroberi.'],
            ],
            'Makanan Berat' => [
                ['Nasi Goreng Spesial', 25000, 'Nasi goreng dengan telur, ayam, dan kerupuk.'],
                ['Mie Goreng Jawa',     22000, 'Mie goreng ala Jawa dengan bumbu otentik.'],
                ['Nasi Uduk Komplit',   28000, 'Nasi uduk dengan ayam goreng, tempe, dan lalapan.'],
                ['Ayam Geprek',         27000, 'Ayam goreng geprek dengan sambal merah pedas.'],
                ['Nasi Bakar Teri',     24000, 'Nasi bakar dengan teri Medan dan kemangi.'],
                ['Sandwich Telor',      20000, 'Roti panggang dengan telur mata sapi dan sayur.'],
                ['Pasta Carbonara',     35000, 'Pasta creamy ala Italia dengan daging asap.'],
                ['Burger Daging Sapi',  38000, 'Burger premium dengan patty sapi pilihan.'],
                ['Risol Mayo',          15000, 'Risol renyah dengan isian mayo dan sayur.'],
                ['Toast Avocado',       32000, 'Roti panggang dengan alpukat dan telur rebus.'],
            ],
            'Camilan' => [
                ['Kentang Goreng',      18000, 'Kentang renyah dengan saus tomat dan mayonaise.'],
                ['Pisang Goreng Coklat',16000, 'Pisang goreng crispy dengan lelehan coklat.'],
                ['Cireng Bumbu Rujak',  14000, 'Cireng renyah dengan bumbu rujak pedas.'],
                ['Onion Ring',          20000, 'Bawang bombay goreng crispy.'],
                ['Tahu Sutra Crispy',   17000, 'Tahu silken goreng dengan sambal kecap.'],
                ['Roti Bakar Nutella',  22000, 'Roti panggang dengan selai coklat premium.'],
                ['Dimsum 4 Pcs',        25000, 'Dimsum variasi kukus dengan saus tiram.'],
                ['Waffle Original',     28000, 'Waffle hangat dengan butter dan madu.'],
                ['Pancake Blueberry',   30000, 'Pancake lembut dengan topping blueberry segar.'],
                ['Croissant Mentega',   26000, 'Croissant renyah ala Prancis dengan mentega.'],
            ],
            'Dessert' => [
                ['Es Krim 2 Scoop',     25000, 'Es krim premium pilihan rasa vanilla & coklat.'],
                ['Pudding Coklat',      18000, 'Pudding lembut dengan saus karamel.'],
                ['Brownies Fudge',      22000, 'Brownies legit dengan lapisan coklat tebal.'],
                ['Mochi Ice Cream',     28000, 'Mochi kenyal dengan isian es krim lembut.'],
                ['Cheesecake Slice',    35000, 'Cheesecake New York creamy dan segar.'],
                ['Tiramisu',            37000, 'Dessert Italia klasik dengan mascarpone.'],
                ['Panna Cotta',         30000, 'Dessert Italia dengan vanilla bean segar.'],
                ['Creme Brulee',        38000, 'Custard Prancis dengan permukaan karamel.'],
                ['Es Doger',            20000, 'Es campur khas Bandung dengan alpukat.'],
                ['Klepon',              15000, 'Klepon hijau isi gula merah dengan kelapa parut.'],
            ],
            'Sarapan' => [
                ['Bubur Ayam',          20000, 'Bubur lembut dengan topping ayam suwir dan kerupuk.'],
                ['Lontong Sayur',       18000, 'Lontong dengan kuah sayur khas Betawi.'],
                ['Nasi Kuning',         22000, 'Nasi kuning dengan lauk lengkap.'],
                ['Omelette Sayur',      25000, 'Telur dadar isi sayuran segar.'],
                ['Roti Canai',          20000, 'Roti canai gurih dengan kuah kari.'],
                ['Ketan Hitam',         15000, 'Ketan hitam gurih dengan parutan kelapa.'],
                ['Pecel Lele',          24000, 'Lele goreng dengan sambal pecel dan lalapan.'],
                ['Soto Ayam',           22000, 'Soto bening khas Jawa dengan ayam suwir.'],
                ['Kwetiau Goreng',      24000, 'Kwetiau goreng dengan udang dan sayuran.'],
                ['English Breakfast',   40000, 'Telur, sosis, roti panggang, dan baked beans.'],
            ],
            'Minuman Segar' => [
                ['Jus Alpukat',         22000, 'Jus alpukat segar dengan susu kental manis.'],
                ['Jus Mangga',          20000, 'Mangga harum manis diblender segar.'],
                ['Jus Semangka',        18000, 'Semangka segar tanpa biji, manis alami.'],
                ['Jus Nanas',           18000, 'Nanas madu segar dengan sedikit garam.'],
                ['Es Jeruk Peras',      15000, 'Jeruk lokal peras dengan gula batu.'],
                ['Soda Gembira',        18000, 'Sirup buah dengan soda dan susu.'],
                ['Minuman Jahe Merah',  15000, 'Jahe merah hangat dengan madu.'],
                ['Infused Water Lemon', 12000, 'Air putih dengan irisan lemon dan mint.'],
                ['Es Campur',           22000, 'Es campur lengkap dengan kolang-kaling.'],
                ['Cincau Hitam',        15000, 'Minuman cincau segar dengan sirup pandan.'],
            ],
            'Paket Hemat' => [
                ['Paket Kopi + Snack',  35000, 'Kopi susu + 1 snack pilihan.'],
                ['Paket Sarapan A',     30000, 'Bubur ayam + teh panas.'],
                ['Paket Sarapan B',     35000, 'Nasi kuning + jus jeruk.'],
                ['Paket Makan Siang',   45000, 'Nasi goreng + es teh + dessert.'],
                ['Paket Sore',          40000, 'Waffle + es kopi susu.'],
                ['Paket Keluarga',      85000, 'Nasi uduk 2 porsi + 2 minuman pilihan.'],
                ['Paket Meeting',       25000, 'Kopi hitam + roti bakar.'],
                ['Paket Date',          90000, 'Pasta + 2 minuman + dessert.'],
                ['Paket Jumat Berkah',  50000, 'Nasi bakar + soto + minuman.'],
                ['Paket Weekend',       70000, 'Full English + 2 latte pilihan.'],
            ],
            'Minuman Botol' => [
                ['Air Mineral 600ml',    5000, 'Air mineral segar.'],
                ['Teh Kotak',            8000, 'Teh dalam kemasan kotak.'],
                ['Yakult',               6000, 'Minuman susu fermentasi menyehatkan.'],
                ['Pocari Sweat',        12000, 'Minuman isotonik pengganti ion tubuh.'],
                ['Floridina Jeruk',     10000, 'Minuman jeruk segar dalam botol.'],
                ['Le Minerale 1500ml',   8000, 'Air mineral botol besar.'],
                ['Good Day Mocca',      10000, 'Kopi sachet dalam kemasan botol.'],
                ['Fanta Strawberry',    12000, 'Minuman soda rasa stroberi.'],
                ['Sprite Dingin',       12000, 'Minuman soda lemon lime segar.'],
                ['Coca-Cola',           13000, 'Minuman soda khas yang ikonik.'],
            ],
        ];

        $rows = [];
        $counter = 0;

        foreach ($categories as $kategori => $items) {
            foreach ($items as [$nama, $harga, $desc]) {
                $counter++;
                $rows[] = [
                    'nama_menu'   => $nama,
                    'deskripsi'   => $desc,
                    'kategori'    => $kategori,
                    'harga'       => $harga,
                    'tipe_menu'   => $counter <= 20 ? 'best_seller' : 'utama', // 20 best seller
                    'status_menu' => 'tersedia',
                    'stok_menu'   => null, // tanpa batasan stok
                    'gambar'      => null,
                ];
                if ($counter >= self::TOTAL_MENU) break;
            }
            if ($counter >= self::TOTAL_MENU) break;
        }

        return $rows;
    }

    // ─────────────────────────────────────────────
    //  ORDERS
    // ─────────────────────────────────────────────
    private function seedOrders(int $partnerId, array $menuIds): void
    {
        $this->command->info('Seeding ' . self::TOTAL_ORDER . ' orders...');

        $namaList = [
            'Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hendra',
            'Indah', 'Joko', 'Kartika', 'Lina', 'Marko', 'Nina', 'Omar', 'Putri',
            'Qori', 'Raka', 'Sari', 'Tono', 'Umar', 'Vina', 'Wati', 'Xena',
            'Yudi', 'Zara', 'Aldi', 'Bella', 'Candra', 'Dina',
        ];

        $metodeList = ['cash', 'qris', 'transfer'];
        $mejaList   = array_merge(
            range('A', 'F'),
            array_map(fn($n) => (string)$n, range(1, 10))
        );

        $startDate = Carbon::now()->subYear();
        $totalSecs = abs((int) Carbon::now()->diffInSeconds($startDate));
        $menuCount = count($menuIds);
        $nomorCounter = 1;
        $chunkSize    = 100;

        // Buffer per-chunk: uuid → details
        $orderRows       = [];
        $detailsByUuid   = [];

        for ($i = 0; $i < self::TOTAL_ORDER; $i++) {

            $randomSecs = random_int(0, $totalSecs);
            $createdAt  = $startDate->copy()->addSeconds($randomSecs);
            $updatedAt  = $createdAt->copy()->addMinutes(random_int(1, 10));

            // 1–5 item unik per order
            $itemCount    = random_int(1, min(5, $menuCount));
            $shuffled     = $menuIds;
            shuffle($shuffled);
            $sampledMenus = array_slice($shuffled, 0, $itemCount);

            $subTotal    = 0;
            $tempDetails = [];

            foreach ($sampledMenus as $menuId) {
                $qty   = random_int(1, 4);
                $harga = $this->getMenuHarga($menuId, $menuIds);
                $subTotal += $qty * $harga;
                $tempDetails[] = [
                    'menu_id'    => $menuId,
                    'qty'        => $qty,
                    'harga'      => $harga,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ];
            }

            // Diskon ~20% probabilitas
            $nominalDiskon = 0;
            $jenisDiskon   = 'Rp';
            if (random_int(0, 4) === 0) {
                $jenisDiskon   = random_int(0, 1) ? '%' : 'Rp';
                $nominalDiskon = $jenisDiskon === '%'
                    ? random_int(5, 20)
                    : round(random_int(2000, 10000) / 1000) * 1000;
                $potongan  = $jenisDiskon === '%'
                    ? ($subTotal * $nominalDiskon / 100)
                    : $nominalDiskon;
                $totalHarga = max(0, $subTotal - $potongan);
            } else {
                $totalHarga = $subTotal;
            }

            $metode    = $metodeList[array_rand($metodeList)];
            $uangBayar = $metode === 'cash'
                ? ceil($totalHarga / 1000) * 1000 + (random_int(0, 3) * 5000)
                : $totalHarga;
            $kembalian = max(0, $uangBayar - $totalHarga);

            $uuid      = (string) Str::uuid();
            $kodeStruk = strtolower(Str::random(6));

            $orderRows[] = [
                'partner_id'    => $partnerId,
                'uuid'          => $uuid,
                'kode_struk'    => $kodeStruk,
                'nomor_pesanan' => $nomorCounter++,
                'nama_pelanggan'=> $namaList[array_rand($namaList)],
                'no_whatsapp'   => '08' . random_int(100000000, 999999999),
                'no_meja'       => $mejaList[array_rand($mejaList)],
                'keterangan'    => null,
                'total_harga'   => $totalHarga,
                'nominal_diskon'=> $nominalDiskon,
                'jenis_diskon'  => $jenisDiskon,
                'uang_bayar'    => $uangBayar,
                'metode_bayar'  => $metode,
                'kembalian'     => $kembalian,
                'status'        => 'success',
                'created_at'    => $createdAt,
                'updated_at'    => $updatedAt,
            ];

            $detailsByUuid[$uuid] = $tempDetails;

            if (count($orderRows) === $chunkSize) {
                $this->insertChunk($orderRows, $detailsByUuid);
                $orderRows     = [];
                $detailsByUuid = [];
            }
        }

        // Sisaan
        if (!empty($orderRows)) {
            $this->insertChunk($orderRows, $detailsByUuid);
        }

        $this->command->info('✅ ' . self::TOTAL_ORDER . ' orders seeded.');
    }

    /**
     * Insert 100 orders + semua detail-nya dalam satu chunk.
     * Detail di-map ke order_id lewat uuid.
     */
    private function insertChunk(array $orderRows, array $detailsByUuid): void
    {
        DB::table('orders')->insert($orderRows);

        // Ambil id yang baru diinsert
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

    /**
     * Ambil harga menu dari DB berdasarkan id.
     * Di-cache dalam property agar tidak query 1000x.
     */
    private array $hargaCache = [];

    private function getMenuHarga(int $menuId, array $menuIds): int
    {
        if (!isset($this->hargaCache[$menuId])) {
            // Lazy load semua harga sekaligus pada pemanggilan pertama
            if (empty($this->hargaCache)) {
                $rows = DB::table('menus')
                    ->whereIn('id', $menuIds)
                    ->pluck('harga', 'id')
                    ->toArray();
                foreach ($rows as $id => $harga) {
                    $this->hargaCache[$id] = (int) $harga;
                }
            }
        }

        return $this->hargaCache[$menuId] ?? 15000;
    }
}
