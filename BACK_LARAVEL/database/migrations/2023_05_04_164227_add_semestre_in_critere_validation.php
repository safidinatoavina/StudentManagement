<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemestreInCritereValidation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('critere_validations', function (Blueprint $table) {
            $table->foreignId('semestre_id')->after('parcour_id');
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
        Schema::table('critere_validations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('semestre_id');
        });
    }
}
