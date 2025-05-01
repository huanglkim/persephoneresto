<?php

namespace App\Imports;

use App\Karyawan;
use App\Divisi;
use App\Jabatan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KaryawanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan nama kolom tidak bermasalah dengan huruf besar/kecil
        $row = array_change_key_case($row, CASE_LOWER);

        // Validasi lebih awal sebelum cek divisi & jabatan
        $rules = [
            'nama_karyawan' => 'required|string',
            'tanggal_masuk' => 'required',
            'nama' => 'required|string',
            'nama_jabatan' => 'required|string',
        ];

        $validator = Validator::make($row, $rules);
        if ($validator->fails()) {
            Log::error('Gagal Import Laporan :' . json_encode($validator->errors()->all()));
            return null;
        }

        // Cari Divisi dan Jabatan berdasarkan nama
        $divisi = Divisi::where('nama', $row['nama'])->first();
        if (!$divisi) {
            Log::warning("Divisi '{$row['nama']}' tidak ditemukan. Baris dilewati.");
            return null;
        }

        $jabatan = Jabatan::where('nama_jabatan', $row['nama_jabatan'])->first();
        if (!$jabatan) {
            Log::warning("Jabatan '{$row['nama_jabatan']}' tidak ditemukan. Baris dilewati.");
            return null;
        }

        // Konversi tanggal masuk
        $tanggal_masuk = isset($row['tanggal_masuk']) && is_numeric($row['tanggal_masuk'])
            ? Date::excelToDateTimeObject($row['tanggal_masuk'])->format('Y-m-d')
            : (strtotime($row['tanggal_masuk']) ? date('Y-m-d', strtotime($row['tanggal_masuk'])) : null);

        if (!$tanggal_masuk) {
            Log::warning("Format tanggal salah pada data: " . json_encode($row));
            return null;
        }

        // Simpan data karyawan
        return new Karyawan([
            'nama_karyawan' => $row['nama_karyawan'],
            'tanggal_masuk' => $tanggal_masuk,
            'divisi_id' => $divisi->id,
            'jabatan_id' => $jabatan->id,
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
