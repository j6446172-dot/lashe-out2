<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',      // 🔥 تغيير من customer_id إلى user_id
        'staff_id',
        'rating',
        'comment',
    ];

    // العلاقات
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // 🔥 تغيير من customer إلى user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // يمكنك إضافة هذه العلاقة كاسم بديل للعميل
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    // تعديل accessor للحصول على اسم الخدمة
    public function getServiceNameAttribute()
    {
        return $this->booking?->service_type ?? 'خدمة غير محددة';
    }

    // تعديل accessor للحصول على اسم العميل
    public function getCustomerNameAttribute()
    {
        return $this->user?->name ?? 'عميل';
    }
}