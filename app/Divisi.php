<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
