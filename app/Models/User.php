<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'email',
        'password',
        'role',
        'plan',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    // Relationship with payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Relationship with schedules (as member)
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'member_id');
    }

    // Get trainer data if user is a trainer
    public function getTrainerAttribute()
    {
        if ($this->role === 'trainer') {
            return Trainer::where('email', $this->email)->first();
        }
        return null;
    }

    // Accessor for specialization (for trainers)
    public function getSpecializationAttribute()
    {
        if ($this->role === 'trainer') {
            $trainer = Trainer::where('email', $this->email)->first();
            return $trainer ? $trainer->specialization : null;
        }
        return null;
    }

    // Accessor for experience (for trainers)
    public function getExperienceAttribute()
    {
        if ($this->role === 'trainer') {
            $trainer = Trainer::where('email', $this->email)->first();
            return $trainer ? $trainer->experience : null;
        }
        return null;
    }

    // Accessor for hourly_rate (for trainers)
    public function getHourlyRateAttribute()
    {
        if ($this->role === 'trainer') {
            $trainer = Trainer::where('email', $this->email)->first();
            return $trainer ? $trainer->hourly_rate : null;
        }
        return null;
    }

    // Accessor for trainer status
    public function getTrainerStatusAttribute()
    {
        if ($this->role === 'trainer') {
            $trainer = Trainer::where('email', $this->email)->first();
            return $trainer ? $trainer->status : null;
        }
        return null;
    }

    // Relationship with trainer (one-to-one)
    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'email', 'email');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is trainer
    public function isTrainer()
    {
        return $this->role === 'trainer';
    }

    // Check if user is member
    public function isMember()
    {
        return $this->role === 'member';
    }
}