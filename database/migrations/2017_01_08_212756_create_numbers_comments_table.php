<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers_comments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('number_id')->unsigned();
            $table->foreign('number_id')->references('id')->on('numbers_numbers');

            $table->string('author')->nullable();
            //$table->dateTime('date')->nullable();
            $table->text('content')->nullable();
            $table->string('ip', 16)->nullable();
            $table->string('user_agent')->nullable();
            //$table->boolean('is_active')->default(1);

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
        Schema::dropIfExists('numbers_comments');
    }
}
