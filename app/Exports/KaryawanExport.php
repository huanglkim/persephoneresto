<?php

namespace App\Exports;

use App\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Karyawan::with('jabatan', 'divisi', 'user')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Karyawan',
            'Email',
            'Tanggal Masuk',
            'Divisi',
            'Jabatan',
        ];
    }

    public function map($karyawan): array
    {
        static $number = 1; // Ini digunakan untuk nomor urut
        return [
            $number++, // Menambahkan nomor urut
            $karyawan->nama_karyawan,
            optional($karyawan->user)->email ?? 'N/A', // Menggunakan optional untuk mencegah error jika relasi tidak ada
            $karyawan->tanggal_masuk,
            optional($karyawan->divisi)->nama ?? 'N/A', // Menggunakan optional untuk mencegah error jika relasi tidak ada
            optional($karyawan->jabatan)->nama_jabatan ?? 'N/A', // Menggunakan optional untuk mencegah error jika relasi tidak ada
        ];
    }
}
