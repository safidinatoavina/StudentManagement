<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id');
            $table->foreignId('parcour_id');
            $table->string('numeroInscription');
            $table->foreignId('annee_universitaire_id');
            $table->foreignId('status_id');
            $table->timestamps();
            $table->foreign('etudiant_id')
            ->references('id')
            ->on('etudiants')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('parcour_id')
                ->references('id')
                ->on('parcours')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
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
        Schema::dropIfExists('historiques');
    }
}
