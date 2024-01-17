<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolveColumnMoyenneSemestres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moyenne_semestres', function (Blueprint $table) {
            $table->float('total_credit',false,true)->nullable()->default(0)->after('historique_id');
            $table->integer('total_ue_valide',false,true)->nullable()->default(0)->after('historique_id');
            $table->string('validation',false,true)->nullable()->default('NV')->after('historique_id');
            $table->tinyInteger('is_valide',false,true)->nullable()->default(0)->after('historique_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moyenne_semestres', function (Blueprint $table) {
            $table->dropColumn('total_credit');
            $table->dropColumn('total_ue_valide');
            $table->dropColumn('validation');
            $table->dropColumn('is_valide');
        });
    }
}
