<?php

namespace App\Exports;

use App\Gaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GajiExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Gaji::with('jabatan', 'absensi', 'karyawan')->get();
    }
    public function headings(): array
    {
     return [
        'nama_karyawan',
        'nama_jabatan',
        'jumlah_hari_kerja',
        'gaji_pokok',
        'tunjangan_jabatan',
        'total_gaji',
        'keterangan',
     ];
    }
    public function map($gaji) : array
    {
        static $number = 1;
        return [
            $gaji->karyawan->nama_karyawan ?? 'N/A',
            $gaji->jabatan->nama_jabatan ?? 'N/A',
            $gaji->absensi->jumlah_hari_kerja ?? 'N/A',
            $gaji->jabatan->gaji_pokok ?? 'N/A',
            $gaji->jabatan->tunjangan_jabatan ?? 'N/A',
            $gaji->total_gaji,
            $gaji->keterangan,
        ];
    }
}
