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
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->foreignId('table_id')->constrained('tables');
            $table->integer('price_amount');
            $table->string('payment_image')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('pay_amount')->nullable();
            $table->string('pay_receive')->nullable();
            $table->string('pay_return')->nullable();
            $table->string('confirm_order')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->string('name_customer');
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