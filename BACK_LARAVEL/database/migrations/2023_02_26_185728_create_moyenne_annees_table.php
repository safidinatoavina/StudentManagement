<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoyenneAnneesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moyenne_annees', function (Blueprint $table) {
            $table->id();
            $table->decimal('valeur')->default(0);
            $table->foreignId('historique_id');
            $table->timestamps();

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
        Schema::dropIfExists('moyenne_annees');
    }
}
