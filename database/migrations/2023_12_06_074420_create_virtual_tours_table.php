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
        Schema::create('virtual_tours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('minute')->nullable();
            $table->string('duration')->nullable();
            $table->text('audio_file');
            $table->text('trial_audio_file');
            $table->text('thumbnail_file');
            $table->text('short_description');
            $table->text('description');
            $table->float('price')->nullable();
            $table->string('cencellation_policy',500)->nullable();
            $table->string('status',10)->nullable()->comment('0:Pending,1:Approved, 3:Delete, 4:Archive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_tours');
    }
};