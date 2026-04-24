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
        Schema::create('password_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('officer_id');
            $table->unsignedBigInteger('requested_by'); // admin id
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('officer_id')->references('id')->on('barangay_officers')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('barangay_officers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_change_requests');
    }
};
