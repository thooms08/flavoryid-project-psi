<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modal;
use App\Exports\ModalExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class KasirModalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            abort(403, 'Akses ditolak: Akun tidak memiliki relasi Partner.');
        }
        
        // Ambil tanggal hari ini sesuai timezone aplikasi
        $today = Carbon::today()->toDateString();
        
        $startDate = $request->input('start_date', $today);
        $endDate = $request->input('end_date', $today);
        
        // Menangkap filter status (default 'save')
        $status = $request->input('status', 'save');
        
        $modals = Modal::where('partner_id', $user->partner->id)
            ->where('status', $status)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('kasir.modal', compact('modals', 'startDate', 'endDate', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Modal::create([
            'partner_id' => $request->user()->partner->id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'status' => 'save', // Default status disimpan di save
        ]);

        return redirect()->back()->with('success', 'Modal berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $modal = Modal::findOrFail($id);
        // Pindahkan ke tab terhapus (Soft delete simulasi)
        $modal->update(['status' => 'delete']);

        return redirect()->back()->with('success', 'Data modal dipindahkan ke tab Terhapus.');
    }

    public function restore($id)
    {
        $modal = Modal::findOrFail($id);
        // Pulihkan ke tab data tersedia
        $modal->update(['status' => 'save']);

        return redirect()->back()->with('success', 'Data modal berhasil dipulihkan.');
    }

    public function export(Request $request)
    {
        $partnerId = $request->user()->partner->id;
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        $startDate = $request->input('start_date', $today);
        $endDate = $request->input('end_date', $today);
        
        // Format penamaan file sesuai instruksi
        $dateStr = Carbon::parse($startDate)->format('d-m-Y');
        $fileName = "modal_usaha_{$dateStr}.xlsx";

        // Membersihkan buffer untuk mencegah file excel corrupt
        ob_end_clean();
        ob_start();

        return Excel::download(new ModalExport($partnerId, $startDate, $endDate), $fileName);
    }
}