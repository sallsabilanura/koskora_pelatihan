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
        Schema::create('facility_room', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['facilities_id']);
            $table->dropColumn('facilities_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_room');
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('facilities_id')->nullable()->constrained('facilities')->onDelete('cascade');
        });
    }
};
