<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Laporan;
use Faker\Generator as Faker;

$factory->define(Laporan::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 4),
        // Gunakan format yang benar untuk Faker date
        'tanggal' => $faker->dateTimeBetween('2024-01-01', '2024-12-31')->format('Y-m-d'),
        'total_penjualan' => $faker->numberBetween(15, 50),
        'total_pendapatan' => $faker->numberBetween(1, 100) * 20000,
        'poin' => $faker->numberBetween(100, 1000),
    ];
});
