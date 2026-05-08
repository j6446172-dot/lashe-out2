<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'staff_id',
        'month',
        'year',
        'base_salary',
        'deduction',
        'bonus',
        'net_salary',
        'notes',
        'is_paid',
        'paid_at'
    ];
    
    protected $casts = [
        'is_paid' => 'boolean',
        'paid_at' => 'date'
    ];
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    
    // Accessor لعرض اسم الشهر
    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];
        return $months[$this->month] ?? '';
    }
}