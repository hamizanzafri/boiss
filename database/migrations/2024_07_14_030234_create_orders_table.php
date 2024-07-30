<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id'); // This creates an unsigned integer primary key
            $table->unsignedBigInteger('user_id'); // Ensure this matches the type in the users table
            $table->string('name');
            $table->string('phone_number');
            $table->string('email');
            $table->text('address');
            $table->text('product');
            $table->text('type')->nullable();
            $table->text('quantity');
            $table->text('size');
            $table->decimal('total_paid', 8, 2);
            $table->string('payment_id');
            $table->string('status');
            $table->string('payment_status');
            $table->string('user_type')->default('general');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
    }
}
