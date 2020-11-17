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
        //
        Schema::create('problems',function (Blueprint $table){
           $table->id();
           $table->foreignId('setter_id');
           $table->string('title',100);
           $table->text('description');
           $table->text('input_format');
           $table->text('output_format');
           $table->tinyInteger('time_limit');
           $table->integer('memory_limit');
           $table->string('sample_input',100);
           $table->string('sample_output',100);
           $table->boolean('execution_type');
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
