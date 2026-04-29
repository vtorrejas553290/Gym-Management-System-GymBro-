<?php

namespace Database\Seeders;

use App\Models\Trainer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = [
            [
                'first_name' => 'Alex',
                'middle_name' => null,
                'last_name' => 'Thompson',
                'email' => 'alex.t@gym.com',
                'phone' => '+1 (555) 111-2222',
                'password' => Hash::make('password123'),
                'specialization' => 'Strength Training',
                'experience' => 5,
                'hourly_rate' => 850,
                'status' => 'Active',
            ],
            [
                'first_name' => 'Jessica',
                'middle_name' => null,
                'last_name' => 'Martinez',
                'email' => 'jessica.m@gym.com',
                'phone' => '+1 (555) 222-3333',
                'password' => Hash::make('password123'),
                'specialization' => 'Yoga & Pilates',
                'experience' => 7,
                'hourly_rate' => 900,
                'status' => 'Active',
            ],
            [
                'first_name' => 'David',
                'middle_name' => null,
                'last_name' => 'Chen',
                'email' => 'david.c@gym.com',
                'phone' => '+1 (555) 333-4444',
                'password' => Hash::make('password123'),
                'specialization' => 'Cardio & HIIT',
                'experience' => 4,
                'hourly_rate' => 750,
                'status' => 'Active',
            ],
            [
                'first_name' => 'Emma',
                'middle_name' => null,
                'last_name' => 'Wilson',
                'email' => 'emma.w@gym.com',
                'phone' => '+1 (555) 444-5555',
                'password' => Hash::make('password123'),
                'specialization' => 'CrossFit',
                'experience' => 6,
                'hourly_rate' => 950,
                'status' => 'Active',
            ],
            [
                'first_name' => 'Mike',
                'middle_name' => null,
                'last_name' => 'Chen',
                'email' => 'mike.chen@gym.com',
                'phone' => '+1 (555) 555-6666',
                'password' => Hash::make('password123'),
                'specialization' => 'Strength Training',
                'experience' => 8,
                'hourly_rate' => 800,
                'status' => 'Active',
            ],
            [
                'first_name' => 'Sarah',
                'middle_name' => null,
                'last_name' => 'Johnson',
                'email' => 'sarah.j@gym.com',
                'phone' => '+1 (555) 666-7777',
                'password' => Hash::make('password123'),
                'specialization' => 'Cardio & HIIT',
                'experience' => 6,
                'hourly_rate' => 750,
                'status' => 'Active',
            ],
        ];

        foreach ($trainers as $trainer) {
            if (!Trainer::where('email', $trainer['email'])->exists()) {
                Trainer::create($trainer);
            }
        }
    }
}