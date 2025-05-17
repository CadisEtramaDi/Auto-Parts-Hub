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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('subtotal', 8, 2);
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->decimal('tax', 8, 2);
            $table->decimal('total', 8, 2);
            $table->string('name');
            $table->string('phone');
            $table->enum('status', ['ordered', 'completed', 'canceled'])->default('ordered');
            $table->date('completed_date')->nullable();
            $table->date('canceled_date')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->default('cash');
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};