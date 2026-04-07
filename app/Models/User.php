<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول المسموح بتعبئتها (Mass Assignable)
     * أضفنا الـ role والـ phone والـ loyalty_points بناءً على الـ SRS
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',          
        'role',           
        'loyalty_points', 
    ];

    /**
     * الحقول المخفية التي لا تظهر عند تحويل الموديل لـ JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع البيانات (Casting)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}