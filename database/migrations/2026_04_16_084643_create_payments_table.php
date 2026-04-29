<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // Membership, Trainer Session, etc.
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('method'); // Credit Card, Cash, PayPal, etc.
            $table->enum('status', ['Paid', 'Pending', 'Overdue'])->default('Pending');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};