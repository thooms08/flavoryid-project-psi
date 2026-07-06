<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirNoteController extends Controller
{
    public function index()
    {
        $partnerId = Auth::user()->partner->id;
        // Menampilkan yang terbaru di atas
        $notes = Note::where('partner_id', $partnerId)->latest()->get();
        
        return view('kasir.note', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'note' => 'required|string|max:100',
        ], [
            'note.required' => 'Catatan tidak boleh kosong.',
            'note.max' => 'Maksimal 100 karakter bro!',
        ]);

        Note::create([
            'partner_id' => Auth::user()->partner->id,
            'note' => $request->note,
            'status' => 'nonaktif', // Default sesuai permintaan
        ]);

        return back()->with('success', 'Catatan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Note $note)
    {
        // Ganti !== menjadi !=
        if ($note->partner_id != Auth::user()->partner->id) {
            abort(403);
        }
    
        $request->validate([
            'note' => 'required|string|max:100',
        ]);
    
        $note->update([
            'note' => $request->note
        ]);
    
        return back()->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroy(Note $note)
    {
        // Ganti !== menjadi !=
        if ($note->partner_id != Auth::user()->partner->id) {
            abort(403);
        }
    
        $note->delete();
    
        return back()->with('success', 'Catatan berhasil dihapus.');
    }

    public function toggleStatus(Note $note)
    {
        $partnerId = Auth::user()->partner->id;
    
        // Ganti !== menjadi !=
        if ($note->partner_id != $partnerId) {
            abort(403);
        }
    
        if ($note->status === 'aktif') {
            $note->update(['status' => 'nonaktif']);
            $msg = 'Catatan dinonaktifkan.';
        } else {
            Note::where('partner_id', $partnerId)->update(['status' => 'nonaktif']);
            $note->update(['status' => 'aktif']);
            $msg = 'Catatan berhasil diaktifkan. Catatan lain otomatis dinonaktifkan.';
        }
    
        return back()->with('success', $msg);
    }
}