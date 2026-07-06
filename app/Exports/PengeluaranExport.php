<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PengeluaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $partnerId;
    protected $startDate;
    protected $endDate;

    public function __construct($partnerId, $startDate, $endDate)
    {
        $this->partnerId = $partnerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Pengeluaran::where('partner_id', $this->partnerId)
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate, 'Asia/Jakarta')->startOfDay()->timezone('UTC'), 
                Carbon::parse($this->endDate, 'Asia/Jakarta')->endOfDay()->timezone('UTC')
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Kategori', 'Nama Item', 'Harga (Rp)', 'Qty', 'Total (Rp)', 'Keterangan', 'Tanggal'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            ucwords($row->kategori),
            $row->item,
            $row->harga,
            $row->qty,
            $row->total,
            $row->keterangan ?? '-',
            Carbon::parse($row->created_at)->timezone('Asia/Jakarta')->translatedFormat('H:i, l, d F Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        return [];
    }
}