<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_taxis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('booking_time')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('pickup_location',500)->nullable();
            $table->string('pickup_lat_long',200)->nullable();
            $table->string('drop_location',500)->nullable();
            $table->string('drop_lat_long',200)->nullable();
            $table->string('mobile',200)->nullable();
            $table->string('hotel_name',100)->nullable();
            $table->string('book_taxicol',45)->nullable();
            $table->float('distance')->nullable();
            $table->string('status',45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_taxis');
    }
};