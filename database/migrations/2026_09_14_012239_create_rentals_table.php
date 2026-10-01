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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
          $table->foreignId('user_id')
      ->constrained('users')
      ->onDelete('cascade');

$table->foreignId('room_rentals_id')
      ->constrained('room_rentals')
      ->onDelete('cascade');
      
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rental_price', 10,2);
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
