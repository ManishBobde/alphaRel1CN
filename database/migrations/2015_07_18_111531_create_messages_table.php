<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function(Blueprint $table) {
            $table->increments('msgId');
            $table->string('msgTitle');
            $table->string('msgTo');
            $table->text('msgDesc');
            $table->boolean('msgRead');
            $table->integer('userId')->unsigned();
            $table->integer('bucketId')->unsigned();
            $table->foreign('userId')->references('userId')->on('users');
            $table->foreign('bucketId')->references('bucketId')->on('buckets');
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
        Schema::drop('messages');
    }
}
