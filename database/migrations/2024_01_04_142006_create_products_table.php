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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 4)->nullable();
            $table->integer('kategori_id')->nullable();
            $table->string('nama_barang', 30)->nullable();
            $table->string('merk', 30)->nullable();
            $table->integer('stok')->nullable();
            $table->date('date_expired')->nullable();
            $table->double('harga_beli')->nullable();
            $table->double('harga_jual')->nullable();
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
