<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $guarded = ['id'];

    public function pesanan()
    {
        return $this->belongsTo(Produk::class, 'pesanan_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class)->withDefault(); //withDefault untuk menghindari error jika null
    }
    public function topping()
    {
        return $this->belongsTo(Topping::class)->withDefault();
    }
}
