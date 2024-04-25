<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_units', function (Blueprint $table) {
            $table->id("id");
            $table->integer("unit_kerja_id");
            $table->string("nama_anggota");
            $table->string("user_email");
            $table->string("user_role");
            $table->integer("jumlah_barang");
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
        Schema::dropIfExists('anggota_units');
    }
}
