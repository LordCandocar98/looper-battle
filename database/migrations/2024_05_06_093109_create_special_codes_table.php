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
        Schema::create('special_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('value')->default(0);
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('purchase_type_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('special_codes');
    }
};
