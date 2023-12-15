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
        Schema::create('callback_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tour_id')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile',100)->nullable();
            $table->string('timezone',100)->nullable();
            $table->dateTime('preferred_time')->nullable();
            $table->text('note');
            $table->string('status',10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callback_requests');
    }
};