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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilities_id')
      ->constrained('facilities')
      ->onDelete('cascade');

            $table->foreignId('properties_id')
      ->constrained('properties')
      ->onDelete('cascade');
            $table->string('room_number');
            $table->string('floor');
            $table->enum('status', ['available', 'occupied', 'maintenance']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
