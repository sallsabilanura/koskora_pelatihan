<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user for testing
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@koskora.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);
        
        // Optionally create a test owner
        $owner = User::create([
            'name' => 'Test Owner',
            'email' => 'owner@koskora.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'owner',
        ]);

        // Seed some properties for the owner
        \App\Models\Property::create([
            'user_id' => $owner->id,
            'name' => 'Koskora Sudirman Center',
            'address' => 'Jl. Jend. Sudirman No. 1, Jakarta',
            'description' => 'Fasilitas premium di pusat kota Jakarta.',
        ]);

        \App\Models\Property::create([
            'user_id' => $owner->id,
            'name' => 'Koskora Kemang Residence',
            'address' => 'Jl. Kemang Raya No. 10, Jakarta Selatan',
            'description' => 'Kos eksklusif dengan lingkungan yang tenang dan asri.',
        ]);
    }
}
