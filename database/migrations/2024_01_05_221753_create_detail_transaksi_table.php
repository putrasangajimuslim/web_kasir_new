<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah');
            $table->double('harga_jual');
            $table->double('subtotal_item');
            $table->double('keuntungan');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('barang_id')
                    ->references('id')
                    ->on('barang')
                    ->onUpdate('cascade')
                    ->onDelete('cascade'); 

            $table->foreign('transaksi_id')
                    ->references('id')
                    ->on('transaksi')
                    ->onUpdate('cascade')
                    ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_transaksi');
    }
}
