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
        Schema::create('fuel_records', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('vehicle_id');
    $table->date('date');
    $table->float('fuel_amount'); // liter
    $table->timestamps();

    $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_records');
    }
};
