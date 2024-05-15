<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('member', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nm_member')->nullable();
        //     $table->longText('alamat')->nullable();
        //     $table->string('telp')->nullable();
        //     $table->string('email')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('member');
    }
}
