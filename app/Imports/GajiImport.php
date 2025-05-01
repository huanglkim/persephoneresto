<?php

namespace App\Imports;

use App\Gaji;
use App\Karyawan;
use App\Absensi;
use App\Jabatan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GajiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER);

        $rules = [
            'nama_karyawan' => 'required|string',
            'nama_jabatan' => 'required|string',
            'jumlah_hari_kerja' => 'required',
            'gaji_pokok' => 'required',
            'tunjangan_jabatan' => 'required',
            'total_gaji' => 'required',
        ];

        $validator = Validator::make($row, $rules);
        if ($validator->fails()) {
            Log::error('Gagal Import Laporan :' . json_encode($validator->errors()->all()));
            return null;
        }

        $karyawan = Karyawan::where('nama_karyawan', $row['nama_karyawan'])->first();
        if (!$karyawan) {
            Log::warning("Karyawan '{$row['nama_karyawan']}' tidak ditemukan. Baris dilewati.");
            return null;
        }

        $jabatan = Jabatan::where('nama_jabatan', $row['nama_jabatan'])->first();
        if (!$jabatan) {
            Log::warning("Jabatan '{$row['nama_jabatan']}' tidak ditemukan. Baris dilewati.");
            return null;
        }
        $absensi = Absensi::where('jumlah_hari_kerja', $row['jumlah_hari_kerja'])->first();
        if (!$absensi) {
            Log::warning("Absensi '{$row['jumlah_hari_kerja']}' tidak ditemukan. Baris dilewati.");
            return null;
        }
        return new Gaji([
            'karyawan_id' => $karyawan->id,
            'absensi_id' => $absensi->id,
            'jabatan_id' => $jabatan->id,
            'total_gaji' => $row['total_gaji'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
