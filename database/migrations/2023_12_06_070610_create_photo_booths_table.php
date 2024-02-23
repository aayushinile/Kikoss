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
        Schema::create('photo_booths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tour_id')->nullable();
            $table->integer('delete_days')->nullable();
            $table->string('users_id')->nullable();
            $table->string('title')->nullable();
            $table->float('price',100)->nullable();
            $table->text('description');
            $table->text('cancellation_policy');    
            $table->string('status',10)->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_booths');
    }
};