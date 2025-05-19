<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('activity');
    $table->string('model')->nullable(); // misal: 'Order', 'Vehicle'
    $table->unsignedBigInteger('model_id')->nullable();
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
