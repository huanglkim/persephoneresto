<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
    
    public function gajis()
    {
        return $this->hasMany(Gaji::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}