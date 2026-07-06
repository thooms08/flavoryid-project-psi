<?php

namespace App\Exports;

use App\Models\Modal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ModalExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        return Modal::where('partner_id', $this->partnerId)
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate, 'Asia/Jakarta')->startOfDay()->timezone('UTC'),
                Carbon::parse($this->endDate, 'Asia/Jakarta')->endOfDay()->timezone('UTC')
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Nominal (Rp)', 'Keterangan', 'Tanggal Input'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->nominal,
            $row->keterangan ?? '-',
            Carbon::parse($row->created_at)->timezone('Asia/Jakarta')->translatedFormat('H:i, l, d F Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Menebalkan baris header Excel
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        return [];
    }
}