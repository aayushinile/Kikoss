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
        Schema::create('tours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('total_people')->nullable();
            $table->integer('duration')->nullable();
            $table->float('age_11_price')->nullable();
            $table->float('age_60_price')->nullable();
            $table->float('under_10_age_price')->nullable();
            $table->text('description')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->string('thumbnail',255)->nullable();
            $table->string('status',45)->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};