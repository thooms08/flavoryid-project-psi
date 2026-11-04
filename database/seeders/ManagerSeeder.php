<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * ManagerSeeder
 * ─────────────────────────────────────────────────────────────────
 * Membuat satu akun manager dan menghubungkannya ke akun kasir
 * yang sudah ada (kasir_id = 4) lewat tabel owner_kasir.
 *
 * Seeder ini IDEMPOTENT — aman dijalankan berulang kali.
 * Jika username sudah ada dengan role manager, baris baru tidak
 * akan dibuat. Begitu pula baris owner_kasir-nya.
 *
 * Jalankan:
 *   php artisan db:seed --class=ManagerSeeder
 * ─────────────────────────────────────────────────────────────────
 */
class ManagerSeeder extends Seeder
{
    // ── Konfigurasi akun manager ──────────────────────────────────
    const USERNAME  = 'manager-demo';
    const PASSWORD  = 'password123';
    const ROLE      = 'manager';

    // ── Kasir yang akan dihubungkan ───────────────────────────────
    const KASIR_ID  = 4;

    public function run(): void
    {
        // ── 1. Pastikan kasir_id = 4 benar-benar ada ─────────────
        $kasir = DB::table('users')
            ->where('id', self::KASIR_ID)
            ->where('role', 'kasir')
            ->first();

        if (!$kasir) {
            $this->command->error(
                '❌  User dengan id=' . self::KASIR_ID . ' dan role=kasir tidak ditemukan. ' .
                'Pastikan kasir tersebut sudah ada sebelum menjalankan seeder ini.'
            );
            return;
        }

        // ── 2. Buat akun manager (idempotent) ────────────────────
        $existingManager = DB::table('users')
            ->where('username', self::USERNAME)
            ->where('role', self::ROLE)
            ->first();

        if ($existingManager) {
            $managerId = $existingManager->id;
            $this->command->warn(
                '⚠️  User manager "' . self::USERNAME . '" sudah ada (id=' . $managerId . '), dilewati.'
            );
        } else {
            $managerId = DB::table('users')->insertGetId([
                'username'   => self::USERNAME,
                'password'   => Hash::make(self::PASSWORD),
                'role'       => self::ROLE,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅  Akun manager dibuat (id=' . $managerId . ').');
        }

        // ── 3. Isi tabel owner_kasir (idempotent) ─────────────────
        // Kolom owner_id diisi dengan id manager (role manager juga
        // bisa menjadi "pemilik" outlet, sesuai logika aplikasi).
        $alreadyLinked = DB::table('owner_kasir')
            ->where('owner_id', $managerId)
            ->where('kasir_id', self::KASIR_ID)
            ->exists();

        // Cek juga: kasir_id harus unik di tabel ini
        $kasirAlreadyTaken = DB::table('owner_kasir')
            ->where('kasir_id', self::KASIR_ID)
            ->first();

        if ($alreadyLinked) {
            $this->command->warn(
                '⚠️  Relasi owner_kasir (owner_id=' . $managerId .
                ', kasir_id=' . self::KASIR_ID . ') sudah ada, dilewati.'
            );
        } elseif ($kasirAlreadyTaken) {
            $this->command->error(
                '❌  Kasir id=' . self::KASIR_ID . ' sudah terhubung ke owner_id=' .
                $kasirAlreadyTaken->owner_id . '. ' .
                'Satu kasir hanya boleh dimiliki oleh satu owner/manager.'
            );
        } else {
            DB::table('owner_kasir')->insert([
                'owner_id'   => $managerId,
                'kasir_id'   => self::KASIR_ID,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info(
                '✅  Relasi owner_kasir dibuat ' .
                '(owner_id=' . $managerId . ', kasir_id=' . self::KASIR_ID . ').'
            );
        }

        $this->command->newLine();
        $this->command->line('┌──────────────────────────────────────────┐');
        $this->command->line('│  Kredensial akun manager                 │');
        $this->command->line('│  Username : ' . str_pad(self::USERNAME, 29) . '│');
        $this->command->line('│  Password : ' . str_pad(self::PASSWORD, 29) . '│');
        $this->command->line('│  Role     : ' . str_pad(self::ROLE, 29) . '│');
        $this->command->line('│  Kasir ID : ' . str_pad((string)self::KASIR_ID, 29) . '│');
        $this->command->line('└──────────────────────────────────────────┘');
    }
}
