<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->integer('jabatan_id');
            $table->integer('divisi_id');
            $table->string('jumlah_hari_kerja');
            $table->string('jumlah_hari_sakit');
            $table->string('jumlah_hari_izin');
            $table->string('jumlah_hari_alfa');
            $table->string('jumlah_hari_cuti');
            $table->string('potongan_gaji_pokok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi');
    }
}
