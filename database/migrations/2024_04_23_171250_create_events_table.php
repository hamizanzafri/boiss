<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event')->nullable();
            $table->string('venue')->nullable();
            $table->date('date')->nullable();
            $table->string('photo')->nullable(); // assuming photos are optional
            $table->decimal('ticket_price', 8, 2); // decimal type for prices, adjust precision and scale if needed
            $table->integer('ticket_stock'); // integer type for stock count
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
