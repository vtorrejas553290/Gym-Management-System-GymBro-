<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'duration' => '1 Month',
                'duration_days' => 30,
                'price' => 49,
                'features' => [
                    'Access to gym equipment',
                    'Locker room access',
                    'Free Wi-Fi',
                    '1 guest pass per month',
                ],
                'popular' => false,
                'active' => true,
            ],
            [
                'name' => 'Premium',
                'duration' => '3 Months',
                'duration_days' => 90,
                'price' => 99,
                'features' => [
                    'Access to gym equipment',
                    'Locker room access',
                    'Free Wi-Fi',
                    '5 guest passes per month',
                    'Personal training (2 sessions)',
                    'Nutrition consultation',
                ],
                'popular' => true,
                'active' => true,
            ],
            [
                'name' => 'VIP',
                'duration' => '6 Months',
                'duration_days' => 180,
                'price' => 149,
                'features' => [
                    'Access to gym equipment',
                    'Locker room access',
                    'Free Wi-Fi',
                    'Unlimited guest passes',
                    'Personal training (4 sessions)',
                    'Nutrition consultation',
                    'Sauna & steam room',
                    'Priority booking',
                ],
                'popular' => false,
                'active' => true,
            ],
            [
                'name' => 'Annual',
                'duration' => '12 Months',
                'duration_days' => 365,
                'price' => 499,
                'features' => [
                    'Access to gym equipment',
                    'Locker room access',
                    'Free Wi-Fi',
                    'Unlimited guest passes',
                    'Personal training (8 sessions)',
                    'Nutrition consultation',
                    'Sauna & steam room',
                    'Priority booking',
                    '24/7 access',
                ],
                'popular' => false,
                'active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::create($plan);
        }
    }
}