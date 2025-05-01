<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $guarded = ['id'];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    
    public function gajis()
    {
        return $this->hasMany(Gaji::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
