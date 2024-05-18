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
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kasir_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('transaksi');
    }
}
