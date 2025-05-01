<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function actuals()
    {
        return $this->hasMany(Actual::class);
    }
}
