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
        return view('index');
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