<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Problems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ALTER TABLE users AUTO_INCREMENT=1001;
        Schema::create('problems',function (Blueprint $table){
           $table->bigIncrements('id')->autoIncrement(1000);
           $table->integer('setter_id');
           $table->string('title',100);
           $table->text('description');
           $table->text('input_format');
           $table->text('output_format');
           $table->text('note')->nullable();
           $table->float('time_limit');
           $table->integer('memory_limit');
           $table->string('sample_input',100);
           $table->string('sample_output',100);
           $table->tinyInteger('status');
           //$table->boolean('execution_type');
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
