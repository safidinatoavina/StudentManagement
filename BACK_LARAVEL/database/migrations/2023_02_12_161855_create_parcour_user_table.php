<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcourUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcour_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcour_id');
            $table->foreignId('user_id');
            $table->timestamps();

            $table->foreign('parcour_id')
            ->references('id')
            ->on('parcours')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('parcour_user');
    }
}
