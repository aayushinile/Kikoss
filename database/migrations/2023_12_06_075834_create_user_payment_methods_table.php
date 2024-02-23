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
        Schema::create('user_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userid')->nullable();
            $table->string('method_type',45)->nullable();
            $table->string('card_no',45)->nullable();
            $table->string('card_type',45)->nullable();
            $table->string('expiry',50)->nullable();
            $table->string('CVV',45)->nullable();
            $table->string('name_on_card',200)->nullable();
            $table->tinyInteger('is_default')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_payment_methods');
    }
};