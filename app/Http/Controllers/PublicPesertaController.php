<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class PublicPesertaController extends Controller
{
    /**
     * Halaman daftar peserta + sertifikat (publik)
     * Hanya bisa diakses jika event sudah selesai (tanggal akhir sudah lewat)
     */
    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)
            ->orWhere('uuid', $slug)
            ->firstOrFail();

        // Halaman ini hanya tersedia jika event sudah selesai
        if (! now()->gt($event->event_end_date)) {
            abort(403, 'Halaman sertifikat belum tersedia. Event belum selesai.');
        }

        $participants = Participant::where('event_id', $event->id)
            ->orderBy('name', 'asc')
            ->get();

        return view('event.peserta', compact('event', 'participants'));
    }

    /**
     * Endpoint AJAX pencarian peserta berdasarkan nama atau nomor WA
     * GET /event/{slug}/peserta/search?q=...
     */
    public function search(Request $request, string $slug)
    {
        $event = Event::where('slug', $slug)
            ->orWhere('uuid', $slug)
            ->firstOrFail();

        if (! now()->gt($event->event_end_date)) {
            return response()->json(['participants' => []]);
        }

        $query = trim($request->get('q', ''));

        $participants = Participant::where('event_id', $event->id)
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('name', 'like', '%' . $query . '%')
                        ->orWhere('wa',   'like', '%' . $query . '%');
                });
            })
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'wa', 'certificat']);

        // Format response — beri URL lengkap untuk sertifikat
        $data = $participants->map(function ($p, $index) {
            return [
                'no'          => $index + 1,
                'name'        => $p->name,
                'wa'          => $p->wa,
                'certificat'  => $p->certificat,
                'certificat_url' => $p->certificat
                    ? asset('assets/sertifikat/' . $p->certificat)
                    : null,
            ];
        });

        return response()->json(['participants' => $data]);
    }
}
