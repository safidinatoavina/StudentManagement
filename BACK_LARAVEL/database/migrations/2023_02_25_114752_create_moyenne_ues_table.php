<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoyenneUesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moyenne_ues', function (Blueprint $table) {
            $table->id();
            $table->decimal('valeur')->default(0);
            $table->decimal('valeur_session_normal')->default(0);
            $table->foreignId('semestre_id');
            $table->foreignId('ue_id');
            $table->foreignId('historique_id');
            $table->timestamps();

            $table->foreign('semestre_id')
                ->references('id')
                ->on('semestres')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ue_id')
                ->references('id')
                ->on('ues')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('historique_id')
                ->references('id')
                ->on('historiques')
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
        Schema::dropIfExists('moyenne_ues');
    }
}
