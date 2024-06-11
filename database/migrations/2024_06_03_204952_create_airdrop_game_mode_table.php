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
        Schema::create('airdrop_game_mode', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->unsignedBigInteger('airdrop_code_id')->nullable();
            $table->string('room_name', 50);
            $table->enum('privacy', ['public', 'private'])->default('private');
            $table->string('map', 50);
            $table->integer('max_players')->default(1);
            $table->integer('room_time_limit')->default(15);
            $table->integer('game_mode_goal')->default(25);
            $table->boolean('bots')->default(true);
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('airdrop_code_id')->references('id')->on('airdrop_codes')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airdrop_game_mode');
    }
};
