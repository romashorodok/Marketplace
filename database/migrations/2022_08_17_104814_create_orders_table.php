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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('total_price', 10, 2);
            $table->string('charge_token')->nullable();

            $table->string('firstName');
            $table->string('lastName');
            $table->string('address');

            $table->foreignId('user_id')
                ->constrained('users');
        });

        Schema::table('billing_items', function (Blueprint $table) {
            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::table('billing_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });
    }
};
