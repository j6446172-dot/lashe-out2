<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول المسموح بتعبئتها
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'loyalty_points',
        'total_bookings',
        'loyalty_discount_used',
        'last_eye_shape',
        'last_style_preference',
        'last_lash_duration',
        'avatar',
        'base_salary',
        'bonus',
        'deduction',
        'finance_password',
    ];

    /**
     * الحقول المخفية
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== العلاقات الأساسية ==========

    /**
     * علاقة العميل بحجوزاته
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * علاقة الموظفة بالحجوزات المخصصة لها
     */
    public function staffBookings()
    {
        return $this->hasMany(Booking::class, 'staff_id');
    }

    /**
     * علاقة العميل بنقاط الولاء
     */
    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    /**
     * علاقة العميل بطابور الانتظار
     */
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    /**
     * ✅ علاقة العميل بتقييماته
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * ✅ علاقة الموظفة بالتقييمات التي حصلت عليها
     */
    public function staffReviews()
    {
        return $this->hasMany(Review::class, 'staff_id');
    }

    // ========== دوال التحقق من الصلاحيات ==========

    /**
     * هل المستخدم عميل؟
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * هل المستخدم موظفة؟
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * هل المستخدم مالك؟
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    // ========== نظام نقاط الولاء ==========

    /**
     * الحصول على نقاط الولاء (بديل للعمود)
     */
    public function getLoyaltyPointsAttribute(mixed $value): int  // ✅ تم إصلاح التحذير
    {
        return (int)($value ?? 0);
    }

    /**
     * إضافة نقاط ولاء
     */
    public function addLoyaltyPoints(int $points): void
    {
        $this->increment('loyalty_points', $points);
    }

    /**
     * خصم نقاط ولاء
     */
    public function deductLoyaltyPoints(int $points): void
    {
        $this->decrement('loyalty_points', $points);
    }

    // ========== نظام الخصم التلقائي ==========

    /**
     * عدد الحجوزات المكتملة
     */
    public function getCompletedBookingsCountAttribute(): int
    {
        return $this->bookings()->where('status', 'completed')->count();
    }

    /**
     * عدد الحجوزات المتبقية للخصم القادم
     */
    public function getNextDiscountBookingAttribute(): int
    {
        $completed = $this->completed_bookings_count;
        
        // إذا كان العدد 0 أو ليس مضاعفاً للـ 5
        if ($completed < 5) {
            return 5 - $completed;
        }
        
        // إذا كان العدد مضاعفاً للـ 5
        if ($completed % 5 == 0) {
            return 0; // الحجز الحالي عليه خصم
        }
        
        // حساب المتبقي للوصول للمضاعف التالي
        $nextMultiple = ceil(($completed + 1) / 5) * 5;
        return $nextMultiple - $completed;
    }

    /**
     * نسبة الخصم المستحقة
     */
    public function getDiscountPercentageAttribute(): int
    {
        $completed = $this->completed_bookings_count;
        
        // خصم 15% فقط عند إتمام 5، 10، 15، 20 حجز...
        if ($completed >= 5 && $completed % 5 == 0 && $this->loyalty_discount_used == 0) {
            return 15;
        }
        
        return 0;
    }

    /**
     * هل يمكن تطبيق الخصم على هذا الحجز؟
     */
    public function canApplyDiscount(): bool
    {
        $completed = $this->completed_bookings_count;
        return $completed >= 5 && $completed % 5 == 0 && $this->loyalty_discount_used == 0;
    }

    /**
     * تطبيق الخصم (يتم استدعاؤه عند استخدام الخصم)
     */
    public function applyDiscount(): void
    {
        $this->increment('loyalty_discount_used');
    }

    // ========== دوال مساعدة ==========

    /**
     * الحصول على اسم المستخدم مع اللقب المناسب
     */
    public function getGreetingAttribute(): string
    {
        $name = $this->name;
        $hour = now()->hour;
        
        if ($hour < 12) {
            return "صباح الخير {$name} 🌞";
        } elseif ($hour < 18) {
            return "مساء الخير {$name} 🌤️";
        } else {
            return "مساء النور {$name} 🌙";
        }
    }

    /**
     * الحصول على رابط صورة المستخدم
     */
    public function getAvatarAttribute(): string
    {
        return $this->avatar ?? 'https://ui-avatars.com/api/?background=ec4899&color=fff&name=' . urlencode($this->name);
    }
}