<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id'); // This creates an unsigned integer primary key
            $table->unsignedBigInteger('user_id'); // Ensure this matches the type in the users table
            $table->string('membership_id');
            $table->string('name');
            $table->text('address');
            $table->timestamps();

            // Foreign key constraint linking to the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Ensure the users table and membership_id have appropriate indexing for performance
            $table->index('user_id');
            $table->index('membership_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberships');
    }
}
