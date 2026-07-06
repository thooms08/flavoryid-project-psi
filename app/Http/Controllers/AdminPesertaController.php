<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPesertaController extends Controller
{
    public function index()
    {
        // Fetch all events to show in the list
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('admin.event.kelola-peserta', compact('events'));
    }

    public function detail($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();
        $participants = Participant::where('event_id', $event->id)->orderBy('name', 'asc')->get();

        return view('admin.event.detail-peserta', compact('event', 'participants'));
    }

    public function uploadSertifikat(Request $request, $uuid)
    {
        $participant = Participant::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'certificat' => 'required|image|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

        if ($request->hasFile('certificat')) {
            // Delete old certificate if exists
            if ($participant->certificat) {
                Storage::disk('public')->delete($participant->certificat);
            }

            // Store new certificate in storage/app/public/certificat/...
            $path = $request->file('certificat')->store('certificat', 'public');
            $participant->certificat = $path;
            $participant->save();
        }

        return redirect()->back()->with('success', 'Sertifikat berhasil diunggah.');
    }
}
