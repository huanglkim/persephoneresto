<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'alamat', 'nohp', 'jabatan', 'gambar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'user_id', 'id');
    }

    public function actuals()
    {
        return $this->hasMany(Actual::class);
    }
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }    
}
