<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class AdminPartnerController extends Controller
{
    // --- VIEW METHODS ---

    public function aktif() {
        $partners = Partner::whereHas('user', function($q) {
            $q->where('role', 'kasir');
        })->whereHas('outlet.mediaQr', function($q) {
            $q->where('status', 'aktif');
        })->with('outlet.mediaQr')->get();
        
        return view('admin.partner-aktif', compact('partners'));
    }

    public function jatuhTempo() {
        $partners = Partner::whereHas('user', function($q) {
            $q->where('role', 'kasir');
        })->whereHas('outlet.mediaQr', function($q) {
            $q->where('status', 'jatuh_tempo');
        })->with('outlet.mediaQr')->get();
        
        return view('admin.partner-jatuh-tempo', compact('partners'));
    }

    public function nonaktif() {
        $partners = Partner::whereHas('user', function($q) {
            $q->where('role', 'kasir');
        })->whereHas('outlet.mediaQr', function($q) {
            $q->where('status', 'nonaktif');
        })->with('outlet.mediaQr')->get();
        
        return view('admin.partner-nonaktif', compact('partners'));
    }

    public function tanpaStatus() {
        // Filter kasir dan bungkus logika orWhereHas ke dalam satu scope where()
        $partners = Partner::whereHas('user', function($q) {
            $q->where('role', 'kasir');
        })->where(function($query) {
            $query->whereDoesntHave('outlet.mediaQr')
                  ->orWhereHas('outlet.mediaQr', function($q) {
                      $q->whereNull('status')->orWhere('status', '');
                  });
        })->with('outlet.mediaQr')->get();
            
        return view('admin.partner-tanpa-status', compact('partners'));
    }

    // --- ACTION METHODS ---

    private function updateStatus($uuid, $status) {
        $partner = Partner::where('uuid', $uuid)->firstOrFail();
        
        // Pastikan outlet dan mediaQr ada sebelum update
        if ($partner->outlet && $partner->outlet->mediaQr) {
            // --- PROTEKSI PAKET SUPER (SELAMANYA) ---
            // Paket super tidak boleh dijatuh tempo atau dinonaktifkan oleh admin
            if (strtolower($partner->outlet->mediaQr->paket) === 'super' && in_array($status, ['jatuh_tempo', 'nonaktif'])) {
                return back()->with('error', 'Partner ini menggunakan Paket Super (Selamanya). Status tidak dapat diubah menjadi jatuh tempo atau nonaktif.');
            }

            $partner->outlet->mediaQr->update(['status' => $status]);
        } else {
            return back()->with('error', 'Data Media QR partner belum lengkap.');
        }

        return back()->with('success', "Status berhasil diubah menjadi $status.");
    }

    public function setAktif(Request $request, $uuid)
    { 
        $request->validate([
            'paket' => 'required|in:gratis,eksklusif,super'
        ]);

        $partner = Partner::where('uuid', $uuid)->firstOrFail();
        
        if ($partner->outlet && $partner->outlet->mediaQr) {
            $partner->outlet->mediaQr->update([
                'status' => 'aktif',
                'paket' => $request->paket
            ]);
        } else {
            return back()->with('error', 'Data Media QR partner belum lengkap.');
        }

        return back()->with('success', "Partner berhasil diaktifkan dengan paket " . ucfirst($request->paket) . ".");
    }
    public function setJatuhTempo($uuid) 
    { 
        return $this->updateStatus($uuid, 'jatuh_tempo'); 
    }
    public function setNonaktif($uuid) 
    { 
        return $this->updateStatus($uuid, 'nonaktif'); 
    }

    public function detail($uuid) {
        // Ambil data partner beserta relasinya
        $partner = Partner::where('uuid', $uuid)
            ->with(['outlet.mediaQr', 'user'])
            ->firstOrFail();
            
        return view('admin.detail', compact('partner'));
    }
}