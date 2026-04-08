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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_code')->unique();
            $table->dateTime('pickup_datetime');
            $table->dateTime('return_datetime');
            $table->integer('total_days');
            $table->integer('total_price');
            $table->enum('payment_status', ['unpaid', 'half', 'paid'])->default('unpaid');
            $table->enum('status', ['pending', 'active', 'returned', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
