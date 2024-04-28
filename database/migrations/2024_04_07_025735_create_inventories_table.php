<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id("id");
            $table->string("gambar_barang")->nullable();
            $table->string("kode_barang");
            $table->string("nama_barang");
            $table->string("deskripsi_barang");
            $table->string("satuan");
            $table->integer("stok_awal");
            $table->integer("barang_keluar");
            $table->integer("stok_akhir");
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
        Schema::dropIfExists('inventories');
    }
}
