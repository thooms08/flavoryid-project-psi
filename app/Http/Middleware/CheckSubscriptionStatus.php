<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Partner, InformasiOutlet, MediaQr};

class CheckSubscriptionStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user sudah login dan perannya adalah kasir
        if (Auth::check() && Auth::user()->role === 'kasir') {
            
            // PENTING: Pengecualian agar tidak terjadi "Infinite Redirect Loop"
            // Biarkan user mengakses halaman langganan dan proses pembayarannya
            if ($request->routeIs('kasir.langganan.*')) {
                return $next($request);
            }

            $user = Auth::user();
            $partner = Partner::where('user_id', $user->id)->first();
            
            if ($partner) {
                $outlet = InformasiOutlet::where('partner_id', $partner->id)->first();
                
                if ($outlet) {
                    $mediaQr = MediaQr::where('informasi_id', $outlet->id)->first();
                    
                    // Jika status nonaktif, tendang kembali ke halaman langganan
                    if ($mediaQr && $mediaQr->status === 'nonaktif') {
                        return redirect()->route('kasir.langganan.index')
                            ->with('error', 'Akses ditolak. Lisensi outlet Anda sedang nonaktif, silakan perpanjang langganan.');
                    }
                }
            }
        }

        return $next($request);
    }
}