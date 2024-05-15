<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('kode_karyawan', 20)->nullable();
            $table->string('nama', 30)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->integer('status')->nullable();
            $table->string('no_hp', 12)->nullable();
            $table->longText('alamat')->nullable();
            $table->string('jenis_kelamin', 6)->nullable();
            $table->string('password', 60)->nullable();
            $table->string('role', 5)->nullable();
            $table->string('email', 30)->unique()->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
