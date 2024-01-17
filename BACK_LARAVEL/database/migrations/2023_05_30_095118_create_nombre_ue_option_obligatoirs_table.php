<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNombreUeOptionObligatoirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nombre_ue_option_obligatoirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcour_id');
            $table->foreignId('semestre_id');
            $table->unsignedInteger('nombre_ue_obli');
            $table->timestamps();

            $table->foreign('parcour_id')
                ->references('id')
                ->on('parcours')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('semestre_id')
                ->references('id')
                ->on('semestres')
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
        Schema::dropIfExists('nombre_ue_option_obligatoirs');
    }
}
