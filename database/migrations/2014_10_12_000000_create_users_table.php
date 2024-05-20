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
            $table->bigIncrements('id');
            $table->string('kode_karyawan', 6);
            $table->string('nama', 30);
            $table->date('tgl_lahir');
            $table->integer('status');
            $table->string('no_hp', 12);
            $table->longText('alamat');
            $table->string('jenis_kelamin', 6);
            $table->string('password', 60);
            $table->string('role', 5);
            $table->string('email', 30);
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
