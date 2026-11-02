<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticUrls = [
            // Halaman utama
            ['loc' => '/',                              'changefreq' => 'weekly',  'priority' => '1.0'],
            ['loc' => '/tentang',                       'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/faq',                           'changefreq' => 'monthly', 'priority' => '0.8'],

            // Registrasi & autentikasi
            ['loc' => '/register',                      'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => '/login',                         'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/lupa-password',                 'changefreq' => 'yearly',  'priority' => '0.4'],

            // Panduan kasir
            ['loc' => '/panduan/daftar',                'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/login',                 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/menu',                  'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/form',                  'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/catatan',               'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/pesanan',               'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/riwayat-pesanan',       'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/pesanan-batal',         'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/ikhtisar-bisnis',       'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/laporan-pengeluaran',   'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/modal-usaha',           'changefreq' => 'monthly', 'priority' => '0.7'],

            // Panduan HRMS
            ['loc' => '/panduan/hrms',                  'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/panduan/hrms/akses-dasbor',     'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/panduan/hrms/data-karyawan',    'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/panduan/hrms/shift',            'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/panduan/hrms/absensi',          'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/panduan/hrms/modal-usaha',      'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/panduan/hrms/laporan-bisnis',   'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/panduan/hrms/mineai-manager',   'changefreq' => 'monthly', 'priority' => '0.6'],

            // Legal
            ['loc' => '/kebijakan-privasi',             'changefreq' => 'yearly',  'priority' => '0.5'],
            ['loc' => '/syarat-ketentuan',              'changefreq' => 'yearly',  'priority' => '0.5'],
        ];

        $xml = view('sitemap', compact('staticUrls'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
