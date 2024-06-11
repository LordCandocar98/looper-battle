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
        Schema::create('airdrop_game_mode_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airdrop_game_id');
            $table->unsignedBigInteger('player_id');
            $table->integer('score');
            $table->integer('kills');
            $table->timestamps();

            $table->foreign('airdrop_game_id')->references('id')->on('airdrop_game_mode')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('player_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airdrop_game_mode_scores');
    }
};
