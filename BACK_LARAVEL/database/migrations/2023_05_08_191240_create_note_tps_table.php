<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteTpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_tps', function (Blueprint $table) {
            $table->id();
            $table->float('valeur')->nullable()->default(0);
            $table->tinyInteger('is_set',false,true)->nullable()->default(0);
            $table->foreignId('tp_id');
            $table->foreignId('historique_id');
            $table->timestamps();

            $table->foreign('tp_id')
                ->references('id')
                ->on('t_p_s')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('historique_id')
                ->references('id')
                ->on('historiques')
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
        Schema::dropIfExists('note_tps');
    }
}
