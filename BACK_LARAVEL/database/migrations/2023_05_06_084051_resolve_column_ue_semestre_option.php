<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolveColumnUeSemestreOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ues', function (Blueprint $table) {
            $table->foreignId('semestre_id')->after('ue')->nullable();
            $table->tinyInteger('option')->after('ue')->nullable();
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
        Schema::table('ues', function (Blueprint $table) {
            $table->dropConstrainedForeignId('semestre_id');
            $table->dropColumn('option');
        });
    }
}
