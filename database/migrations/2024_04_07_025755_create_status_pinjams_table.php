<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusPinjamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_pinjams', function (Blueprint $table) {
            $table->id("id");
            $table->integer("inventory_id");
            $table->integer("anggota_unit_id");
            $table->integer("jumlah_pinjam");
            $table->dateTime("tanggal_pinjam");
            $table->integer("posisi");
            $table->string("status");
            $table->longText("ttd_ketua")->nullable();
            $table->longText("ttd_admin")->nullable();
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
        Schema::dropIfExists('status_pinjams');
    }
}
