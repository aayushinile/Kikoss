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
        Schema::create('photo_booth_medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('booth_id')->nullable();
            $table->string('media_type',45)->nullable()->comment("Photo, Video");
            $table->text('media');
            $table->string('status',10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_booth_medias');
    }
};