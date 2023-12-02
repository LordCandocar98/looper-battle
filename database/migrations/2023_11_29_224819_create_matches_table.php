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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('room_name', 50);
            $table->string('access_code')->nullable();
            $table->enum('privacy', ['public', 'private'])->default('public');
            $table->string('map', 50);
            $table->enum('game_mode', ['free_for_all', 'team_deathmatch', 'capture_the_flag'])->default('free_for_all');
            $table->integer('max_players')->default(6);
            $table->integer('room_time_limit')->default(15);
            $table->integer('game_mode_goal')->default(25);
            $table->enum('team_selection', ['manually', 'randomly'])->default('manually');
            $table->boolean('friendly_fire')->default(false);
            $table->boolean('bots')->default(false);
            $table->boolean('pay_tournament')->default(false);
            $table->string('payment_code')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'finished'])->default('pending');
            $table->dateTime('date')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
};
