<?php

namespace Database\Seeders;

use App\Models\BarangayOfficer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        BarangayOfficer::updateOrCreate(
            ['email' => 'admin@barangay.local'],
            [
                'fullname' => 'Juan Dela Cruz',
                'username' => 'barangay_admin',
                'password' => Hash::make('password123'),
                'contact' => '09123456789',
                'address' => 'Barangay Hall',
                'role' => 'admin',
            ]
        );
    }
}
