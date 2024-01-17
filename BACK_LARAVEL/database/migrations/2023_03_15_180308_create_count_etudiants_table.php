<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountEtudiantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('count_etudiants', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('count');
            $table->foreignId('annee_universitaire_id');
            $table->timestamps();

            $table->foreign('annee_universitaire_id')
                ->references('id')
                ->on('annee_universitaires')
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
        Schema::dropIfExists('count_etudiants');
    }
}
