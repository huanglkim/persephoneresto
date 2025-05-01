<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PesananDetailTopping extends Pivot
{
    protected $table = 'pesanan_detail_toppings';
}