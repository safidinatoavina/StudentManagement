<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnCoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id');
            $table->foreignId('semestre_id');
            $table->timestamps();

            $table->foreign('session_id')
                ->references('id')
                ->on('sessions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('semestre_id')
                ->references('id')
                ->on('semestres')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_cours');
    }
}
