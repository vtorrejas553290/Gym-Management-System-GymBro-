<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_id',
        'user_id',
        'schedule_id',
        'type',
        'amount',
        'payment_date',
        'method',
        'status',
        'details',
        'admin_message',
        'gcash_number',
        'reference_number',
        'proof_image',  // ADD THIS LINE
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMemberNameAttribute()
    {
        $name = $this->user->first_name;
        if ($this->user->middle_name) {
            $name .= ' ' . $this->user->middle_name;
        }
        $name .= ' ' . $this->user->last_name;
        return $name;
    }
}