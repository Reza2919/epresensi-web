<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIdSatkerJobGenerateLaporanPresensi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_generate_laporan_presensi', function (Blueprint $table) {
            $table->string('id_satker')->nullable();
            $table->string('id_pegawai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_generate_laporan_presensi', function (Blueprint $table) {
            $table->dropColumn('id_satker');
            $table->dropColumn('id_pegawai');
        });
    }
}
