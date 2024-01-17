<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AjoutIndexNumInscriptionHistorique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historiques', function (Blueprint $table) {
            $table->index('numeroInscription');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historiques', function (Blueprint $table) {
            $table->dropIndex('historiques_numeroinscription_index');
        });
    }
}
