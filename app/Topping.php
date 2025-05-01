<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{   
    protected $table = 'toppings';
    protected $guarded = ['id'];

    public function pesananDetails()
    {
        return $this->belongsToMany(PesananDetail::class, 'pesanan_detail_toppings');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
