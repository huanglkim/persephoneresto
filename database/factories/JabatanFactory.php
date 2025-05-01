<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Jabatan;
use Faker\Generator as Faker;

$factory->define(Jabatan::class, function (Faker $faker) {
    return [
        'nama_jabatan' => $faker->firstName('female'),
        'gaji_pokok' => 2000000,
        'tunjangan_jabatan' => $faker->numberBetween(200000, 150000)
    ];
});
