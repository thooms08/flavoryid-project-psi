<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $partnerId;
    protected $startDate;
    protected $endDate;
    protected $mode;

    public function __construct($partnerId, $startDate, $endDate, $mode)
    {
        $this->partnerId = $partnerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->mode = $mode;
    }

    public function collection()
    {
        // Jika mode sederhana, ambil order details secara terpisah lebih mudah
        if ($this->mode === 'sederhana') {
            return OrderDetail::with(['menu', 'order'])
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->where('orders.partner_id', $this->partnerId)
                ->where('orders.status', 'success')
                ->whereBetween('orders.created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
                ->orderBy('orders.created_at', 'desc')
                ->select('order_details.*')
                ->get();
        }

        // Jika mode lengkap, ambil order
        return Order::with(['orderDetails.menu'])
            ->where('partner_id', $this->partnerId)
            ->where('status', 'success')
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        if ($this->mode === 'sederhana') {
            return ['Nama Menu', 'Qty', 'Tanggal'];
        }

        return ['No Pesanan', 'Nama Pelanggan', 'Pesanan (Menu - Qty)', 'Total', 'Metode Pembayaran', 'Tanggal'];
    }

    public function map($row): array
    {
        if ($this->mode === 'sederhana') {
            $variant = $row->variant ? ' (' . $row->variant . ')' : '';
            return [
                $row->menu->nama_menu . $variant,
                $row->qty,
                Carbon::parse($row->order->created_at)->translatedFormat('l, d F Y')
            ];
        }

        // Mode Lengkap
        $pesanan = [];
        foreach ($row->orderDetails as $detail) {
            $variant = $detail->variant ? ' (' . $detail->variant . ')' : '';
            $pesanan[] = $detail->menu->nama_menu . $variant . ' x' . $detail->qty;
        }

        return [
            $row->nomor_pesanan,
            $row->nama_pelanggan ?? 'Walk-in',
            implode(",\n", $pesanan), // Agar tidak bertumpuk
            $row->total_harga,
            $row->metode_bayar,
            Carbon::parse($row->created_at)->translatedFormat('l, d F Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        if ($this->mode === 'lengkap') {
            $sheet->getStyle('C')->getAlignment()->setWrapText(true); // Wrap text untuk list pesanan
        }
        return [];
    }
}