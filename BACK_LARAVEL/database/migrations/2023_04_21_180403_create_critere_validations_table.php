<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritereValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critere_validations', function (Blueprint $table) {
            
            $table->id();
            $table->string('type')->nullable();//'v' ou vpc'
            $table->string('logique')->nullable()->default('et');//'et' ou 'ou'
            $table->tinyInteger('min_ue')->nullable();
            $table->tinyInteger('max_ue')->nullable();
            $table->decimal('min_moyenne')->nullable();
            $table->decimal('max_moyenne')->nullable();
            $table->float('min_credit',false,true)->nullable();
            $table->float('max_credit',false,true)->nullable();
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
        Schema::dropIfExists('critere_validations');
    }
}
