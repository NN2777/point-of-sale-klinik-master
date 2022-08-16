<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id_penjualan');
            $table->string('no_faktur')->nullable();
            $table->integer('id_member');
            $table->integer('id_dokter');
            $table->date('tanggal');
            $table->integer('total_item');
            $table->integer('total_harga');
            $table->tinyInteger('diskon')->default(0);
            $table->tinyInteger('ppn')->default(0);
            $table->integer('bayar')->default(0);
            $table->integer('diterima')->default(0);
            $table->string('status');
            $table->date('jatuh_tempo');
            $table->integer('id_user');
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
        Schema::dropIfExists('penjualan');
    }
}
