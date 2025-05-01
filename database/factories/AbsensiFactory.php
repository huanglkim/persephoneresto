<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Absensi;
use Faker\Generator as Faker;

$factory->define(Absensi::class, function (Faker $faker) {
    return [
        'karyawan_id' => $faker->numberBetween(1, 18), // Assign a random ID between 1 and 18
        'jumlah_hari_kerja' => 21,
        'jumlah_hari_sakit' => 0,
        'jumlah_hari_izin' => 0,
        'jumlah_hari_alfa' => 0,
        'jumlah_hari_cuti' => 0,
        'potongan_gaji_pokok' => 0,
    ];
});
