<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Contest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * hash (string)
         *          use in url instead of id
         *          contest creator provide this hash value. if not auto hash generated
         *          must be unique()
         *          ex. SUB_IUPC_21
         *
         * contest_type (int)
         *          1. open for all user   (category problems)
         *          2. open for all user   (need to apply)
         *          3. invited user only   (personal account or account for this contest only)
         *      in contest type 3, contest creator or problem setter select the user using username.
         *      or apply to the admin to create user account for this contest only.
         *
         * status (int)
         *          contest status
         *          0. not start
         *          1. running
         *          2. finish but rank fridge
         *          3. finish and result publish
         *
         * show_problem_description
         *          only allow for contest_type 3
         */

        Schema::create('contest', function (Blueprint $table){
           $table->bigIncrements('id');
           $table->string('hash', 30);
           $table->integer('user_id');
           $table->string('title', 60);
           $table->string('description', 200);
           $table->tinyInteger('type');
           $table->boolean('show_problem_description');
           $table->timestamp('start_time');
           $table->float('length',4 ,2);
           $table->tinyInteger('status');
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
        //
    }
}
