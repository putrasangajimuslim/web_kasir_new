<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('tgl_transaksi');
            $table->double('subtotal');
            $table->unsignedBigInteger('kasir_id');
            $table->string('status_pembayaran');
            $table->double('total_pembayaran');
            $table->double('total_kembalian');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kasir_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
