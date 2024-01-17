<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolvePersonnePrenom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personnes', function (Blueprint $table) {
            $table->dropColumn('prenom');
        });

        Schema::table('personnes', function (Blueprint $table) {
            $table->string('prenom')->after('nom')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personnes', function (Blueprint $table) {
            $table->dropColumn('prenom');
        });
        Schema::table('personnes', function (Blueprint $table) {
            $table->string('prenom')->after('nom');
        });
    }
}
