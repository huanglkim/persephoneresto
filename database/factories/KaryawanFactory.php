<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Karyawan;
use Faker\Generator as Faker;

$factory->define(Karyawan::class, function (Faker $faker) {
    return [
        'nama_karyawan' => $faker->firstName('female'),
        'tanggal_masuk' => '2024-12-01',
        'divisi_id' => 7,
        'jabatan_id' => 7
    ];
});
