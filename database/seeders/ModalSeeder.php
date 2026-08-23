<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModalSeeder extends Seeder
{
    /**
     * Seed 100 modal usaha untuk partner_id tertentu dalam 1 bulan ke belakang.
     *
     * Jalankan: php artisan db:seed --class=ModalSeeder
     */
    const PARTNER_ID = 2;
    const TOTAL      = 100;
    const RANGE_DAYS = 30; // 1 bulan ke belakang dari hari ini

    public function run(): void
    {
        $partnerId = (int) (env('SEEDER_PARTNER_ID', self::PARTNER_ID));

        // Validasi partner tersedia
        $partnerExists = DB::table('partners')->where('id', $partnerId)->exists();
        if (!$partnerExists) {
            $this->command->error("Partner ID {$partnerId} tidak ditemukan. Seeder dihentikan.");
            return;
        }

        $endDate   = Carbon::now('Asia/Jakarta');
        $startDate = $endDate->copy()->subDays(self::RANGE_DAYS - 1)->startOfDay();
        $totalSecs = (int) $startDate->diffInSeconds($endDate);

        $this->command->info("Seeding " . self::TOTAL . " modal untuk partner ID {$partnerId}...");
        $this->command->info("Range: {$startDate->toDateString()} → {$endDate->toDateString()}");

        // Template keterangan modal yang realistis
        $keterangans = [
            'Setoran modal awal bulan',
            'Tambahan modal dari laba bulan lalu',
            'Investasi bahan baku minggu ini',
            'Penambahan kas operasional',
            'Modal untuk restok stok habis',
            'Tambahan modal darurat',
            'Setoran dari investor',
            'Modal cadangan harian',
            'Penambahan modal belanja pasar',
            'Modal untuk promo akhir bulan',
            'Setoran dari keuntungan cabang',
            'Modal tambahan weekend',
            'Penambahan modal untuk event',
            'Modal untuk beli peralatan baru',
            'Tambahan modal operasional harian',
            null, // beberapa boleh kosong
            null,
            null,
        ];

        // Nominal modal yang realistis (kelipatan 50.000)
        $nominalOptions = [
            100000, 150000, 200000, 250000, 300000,
            350000, 400000, 500000, 600000, 750000,
            1000000, 1250000, 1500000, 2000000, 2500000,
            3000000, 5000000,
        ];

        $rows      = [];
        $grandTotal = 0;

        for ($i = 0; $i < self::TOTAL; $i++) {
            $nominal    = $nominalOptions[array_rand($nominalOptions)];
            $keterangan = $keterangans[array_rand($keterangans)];

            // Waktu acak dalam rentang 1 bulan
            $addSecs   = random_int(0, $totalSecs);
            $createdAt = $startDate->copy()->addSeconds($addSecs)->format('Y-m-d H:i:s');

            $rows[] = [
                'partner_id'  => $partnerId,
                'nominal'     => $nominal,
                'keterangan'  => $keterangan,
                'status'      => 'save',
                'created_at'  => $createdAt,
                'updated_at'  => $createdAt,
            ];

            $grandTotal += $nominal;
        }

        // Urutkan berdasarkan created_at agar rapi di database
        usort($rows, fn($a, $b) => strcmp($a['created_at'], $b['created_at']));

        DB::table('modal')->insert($rows);

        $this->command->info('✅ ' . self::TOTAL . ' modal berhasil di-seed untuk partner ID ' . $partnerId . '.');
        $this->command->info('   Periode  : ' . $startDate->toDateString() . ' → ' . $endDate->toDateString() . ' (' . self::RANGE_DAYS . ' hari)');
        $this->command->info('   Total    : Rp ' . number_format($grandTotal, 0, ',', '.'));
        $this->command->info('   Rata-rata: Rp ' . number_format($grandTotal / self::TOTAL, 0, ',', '.') . ' / transaksi');
    }
}
