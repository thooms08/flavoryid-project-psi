<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    /**
     * Halaman daftar semua event yang aktif (public)
     */
    public function index()
    {
        $events = Event::where('status', 'active')
            ->withCount('participants')
            ->orderBy('event_start_date', 'asc')
            ->get();

        return view('event.index', compact('events'));
    }

    /**
     * Halaman detail event + form pendaftaran
     * Menerima slug (baru) atau uuid (fallback link lama)
     */
    public function show(string $identifier)
    {
        // Coba cari by slug dulu, jika tidak ketemu coba UUID (backward-compatible)
        $event = Event::where('status', 'active')
            ->where(function ($q) use ($identifier) {
                $q->where('slug', $identifier)
                  ->orWhere('uuid', $identifier);
            })
            ->with('participants')
            ->firstOrFail();

        // Jika diakses via UUID (link lama), redirect permanen ke slug
        if ($event->slug && $identifier === $event->uuid) {
            return redirect()->route('event.show', $event->slug, 301);
        }

        return view('event.detail-event', compact('event'));
    }

    /**
     * Proses pendaftaran peserta
     * Jika berhasil → redirect ke link grup WhatsApp event
     */
    public function daftar(Request $request, string $identifier)
    {
        $event = Event::where('status', 'active')
            ->where(function ($q) use ($identifier) {
                $q->where('slug', $identifier)
                  ->orWhere('uuid', $identifier);
            })
            ->firstOrFail();

        $request->validate([
            'name'                 => 'required|string|max:255',
            'wa'                   => 'required|string|max:20',
            'participant_category' => 'required|in:umum,pelajar,pengusaha_kuliner,pengusaha_non_kuliner',
        ], [
            'name.required'                 => 'Nama lengkap wajib diisi.',
            'wa.required'                   => 'Nomor WhatsApp wajib diisi.',
            'participant_category.required' => 'Kategori peserta wajib dipilih.',
            'participant_category.in'       => 'Kategori peserta tidak valid.',
        ]);

        // Cek duplikat: nomor WA yang sama di event yang sama
        $sudahDaftar = Participant::where('event_id', $event->id)
            ->where('wa', $request->wa)
            ->exists();

        if ($sudahDaftar) {
            return back()
                ->withInput()
                ->withErrors(['wa' => 'Nomor WhatsApp ini sudah terdaftar di event ini.']);
        }

        Participant::create([
            'event_id'             => $event->id,
            'name'                 => $request->name,
            'wa'                   => $request->wa,
            'participant_category' => $request->participant_category,
        ]);

        // Redirect ke grup WA jika tersedia, fallback ke halaman event
        $redirectUrl = $event->wa_group_link ?: route('event.index');

        return redirect($redirectUrl);
    }
}
