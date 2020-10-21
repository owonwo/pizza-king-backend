<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHistory extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->json('product_ids');
            $table->integer('zipcode', false);
            $table->mediumText('delivery_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('order_histories');
    }
}
