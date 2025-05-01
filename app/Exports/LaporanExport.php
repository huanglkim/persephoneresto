<?php

namespace App\Exports;

use App\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $user_id;
    protected $month;
    protected $year;

    public function __construct($user_id, $month, $year)
    {
        $this->user_id = $user_id;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $query = Pesanan::with('user');

        if (!empty($this->user_id)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', $this->user_id);
            });
        }

        if (!empty($this->month)) {
            $query->whereMonth('created_at', $this->month);
        }

        if (!empty($this->year)) {
            $query->whereYear('created_at', $this->year);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Tanggal Pesanan',
            'Total Harga',
        ];
    }

    public function map($pesanan): array
    {
        return [
            $pesanan->user->name ?? 'N/A',
            $pesanan->created_at,
            $pesanan->total_harga ?? 0,
        ];
    }
}