<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'first_name' => 'Super',
            'middle_name' => null,
            'last_name' => 'Admin',
            'email' => 'admin@gymsystem.com',
            'password' => Hash::make('password123'),
        ]);

        // Add more sample admins
        Admin::create([
            'first_name' => 'John',
            'middle_name' => 'M',
            'last_name' => 'Smith',
            'email' => 'john.smith@gymsystem.com',
            'password' => Hash::make('password123'),
        ]);

        Admin::create([
            'first_name' => 'Sarah',
            'middle_name' => null,
            'last_name' => 'Johnson',
            'email' => 'sarah.johnson@gymsystem.com',
            'password' => Hash::make('password123'),
        ]);
    }
}