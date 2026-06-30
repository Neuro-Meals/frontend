<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@nutriomeals.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+966500000001',
        ]);

        // Regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@nutriomeals.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '+966500000002',
        ]);

        // Sample customer 1
        User::create([
            'name' => 'Ahmed Al-Saud',
            'email' => 'ahmed@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+966551234567',
        ]);

        // Sample customer 2
        User::create([
            'name' => 'Sarah Al-Otaibi',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+966552345678',
        ]);

        // Sample customer 3
        User::create([
            'name' => 'Khalid Al-Ghamdi',
            'email' => 'khalid@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+966553456789',
        ]);
    }
}
