<?php

namespace App\Exports;

use App\Models\orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $type;
    protected $date;
    protected $month;
    protected $year;

    public function __construct($type = 'daily', $date = null, $month = null, $year = null)
    {
        $this->type = $type;
        $this->date = $date ?? Carbon::today()->format('Y-m-d');
        $this->month = $month ?? Carbon::now()->format('Y-m');
        $this->year = $year ?? Carbon::now()->format('Y');
    }

    public function collection()
    {
        $query = orders::with('orderItems')
            ->where('status', 'completed');

        if ($this->type === 'daily') {
            $query->whereDate('updated_at', $this->date);
        } elseif ($this->type === 'monthly') {
            $query->whereYear('updated_at', Carbon::parse($this->month)->year)
                  ->whereMonth('updated_at', Carbon::parse($this->month)->month);
        } elseif ($this->type === 'yearly') {
            $query->whereYear('updated_at', $this->year);
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Waktu Pesan',
            'Nama Pemesan',
            'Nomor Meja',
            'Ruangan',
            'Pesanan',
            'Jumlah Item',
            'Total Harga',
            'Metode Pembayaran',
            'Status'
        ];
    }

    public function map($order): array
    {
        static $no = 0;
        $no++;
        
        $items = $order->orderItems->pluck('menu_name')->implode(', ');
        $totalItems = $order->orderItems->sum('quantity');

        return [
            $no,
            Carbon::parse($order->created_at)->format('d/m/Y H:i'),
            $order->customer_name,
            $order->table_number,
            $order->room,
            $items,
            $totalItems,
            'Rp' . number_format($order->total_price, 0, ',', '.'),
            strtoupper($order->payment_method),
            'Selesai'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFA500']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
        ];
    }

    public function title(): string
    {
        if ($this->type === 'daily') {
            return 'Laporan Harian ' . Carbon::parse($this->date)->format('d-m-Y');
        } elseif ($this->type === 'monthly') {
            return 'Laporan Bulanan ' . Carbon::parse($this->month)->format('F Y');
        } else {
            return 'Laporan Tahunan ' . $this->year;
        }
    }
}
