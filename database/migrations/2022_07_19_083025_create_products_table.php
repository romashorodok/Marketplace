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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->decimal('price', 10,2);
            $table->integer('count');

            $table->unsignedBigInteger('category_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
