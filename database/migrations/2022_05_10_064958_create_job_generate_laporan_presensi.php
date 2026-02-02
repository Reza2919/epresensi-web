<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobGenerateLaporanPresensi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_generate_laporan_presensi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('satker');
            $table->string('jenis_laporan');
            $table->string('bulan');
            $table->string('tahun');
            $table->string('status');
            $table->string('path')->nullable();
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
        Schema::dropIfExists('job_generate_laporan_presensi');
    }
}
