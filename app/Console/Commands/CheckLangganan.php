<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MediaQr;
use App\Models\InformasiOutlet;
use App\Models\Subscription;
use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceTagihan;

class CheckLangganan extends Command
{
    protected $signature = 'langganan:check';
    protected $description = 'Cek status jatuh tempo dan nonaktif langganan kasir otomatis';

    public function handle()
    {
        $this->info('Mulai mengecek status langganan...');

        $outlets = MediaQr::whereIn('status', ['aktif', 'jatuh_tempo'])->get();

        foreach ($outlets as $qr) {
            $infoOutlet = InformasiOutlet::find($qr->informasi_id);
            if (!$infoOutlet) continue;

            // Ambil data user terkait untuk pengiriman email
            $partner = Partner::with('user')->find($infoOutlet->partner_id);
            $user = $partner ? $partner->user : null;

            $latestSub = Subscription::where('partner_id', $infoOutlet->partner_id)
                            ->where('status', 'success')
                            ->latest('updated_at')
                            ->first();

            $tanggalMulai = $latestSub ? $latestSub->updated_at : $qr->created_at;
            $hariBerlalu = Carbon::parse($tanggalMulai)->diffInDays(now());
            $paket = strtolower($qr->paket);

            // --- PERUBAHAN HARGA DI SINI ---
            $tagihan = 70000; // Default untuk bulanan/eksklusif
            if ($paket === 'super') {
                $tagihan = 880000; // Paket tahunan dipertahankan
            } elseif ($paket === 'eksklusif') {
                $tagihan = 70000; // Bulanan jadi 70.000
            }

            // Fungsi Helper untuk kirim email
            $kirimEmail = function($statusBaru) use ($user, $infoOutlet, $paket, $tagihan) {
                if ($user && $user->email) {
                    $dataEmail = [
                        'nama_user' => $user->name ?? 'Pengguna',
                        'nama_outlet' => $infoOutlet->nama_outlet,
                        'paket' => $paket === 'gratis' ? 'Eksklusif (Upgrade)' : $paket,
                        'tagihan' => $tagihan,
                        'status' => $statusBaru
                    ];
                    
                    try {
                        Mail::to($user->email)->send(new InvoiceTagihan($dataEmail));
                        $this->info("Email tagihan ($statusBaru) terkirim ke: {$user->email}");
                    } catch (\Exception $e) {
                        $this->error("Gagal kirim email ke {$user->email}: " . $e->getMessage());
                    }
                }
            };

            // --- LOGIKA PAKET GRATIS (TRIAL 30 HARI) ---
            if ($paket === 'gratis') {
                // Hari ke-31 ke atas: Nonaktif
                if ($hariBerlalu >= 31 && $qr->status !== 'nonaktif') {
                    $qr->update(['status' => 'nonaktif']);
                    $this->info("Outlet ID {$infoOutlet->id} -> NONAKTIF");
                    $kirimEmail('nonaktif');
                } 
                // Hari ke-29 dan 30: Jatuh Tempo (Hari ke 1-28 artinya tetap aktif)
                elseif ($hariBerlalu >= 29 && $hariBerlalu < 31 && $qr->status !== 'jatuh_tempo') {
                    $qr->update(['status' => 'jatuh_tempo']);
                    $this->info("Outlet ID {$infoOutlet->id} -> JATUH TEMPO");
                    $kirimEmail('jatuh_tempo');
                }
            }

            // --- LOGIKA PAKET EKSKLUSIF (Bulanan) ---
            if ($paket === 'eksklusif') {
                if ($hariBerlalu >= 32 && $qr->status !== 'nonaktif') {
                    $qr->update(['status' => 'nonaktif']);
                    $this->info("Outlet ID {$infoOutlet->id} -> NONAKTIF");
                    $kirimEmail('nonaktif');
                } 
                elseif ($hariBerlalu >= 28 && $hariBerlalu < 32 && $qr->status !== 'jatuh_tempo') {
                    $qr->update(['status' => 'jatuh_tempo']);
                    $this->info("Outlet ID {$infoOutlet->id} -> JATUH TEMPO");
                    $kirimEmail('jatuh_tempo');
                }
            }

            // --- LOGIKA PAKET SUPER (Selamanya/Lifetime) ---
            // Paket super adalah bayar sekali untuk selamanya, tidak pernah jatuh tempo atau nonaktif.
            if ($paket === 'super') {
                $this->info("Outlet ID {$infoOutlet->id} paket SUPER (selamanya) - dilewati, tidak ada expiry.");
                continue;
            }
        }

        $this->info('Selesai! Semua status langganan telah diperbarui.');
    }
}