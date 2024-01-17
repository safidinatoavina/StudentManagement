<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolveColumnMoyenneAnnees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moyenne_annees', function (Blueprint $table) {
            $table->float('total_credit',false,true)->nullable()->default(0)->after('historique_id');
            $table->integer('total_ue_valide',false,true)->nullable()->default(0)->after('historique_id');
            $table->tinyInteger('is_admis')->nullable()->default(0)->after('historique_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moyenne_annees', function (Blueprint $table) {
            $table->dropColumn('total_credit');
            $table->dropColumn('total_ue_valide');
            $table->dropColumn('is_admis');
        });
    }
}
