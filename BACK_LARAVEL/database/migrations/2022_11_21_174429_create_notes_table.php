<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historique_id');
            $table->foreignId('session_id');
            $table->foreignId('matiere_id');
            $table->foreignId('semestre_id');
            $table->tinyInteger('public')->default('0');
            $table->tinyInteger('is_set')->nullable()->default(0);
            $table->decimal('valeur',8,2,true)->nullable();
            $table->timestamps();

            $table->foreign('historique_id')
                ->references('id')
                ->on('historiques')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('semestre_id')
                ->references('id')
                ->on('semestres')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('matiere_id')
                ->references('id')
                ->on('matieres')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('session_id')
                ->references('id')
                ->on('sessions')
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
        Schema::dropIfExists('notes');
    }
}
