<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('match_id');
            $table->integer('points');
            $table->integer('kills');
            $table->integer('deaths');
            $table->boolean('is_winner')->default(false);
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['player_id', 'match_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players_scores');
    }
};
