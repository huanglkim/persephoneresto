<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $table = 'gaji';
    protected $guarded = ['id'];

    // Relasi dengan model Absensi
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }
    public function getTotalGaji()
    {
        return $this->jabatan->gaji_pokok + $this->jabatan->tunjangan_jabatan;
    }
}
