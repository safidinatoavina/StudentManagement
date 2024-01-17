<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('tp')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('matiere_id');
            $table->timestamps();

            $table->foreign('matiere_id')
                ->references('id')
                ->on('matieres')
                ->onUpdate('cascade')
                ->onDelete('cascade'); 

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('t_p_s');
    }
}
