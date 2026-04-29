<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $table = 'membership_plans';

    protected $fillable = [
        'name',
        'duration',
        'duration_days',
        'price',
        'features',
        'popular',
        'active',
    ];

    protected $casts = [
        'features' => 'array',
        'popular' => 'boolean',
        'active' => 'boolean',
        'price' => 'decimal:2',
    ];
}