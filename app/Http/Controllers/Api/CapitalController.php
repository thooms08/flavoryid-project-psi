<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modal;
use App\Exports\ModalExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class CapitalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $partnerId = $user->partner->id;
        $status = $request->query('status', 'save');
        $startDate = $request->query('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate = $request->query('end_date', Carbon::today('Asia/Jakarta')->toDateString());

        try {
            $modals = Modal::where('partner_id', $partnerId)
                ->where('status', $status)
                ->whereBetween('created_at', [
                    Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay(),
                    Carbon::parse($endDate, 'Asia/Jakarta')->endOfDay()
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $modals
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            Modal::create([
                'partner_id' => $request->user()->partner->id,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status' => 'save',
            ]);

            return response()->json(['success' => true, 'message' => 'Modal berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $modal = Modal::where('partner_id', $request->user()->partner->id)->findOrFail($id);
            $status = $request->input('status'); // 'delete' atau 'save'
            $modal->update(['status' => $status]);
            
            return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status'], 500);
        }
    }

    public function export(Request $request)
    {
        $partnerId = $request->user()->partner->id;
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        
        $dateStr = Carbon::parse($startDate)->format('d-m-Y');
        $fileName = "modal_usaha_{$dateStr}.xlsx";

        ob_end_clean();
        ob_start();

        return Excel::download(new ModalExport($partnerId, $startDate, $endDate), $fileName);
    }
}