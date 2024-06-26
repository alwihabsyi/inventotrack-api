<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_barangs', function (Blueprint $table) {
            $table->id("id");
            $table->integer('anggota_unit_id');
            $table->integer("inventory_id");
            $table->integer("status_pinjams_id");
            $table->string("bukti_ambil");
            $table->dateTime("tanggal_ambil");
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
        Schema::dropIfExists('history_barangs');
    }
}
