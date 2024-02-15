<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->string('slug')->unique();
            $table->char('uuid_category', 36);
            $table->timestamps();
        });
         // Add a BEFORE INSERT trigger to set the default value for uuid_user
         \DB::unprepared('
         CREATE TRIGGER category_before_insert BEFORE INSERT ON categories
         FOR EACH ROW SET NEW.uuid_category = LOWER(UUID());
     ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
