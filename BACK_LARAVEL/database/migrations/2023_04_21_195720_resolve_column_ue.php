<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResolveColumnUe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ues', function (Blueprint $table) {
            $table->foreignId('parcour_id')->after('id');
            $table->float('credit',false,true)->nullable()->default(0)->after('ue');
            $table->float('coefficient',false,true)->nullable()->default(0)->after('ue');
            $table->foreign('parcour_id')
                ->references('id')
                ->on('parcours')
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
            $table->dropConstrainedForeignId('parcour_id');
            $table->dropColumn('credit');
            $table->dropColumn('coefficient');
        });
    }
}
