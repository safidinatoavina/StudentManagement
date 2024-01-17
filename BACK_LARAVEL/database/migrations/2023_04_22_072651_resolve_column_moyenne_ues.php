<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolveColumnMoyenneUes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moyenne_ues', function (Blueprint $table) {
            $table->float('credit',false,true)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moyenne_ues', function (Blueprint $table) {
            $table->dropColumn('credit');
        });
    }

}
