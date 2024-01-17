<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolveCritereAdmisColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('critere_admis', function (Blueprint $table) {
            $table->string('logique')->nullable()->default('et');
            $table->float('min_credit',false,true)->nullable()->default(0);
            $table->float('max_credit',false,true)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('critere_admis', function (Blueprint $table) {
            $table->dropColumn('logique');
            $table->dropColumn('min_credit');
            $table->dropColumn('max_credit');
        });
    }
}
