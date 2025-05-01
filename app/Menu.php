<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function produks()
    {
        return $this->hasMany(Produk::class, 'menu_id', 'id');
    }

    public function toppings()
    {
        return $this->hasMany(Produk::class, 'menu_id', 'id');
    }
}
