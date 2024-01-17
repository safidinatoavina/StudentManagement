<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->nullable();
            $table->foreignId('mention_id')->nullable();
            $table->string('parcour');
            $table->string('abreviation')->nullable();
            $table->timestamps();
            $table->foreign('grade_id')
                ->references('id')
                ->on('grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('mention_id')
                ->references('id')
                ->on('mentions')
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
        Schema::dropIfExists('parcours');
    }
}
