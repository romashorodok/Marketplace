<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->string('path');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->nullable();

            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->nullable();

            $table->foreign('image_id')
                ->references('id')
                ->on('images')
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
        Schema::dropIfExists('image');
    }
};
