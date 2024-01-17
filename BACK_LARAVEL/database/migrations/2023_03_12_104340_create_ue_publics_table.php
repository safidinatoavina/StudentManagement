<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUePublicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ue_publics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ue_id');
            $table->foreignId('parcour_id');
            $table->foreignId('annee_universitaire_id');
            $table->foreignId('semestre_id');
            $table->tinyInteger('avec_ratrapage',false,true)->default(0);
            $table->timestamps();
            $table->foreign('ue_id')
                ->references('id')
                ->on('ues')
                ->onUpdate('cascade')
                ->onDelete('cascade'); 
            $table->foreign('semestre_id')
                ->references('id')
                ->on('semestres')
                ->onUpdate('cascade')
                ->onDelete('cascade'); 
            $table->foreign('parcour_id')
                ->references('id')
                ->on('parcours')
                ->onUpdate('cascade')
                ->onDelete('cascade'); 
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
        Schema::dropIfExists('ue_publics');
    }
}
