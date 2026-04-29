<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Trainer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'trainers';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'password',
        'specialization',
        'experience',
        'hourly_rate',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    // Relationship with schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'trainer_id');
    }
}