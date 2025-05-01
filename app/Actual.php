<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actual extends Model
{
    protected $table = 'actual';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
