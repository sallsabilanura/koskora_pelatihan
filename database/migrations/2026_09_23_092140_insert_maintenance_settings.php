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
        \DB::table('settings')->insert([
            [
                'key' => 'web_maintenance',
                'label' => 'Web Maintenance Mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_maintenance',
                'label' => 'App Maintenance Mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('settings')->whereIn('key', ['web_maintenance', 'app_maintenance'])->delete();
    }
};
