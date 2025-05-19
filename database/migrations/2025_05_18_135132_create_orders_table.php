<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('approver_level_1')->nullable();
            $table->unsignedBigInteger('approver_level_2')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->enum('approver_level_1_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('approver_level_2_status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamp('approved_at_level_1')->nullable();
            $table->unsignedBigInteger('approved_by_level_1')->nullable();
            $table->timestamp('approved_at_level_2')->nullable();
            $table->unsignedBigInteger('approved_by_level_2')->nullable();

            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();

            $table->date('start_date');
            $table->date('end_date');
            $table->string('purpose')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null'); // ✅ diperbaiki
            $table->foreign('approver_level_1')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approver_level_2')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_level_1')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_level_2')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
