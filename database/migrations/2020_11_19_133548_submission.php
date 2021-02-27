<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Submission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // verdict is integer type
        // wrong answer = 2
        // time limit = 3
        // memory limit = 4
        // accept = 1 (min)
        // not judge yet = 5 or max(default)

        Schema::create('submission', function(Blueprint $table){
            $table->bigIncrements('sub_id');
            //$table->integer('contest_id');
            $table->varchar('language_id');
            $table->integer('user_id');
            $table->integer('problem_id');
            $table->text('code');
            $table->float('cpu')->default(0);
            $table->integer('memory')->default(0);
            $table->tinyInteger('verdict')->default(5);
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
