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
            $table->string('name');
            $table->string('email',192)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('utype')->default('USR')->comment('ADM for Admin USR for User');
            $table->integer('level')->nullable()->default(0);
            $table->integer('progress')->nullable();
            $table->char('uuid_user', 36);
            $table->rememberToken();
            $table->timestamps();
        });
         // Add a BEFORE INSERT trigger to set the default value for uuid_user
         \DB::unprepared('
         CREATE TRIGGER users_before_insert BEFORE INSERT ON users
         FOR EACH ROW SET NEW.uuid_user = LOWER(UUID());
     ');
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
