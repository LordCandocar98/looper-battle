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
        Schema::create('code_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('reward_id');

            $table->foreign('player_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('reward_id')->references('id')->on('rewards')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('code_assignments');
    }
};
