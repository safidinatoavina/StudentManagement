<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ue_options', function (Blueprint $table) {

            $table->id();
            $table->foreignId('parcour_id');
            $table->foreignId('historique_id');
            $table->foreignId('ue_id');
            $table->timestamps();

            $table->foreign('parcour_id')
                ->references('id')
                ->on('parcours')
                ->onUpdate('cascade')
                ->onDelete('cascade'); 
            
            $table->foreign('historique_id')
                ->references('id')
                ->on('historiques')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->foreign('ue_id')
                ->references('id')
                ->on('ues')
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
        Schema::dropIfExists('ue_options');
    }
}
