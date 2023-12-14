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
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tour_id')->nullable();
            $table->string('tour_type',20)->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->dateTime('booking_date')->nullable();
            $table->integer('no_adults')->nullable();
            $table->integer('no_senior_citizen')->nullable();
            $table->integer('no_childerns')->nullable();
            $table->float('adults_amount')->nullable();
            $table->float('senior_amount')->nullable();
            $table->float('childrens_amount')->nullable();
            $table->float('tax')->nullable();
            $table->float('total_amount')->nullable();
            $table->string('status',10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};