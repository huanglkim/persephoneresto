<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdukStatistik extends Model
{
    protected $table = 'produk_statistiks';
    protected $guarded = ['id'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
