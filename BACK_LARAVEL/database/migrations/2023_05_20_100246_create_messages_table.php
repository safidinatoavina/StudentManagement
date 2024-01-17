<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id');
            $table->foreignId('recipient_id');
            $table->text('content');
            $table->boolean('is_seen')->default(false);
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
