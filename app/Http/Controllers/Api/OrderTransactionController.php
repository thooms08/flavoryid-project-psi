<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class OrderTransactionController extends Controller
{
    // 1. PROSES TRANSAKSI ONLINE LANGSUNG
    public function processTransaction(Request $request)
    {
        $request->validate([
            'order_id'       => 'required',
            'payment_method' => 'required|in:cash,qris,transfer',
            'uang_bayar'     => 'required|numeric',
            'proof_image'    => 'nullable|file|mimes:jpeg,png,jpg|max:2048', 
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $partnerId = Auth::user()->partner->id;
                $order = Order::where('partner_id', $partnerId)->findOrFail($request->order_id);

                if ($order->status !== 'pending') {
                    throw new \Exception("Pesanan ini sudah diproses sebelumnya.");
                }

                $uangBayar = (float) $request->uang_bayar;
                $totalHarga = (float) $order->total_harga;

                if ($request->payment_method === 'cash' && $uangBayar < $totalHarga) {
                    throw new \Exception("Uang pembayaran kurang dari total tagihan.");
                }

                $imagePath = null;
                
                if (in_array($request->payment_method, ['qris', 'transfer']) && $request->hasFile('proof_image')) {
                    $file = $request->file('proof_image');
                    $fileName = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Disk 'local' root-nya sudah storage/app/private — cukup subfolder langsung
                    $file->storeAs('bukti_bayar', $fileName);
                    
                    $imagePath = $fileName;
                }

                $order->update([
                    'status'           => 'success',
                    'metode_bayar'     => $request->payment_method,
                    'uang_bayar'       => $uangBayar,
                    'kembalian'        => max(0, $uangBayar - $totalHarga),
                    'bukti_bayar'      => $imagePath
                ]);
                
                $bulan = \Carbon\Carbon::parse($order->created_at)->format('Ym');
                $nomorAngka = preg_replace('/[^0-9]/', '', $order->nomor_pesanan) ?: $order->id;
                $order->nomor_pesanan_format = str_pad($nomorAngka, 2, '0', STR_PAD_LEFT);

                return response()->json(['success' => true, 'message' => 'Transaksi Berhasil', 'data' => $order]);
            });
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 2. SINKRONISASI TRANSAKSI OFFLINE
    public function syncOfflineTransactions(Request $request)
    {
        $request->validate(['transactions' => 'required|array']);

        try {
            return DB::transaction(function () use ($request) {
                $partnerId = Auth::user()->partner->id;
                $syncedTxIds = [];
                $syncedOrderIds = []; 

                foreach ($request->transactions as $tx) {
                    $order = Order::where('partner_id', $partnerId)
                                  ->where(function($q) use ($tx) {
                                      $q->where('id', $tx['order_id'])
                                        ->orWhere('offline_id', $tx['order_id']);
                                  })->first();

                    if ($order) {
                        if ($order->status === 'pending') {
                            $imagePath = null;
                            
                            // --- Simpan base64 ke storage/app/private/bukti_bayar ---
                            if (!empty($tx['proof_image_base64'])) {
                                $image_parts = explode(";base64,", $tx['proof_image_base64']);
                                $image_base64 = base64_decode($image_parts[1] ?? $image_parts[0]);
                                
                                $fileName = 'offline_' . time() . '_' . uniqid() . '.jpg';
                                
                                // Disk 'local' root-nya sudah storage/app/private — cukup subfolder langsung
                                \Illuminate\Support\Facades\Storage::put('bukti_bayar/' . $fileName, $image_base64);
                                
                                $imagePath = $fileName;
                            }

                            $order->update([
                                'status'           => 'success',
                                'metode_bayar'     => $tx['payment_method'],
                                'uang_bayar'       => $tx['uang_bayar'],
                                'kembalian'        => $tx['kembalian'],
                                'bukti_bayar'      => $imagePath
                            ]);
                        }
                        $syncedTxIds[] = $tx['offline_tx_id'];
                        $syncedOrderIds[] = $tx['order_id'];
                    }
                }

                return response()->json([
                    'success' => true, 
                    'message' => 'Sinkronisasi Pembayaran Berhasil', 
                    'synced_ids' => $syncedTxIds,
                    'synced_order_ids' => $syncedOrderIds 
                ]);
            });
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}