<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatieresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('matiere');
            $table->tinyInteger('status')->nullable()->default(1);
            $table->foreignId('parcour_id');
            $table->foreignId('user_id');
            $table->foreignId('ue_id');
            $table->foreignId('semestre_id')->nullable();
            $table->float('coefficient',false,true)->nullable()->default(1);
            $table->timestamps();

            $table->foreign('parcour_id')
                ->references('id')
                ->on('parcours')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matieres');
    }
}
