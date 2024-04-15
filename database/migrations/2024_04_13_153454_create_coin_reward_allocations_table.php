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
        Schema::create('coin_reward_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('coin_reward_id');
            $table->date('week_start');
            $table->timestamps();

            $table->unique(['player_id', 'coin_reward_id', 'week_start']);

            $table->foreign('player_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coin_reward_id')->references('id')->on('coin_rewards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_reward_allocations');
    }
};
