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
        Schema::create('tour_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tour_id')->nullable();
            $table->string('attribute_type',45)->nullable();
            $table->string('attribute_name',255)->nullable();
            $table->string('value',500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_attributes');
    }
};