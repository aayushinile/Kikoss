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
        Schema::create('photo_booth_booking', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("booth_id")->nullable();
            $table->date("booking_date")->nullable();
            $table->float("amount")->nullable();
            $table->integer("image_count")->nullable();
            $table->integer("video_count")->nullable();
            $table->float("tax")->nullable();
            $table->float("tax_percent")->nullable();
            $table->float("total_amount")->nullable();
            $table->bigInteger("userid")->nullable();
            $table->string("user_name")->nullable();
            $table->string("status")->nullable();
            $table->string("transaction_id")->nullable();
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
        Schema::dropIfExists('photo_booth_booking');
    }
};