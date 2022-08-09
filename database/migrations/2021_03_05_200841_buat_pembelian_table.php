<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian');
            $table->integer('id_supplier');
            $table->date('tanggal');
            $table->integer('total_item');
            $table->integer('total_harga');
            $table->tinyInteger('diskon')->default(0);
            $table->tinyInteger('ppn')->default(0);
            $table->integer('bayar')->default(0);
            $table->string('status');
            $table->date('jatuh_tempo')->default(date('Y-m-d'));
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
        Schema::dropIfExists('pembelian');
    }
}
