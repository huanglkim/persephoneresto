<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';
    protected $guarded = ['id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function statistik()
    {
        return $this->hasOne(ProdukStatistik::class);
    }

    public function pesananDetails()
    {
        return $this->hasMany(PesananDetail::class, 'produk_id', 'id');
    }

    
}
 