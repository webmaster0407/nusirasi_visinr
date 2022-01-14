<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers_numbers', function (Blueprint $table) {
            //$table->increments('id');
            $table->increments('id');

            $table->bigInteger('number')->unique();
            $table->integer('comment_count')->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('rating')->default(0);
            $table->integer('view_count')->default(1);
            $table->string('view_last_ip', 16)->nullable();

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
        Schema::dropIfExists('numbers_numbers');
    }
}
