<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_checks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->datetime('time_arrived');
            $table->integer('eventlist_id')->unsigned();
            $table->foreign('eventlist_id')->references('id')->on('event_lists')->onDelete('cascade');
            $table->integer('invited_id')->unsigned()->nullable();
            $table->foreign('invited_id')->references('id')->on('users');
            $table->integer('graduate_id')->unsigned();
            $table->foreign('graduate_id')->references('id')->on('graduates');
            $table->integer('sell');
            $table->integer('sold');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_checks');
    }
}
