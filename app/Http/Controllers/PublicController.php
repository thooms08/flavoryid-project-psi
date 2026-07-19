<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman Kebijakan Privasi
     */
    public function kebijakanPrivasi()
    {
        return view('kebijakan-privasi');
    }

    public function tentang()
    {
        return view('tentang');
    }
    
    public function index()
    {
        $blogPosts = \Illuminate\Support\Facades\Cache::remember('blog_posts_home', 3600, function () {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)
                    ->get('https://blog.flavory.id/api/posts', ['limit' => 3]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                // Blog tidak bisa diakses — tampilkan halaman tanpa section blog
            }
            return [];
        });

        // Popup event: ambil 1 event aktif yang AKAN DATANG (belum mulai),
        // prioritas event dengan tanggal mulai paling dekat.
        // Syarat: status=active, event_start_date > sekarang (belum mulai sama sekali)
        $popupEvent = \App\Models\Event::where('status', 'active')
            ->whereDate('event_start_date', '>', now()->toDateString())
            ->orderBy('event_start_date', 'asc')
            ->first();

        return view('index', compact('blogPosts', 'popupEvent'));
    }
    
    public function faq()
    {
        return view('faq');
    }
    
    public function SyaratKetentuan()
    {
        return view('syarat-ketentuan');
    }
}