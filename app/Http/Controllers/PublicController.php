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

        return view('index', compact('blogPosts'));
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