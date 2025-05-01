<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function divisi()
    {
        return $this->belongsTo(divisi::class);
    }
    public function gajis()
    {
        return $this->hasMany(Gaji::class);
    }
}
