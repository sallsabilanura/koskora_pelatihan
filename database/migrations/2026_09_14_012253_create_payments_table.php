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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
$table->foreignId('rentals_id')
      ->constrained('rentals')
      ->onDelete('cascade');
            $table->date('payment_date');
            $table->string('payment_period');
            $table->decimal('amount');
            $table->string('payment_method');
            $table->enum('status', ['pending', 'paid', 'overdue']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
