<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoyenneGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moyenne_generals', function (Blueprint $table) {
            $table->id();
            $table->decimal('moyenne');
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
        Schema::dropIfExists('moyenne_generals');
    }
}
