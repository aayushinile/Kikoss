<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_stops_details', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('stop_name')->nullable();
            $table->string('stop_number')->nullable();
            $table->text('stop_image')->nullable();
            $table->text('stop_audio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virtual_stops_details');
    }
};
