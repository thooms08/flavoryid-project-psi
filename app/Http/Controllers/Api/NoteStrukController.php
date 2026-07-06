<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteStrukController extends Controller
{
    public function index(Request $request)
    {
        try {
            $partnerId = $request->user()->partner->id;
            // Menampilkan yang terbaru di atas
            $notes = Note::where('partner_id', $partnerId)->latest()->get();
            
            return response()->json([
                'success' => true,
                'data' => $notes
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'note' => 'required|string|max:100',
        ], [
            'note.required' => 'Catatan tidak boleh kosong.',
            'note.max' => 'Maksimal 100 karakter bro!',
        ]);

        try {
            $note = Note::create([
                'partner_id' => $request->user()->partner->id,
                'note' => $request->note,
                'status' => 'nonaktif', // Default sesuai permintaan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan baru berhasil ditambahkan.',
                'data' => $note
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan catatan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:100',
        ]);

        try {
            $note = Note::findOrFail($id);

            if ($note->partner_id != $request->user()->partner->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        
            $note->update([
                'note' => $request->note
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui.',
                'data' => $note
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal update catatan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $note = Note::findOrFail($id);

            if ($note->partner_id != $request->user()->partner->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        
            $note->delete();
        
            return response()->json([
                'success' => true, 
                'message' => 'Catatan berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal hapus catatan: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $partnerId = $request->user()->partner->id;
            $note = Note::findOrFail($id);
        
            if ($note->partner_id != $partnerId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        
            if ($note->status === 'aktif') {
                $note->update(['status' => 'nonaktif']);
                $msg = 'Catatan dinonaktifkan.';
            } else {
                // Nonaktifkan semua catatan lain milik partner ini
                Note::where('partner_id', $partnerId)->update(['status' => 'nonaktif']);
                // Aktifkan yang dipilih
                $note->update(['status' => 'aktif']);
                $msg = 'Catatan berhasil diaktifkan. Catatan lain otomatis dinonaktifkan.';
            }
        
            return response()->json([
                'success' => true, 
                'message' => $msg,
                'data' => $note
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}