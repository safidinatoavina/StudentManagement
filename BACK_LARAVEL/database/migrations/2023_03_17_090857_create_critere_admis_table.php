<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritereAdmisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critere_admis', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();//'redoublant ou passant'
            $table->tinyInteger('min_ue')->nullable();
            $table->tinyInteger('max_ue')->nullable();
            $table->decimal('min_moyenne')->nullable();
            $table->decimal('max_moyenne')->nullable();
            $table->foreignId('annee_universitaire_id');
            $table->foreignId('parcour_id');
            $table->timestamps();

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
        Schema::dropIfExists('critere_admis');
    }
}
