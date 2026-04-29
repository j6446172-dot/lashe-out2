<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'staff_id',
    'service_type',
    'eye_shape',
    'style_preference',
    'booking_date',
    'booking_time',
    'location',
    'price',        
    'status',
    'in_queue',
    'queue_position',
];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
   
}
