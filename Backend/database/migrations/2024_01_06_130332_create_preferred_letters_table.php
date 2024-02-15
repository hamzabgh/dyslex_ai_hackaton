<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreferredLettersTable extends Migration
{
    public function up()
    {
        Schema::create('preferred_letters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('letter_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('letter_id')->references('id')->on('letters');
        });
    }

    public function down()
    {
        Schema::dropIfExists('preferred_letters');
    }
}

