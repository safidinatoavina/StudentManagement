<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personne_photos', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->foreignId('personne_id');
            $table->timestamps();
            $table->foreign('personne_id')
            ->references('id')
            ->on('personnes')
            ->onUpdate('cascade')
            ->onDelete('cascade');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personne_photos');
    }
}
