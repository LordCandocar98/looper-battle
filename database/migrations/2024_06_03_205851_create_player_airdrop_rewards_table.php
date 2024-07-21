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
        Schema::create('player_airdrop_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('airdrop_reward_id');
            $table->timestamps();

            $table->unique(['player_id', 'airdrop_reward_id']);

            $table->foreign('player_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('airdrop_reward_id')->references('id')->on('airdrop_rewards')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_airdrop_rewards');
    }
};
