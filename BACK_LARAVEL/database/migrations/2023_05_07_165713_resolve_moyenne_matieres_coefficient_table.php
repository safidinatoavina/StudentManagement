<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolveMoyenneMatieresCoefficientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moyenne_matieres', function (Blueprint $table) {
            $table->float('default_coefficient',false,true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moyenne_matieres', function (Blueprint $table) {
            $table->dropColumn('default_coefficient');
        });
    }
}
