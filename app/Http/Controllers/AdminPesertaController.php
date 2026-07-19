<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            'certificat' => 'required|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

        if ($request->hasFile('certificat')) {
            // Hapus sertifikat lama jika ada
            if ($participant->certificat) {
                $oldPath = public_path('assets/sertifikat/' . $participant->certificat);
                if (\Illuminate\Support\Facades\File::exists($oldPath)) {
                    \Illuminate\Support\Facades\File::delete($oldPath);
                }
            }

            // Simpan sertifikat baru ke public/assets/sertifikat/
            $file     = $request->file('certificat');
            $fileName = time() . '_' . \Illuminate\Support\Str::slug($participant->name) . '.' . $file->getClientOriginalExtension();
            $destDir  = public_path('assets/sertifikat');

            if (! \Illuminate\Support\Facades\File::isDirectory($destDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($destDir, 0755, true);
            }

            $file->move($destDir, $fileName);

            $participant->certificat = $fileName;
            $participant->save();
        }

        return redirect()->back()->with('success', 'Sertifikat berhasil diunggah.');
    }
}
