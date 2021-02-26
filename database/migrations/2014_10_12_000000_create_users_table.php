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
        /**
         *  user_type (tiny int)
         *          default value is 0
         *          0. practice user
         *          1. practice user and setter
         *      only admin can change the user type
         *      a setter can create, update, delete... problem description
         */

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('user_type')->default(0);
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('picture')->nullable();
            $table->string('country')->nullable();
            $table->string('institution')->nullable();
            $table->timestamps();
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
