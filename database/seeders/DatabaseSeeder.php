<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
            MessageSeeder::class,
        ]);
        User::factory()->create([
            'name' => "Namariq",
            'username' => "Namariq123",
            'email' => 'namariq@gmail.com',
            "password" => Hash::make('Nam@123'),
            'phone' => "01231421412",
            'role' => 'admin'
        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
