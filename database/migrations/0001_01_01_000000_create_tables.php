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
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 1000)->nullable();
            $table->datetime('date');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 1000)->nullable();
            $table->datetime('date');
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 1000)->nullable();
            $table->datetime('date');
            $table->timestamps();
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 1000)->nullable();
            $table->datetime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('stocks');
    }
};
