<?php

namespace App\Imports;

use App\Laporan;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Untuk konversi tanggal Excel

class LaporanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan nama kolom tidak bermasalah dengan huruf besar/kecil
        $row = array_change_key_case($row, CASE_LOWER);

        $user = User::where('name', $row['name'])->first();
        if (!$user) {
            Log::warning("User '{$row['name']}' tidak ditemukan. Baris dilewati.");
            return null;
        }
        $tanggal = is_numeric($row['tanggal']) ? Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d') : date('Y-m-d', strtotime($row['tanggal']));

        // Aturan validasi
        $rules = [
            'name' => 'required|string', // Ganti dari 'nama karyawan' ke 'name'
            'tanggal' => 'required',
            'total_penjualan' => 'required|numeric',
            'poin' => 'required|integer',
            'total_pendapatan' => 'required|numeric',
        ];

        // Validasi data
        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            Log::error('Gagal Import Laporan: ' . json_encode($validator->errors()->all()));
            return null;
        }

        return new Laporan([
            'user_id' => $user->id,
            'tanggal' => $tanggal,
            'total_penjualan' => $row['total_penjualan'],
            'poin' => $row['poin'],
            'total_pendapatan' => $row['total_pendapatan'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
