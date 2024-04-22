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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('purchase_type_id');
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('rewards')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('purchase_type_id')->references('id')->on('purchase_types')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('purchases');
    }
};
