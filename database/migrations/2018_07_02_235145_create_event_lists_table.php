<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('data');
            $table->string('name', 100);
            $table->integer('eventtype_id')->unsigned();
            $table->foreign('eventtype_id')->references('id')->on('event_types');
            $table->string('description', 512);
            $table->integer('city_code')->unsigned();
            $table->foreign('city_code')->references('code')->on('cities')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('event_lists');
    }
}
