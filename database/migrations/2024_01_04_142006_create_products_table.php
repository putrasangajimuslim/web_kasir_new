<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang')->nullable();
            $table->integer('id_kategori')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('merk')->nullable();
            $table->string('harga_beli')->nullable();
            $table->string('harga_jual')->nullable();
            $table->integer('margin_keuntungan')->nullable();
            $table->string('satuan_barang')->nullable();
            $table->integer('stok')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
