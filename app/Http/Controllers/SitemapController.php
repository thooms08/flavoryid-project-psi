<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticUrls = [
            ['loc' => '/',                       'changefreq' => 'weekly',  'priority' => '1.0'],
            ['loc' => '/tentang',                'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/faq',                    'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/kebijakan-privasi',      'changefreq' => 'yearly',  'priority' => '0.5'],
            ['loc' => '/syarat-ketentuan',       'changefreq' => 'yearly',  'priority' => '0.5'],
            ['loc' => '/register',               'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => '/login',                  'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/lupa-password',          'changefreq' => 'yearly',  'priority' => '0.4'],
            ['loc' => '/event',                  'changefreq' => 'weekly',  'priority' => '0.8'],
            ['loc' => '/panduan/daftar',         'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/login',          'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/menu',           'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/form',           'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/catatan',        'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/pesanan',        'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/riwayat-pesanan','changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/pesanan-batal',  'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/laporan-bisnis', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/performa-bisnis','changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/mineai',         'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        // Ambil semua event yang aktif
        $events = Event::where('status', 'active')
            ->select('slug', 'updated_at')
            ->orderBy('event_start_date', 'desc')
            ->get();

        $xml = view('sitemap', compact('staticUrls', 'events'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
