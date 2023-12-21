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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname')->nullable();
            $table->string('email')->nullable();
            $table->string('user_profile')->nullable();
            $table->tinyInteger('mailverified')->nullable();
            $table->string('mobile',255)->nullable();
            $table->string('password',255)->nullable();
            $table->string('status',10)->nullable()->default(1);
            $table->integer('type')->default(2)->comment('1:Admin,2:User');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};