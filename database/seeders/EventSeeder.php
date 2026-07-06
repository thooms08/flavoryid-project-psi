<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            // 1. Sudah selesai
            [
                'event_name'       => 'Workshop Digitalisasi Kasir untuk UMKM Kuliner',
                'description'      => "Workshop intensif satu hari yang membahas cara digitalisasi kasir untuk usaha kuliner skala kecil dan menengah.\n\nMateri yang akan dibahas:\n- Kenapa kasir manual sudah tidak relevan\n- Demo langsung Flavory.id: dari buka shift hingga laporan harian\n- Tips migrasi data dari catatan manual ke sistem digital\n- Sesi tanya jawab & konsultasi gratis\n\nCocok untuk: pemilik warung makan, kafe, restoran, dan usaha kuliner lainnya yang ingin naik level.",
                'image'            => 'event/event-preview.jpeg',
                'url'              => 'workshop-digitalisasi-kasir-umkm-kuliner',
                'event_start_date' => now()->subDays(40)->format('Y-m-d'),
                'event_end_date'   => now()->subDays(39)->format('Y-m-d'),
                'status'           => 'active',
                'wa_group_link'    => null,
            ],

            // 2. Sudah selesai
            [
                'event_name'       => 'Seminar Manajemen Stok & Pengendalian Bahan Baku Restoran',
                'description'      => "Seminar online via Zoom yang membahas strategi pengendalian stok bahan baku agar tidak boros dan selalu tersedia.\n\nTopik utama:\n- Metode FIFO & FEFO dalam pengelolaan bahan baku\n- Cara menghitung HPP (Harga Pokok Penjualan) secara akurat\n- Fitur manajemen stok Flavory.id secara live\n- Studi kasus: restoran yang berhasil menekan food waste hingga 30%\n\nPembicara: Praktisi F&B dengan pengalaman 10+ tahun mengelola jaringan restoran.",
                'image'            => 'event/analisis.png',
                'url'              => 'seminar-manajemen-stok-pengendalian-bahan-baku',
                'event_start_date' => now()->subDays(15)->format('Y-m-d'),
                'event_end_date'   => now()->subDays(15)->format('Y-m-d'),
                'status'           => 'active',
                'wa_group_link'    => null,
            ],

            // 3. Sedang berlangsung
            [
                'event_name'       => 'Bootcamp Analisis Performa Bisnis Kuliner dengan Data',
                'description'      => "Program bootcamp 3 hari yang mengajarkan cara membaca dan menganalisis data penjualan untuk mengambil keputusan bisnis yang lebih cerdas.\n\nHari 1 — Dasar Analisis Data:\n- Mengenal laporan harian, mingguan, dan bulanan\n- Cara membaca grafik tren penjualan\n\nHari 2 — Praktik dengan Flavory.id:\n- Demo fitur Performa & Laporan Bisnis secara langsung\n- Latihan bersama: analisis data peserta sendiri\n\nHari 3 — Strategi Bisnis:\n- Menggunakan data untuk menentukan menu unggulan\n- Strategi promosi berbasis data penjualan\n- Sesi mentoring 1-on-1\n\nKuota terbatas: 50 peserta.",
                'image'            => 'event/stok.png',
                'url'              => 'bootcamp-analisis-performa-bisnis-kuliner',
                'event_start_date' => now()->subDays(1)->format('Y-m-d'),
                'event_end_date'   => now()->addDays(1)->format('Y-m-d'),
                'status'           => 'active',
                'wa_group_link'    => 'https://chat.whatsapp.com/example',
            ],

            // 4. Akan datang (segera hadir)
            [
                'event_name'       => 'Pelatihan Membuat Menu Digital & QR Order untuk Kafe',
                'description'      => "Pelatihan praktis membuat menu digital berbasis QR Code yang bisa langsung dipakai di kafe atau restoran Anda.\n\nApa yang akan Anda pelajari:\n- Setup halaman menu digital di Flavory.id\n- Cara mengatur kategori, foto, harga, dan deskripsi menu\n- Membuat QR Code untuk order langsung dari meja pelanggan\n- Tips fotografi menu dengan smartphone\n- Strategi harga dan penampilan menu yang menarik\n\nSetelah pelatihan, peserta langsung bisa mengoperasikan menu digital untuk usahanya sendiri.\n\nFasilitas: Materi PDF, akses grup alumni, sertifikat digital.",
                'image'            => 'event/order.png',
                'url'              => 'pelatihan-menu-digital-qr-order-kafe',
                'event_start_date' => now()->addDays(7)->format('Y-m-d'),
                'event_end_date'   => now()->addDays(7)->format('Y-m-d'),
                'status'           => 'active',
                'wa_group_link'    => null,
            ],

            // 5. Akan datang (lebih jauh)
            [
                'event_name'       => 'Conference Inovasi Teknologi F&B Indonesia 2026',
                'description'      => "Event tahunan terbesar Flavory.id yang mempertemukan ratusan pelaku usaha kuliner, teknolog, dan investor di satu panggung.\n\nAgenda:\n- Keynote: Masa Depan Teknologi POS di Indonesia\n- Panel diskusi: AI dalam bisnis kuliner — peluang dan tantangan\n- Demo produk terbaru Flavory.id\n- Networking session dengan sesama pemilik usaha kuliner\n- Award: Penghargaan UMKM Kuliner Digital Terbaik 2026\n- Pameran mitra teknologi F&B\n\nPembicara terkonfirmasi dari perusahaan teknologi terkemuka, asosiasi kuliner nasional, dan praktisi bisnis F&B.\n\nTiket Early Bird tersedia. Daftarkan diri sekarang sebelum harga naik!",
                'image'            => 'event/hero.png',
                'url'              => 'conference-inovasi-teknologi-fb-indonesia-2026',
                'event_start_date' => now()->addDays(30)->format('Y-m-d'),
                'event_end_date'   => now()->addDays(31)->format('Y-m-d'),
                'status'           => 'active',
                'wa_group_link'    => null,
            ],
        ];

        foreach ($events as $data) {
            // Lewati jika slug sudah ada (idempotent)
            if (Event::where('slug', $data['url'])->exists()) {
                continue;
            }

            // Pastikan slug unik (jika nama event duplikat)
            $slug = Event::generateUniqueSlug($data['event_name']);

            Event::create(array_merge($data, ['slug' => $slug]));
        }
    }
}
