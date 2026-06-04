<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role',
        'total_bookings', 'last_eye_shape', 'last_style_preference',
        'last_lash_duration', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== العلاقات ==========
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function staffBookings()
    {
        return $this->hasMany(Booking::class, 'staff_id');
    }

    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function staffReviews()
    {
        return $this->hasMany(Review::class, 'staff_id');
    }

    // ========== الصلاحيات ==========
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    // ========== نظام النقاط والخصم الاحترافي ==========
    
    /**
     * الحصول على رصيد النقاط الحالي
     */
    public function getPointsAttribute(): int
    {
        return $this->loyaltyPoints ? $this->loyaltyPoints->points : 0;
    }
    
    /**
     * إضافة نقاط للعميلة (بعد إتمام الحجز)
     */
    public function addPoints(int $points): void
    {
        if ($this->loyaltyPoints) {
            $this->loyaltyPoints->increment('points', $points);
        } else {
            $this->loyaltyPoints()->create(['points' => $points]);
        }
    }
    
    /**
     * خصم نقاط من العميلة (عند استخدام الخصم)
     */
    public function deductPoints(int $points): void
    {
        if ($this->loyaltyPoints) {
            $this->loyaltyPoints->decrement('points', $points);
        }
    }
    
    /**
     * هل العميلة مؤهلة للحصول على خصم؟
     * (تحتاج 50 نقطة على الأقل)
     */
    public function isEligibleForDiscount(): bool
    {
        return $this->points >= 50;
    }
    
    /**
     * قيمة الخصم المستحق (كل 50 نقطة = 5 دنانير)
     * الحد الأقصى 10 دنانير في الحجز الواحد
     */
    public function getDiscountAmount(): float
    {
        if (!$this->isEligibleForDiscount()) {
            return 0;
        }
        
        $maxDiscountPerBooking = 10; // أقصى خصم 10 دنانير في الحجز الواحد
        $calculatedDiscount = floor($this->points / 50) * 5;
        
        return min($calculatedDiscount, $maxDiscountPerBooking);
    }
    
    /**
     * عدد النقاط التي سيتم خصمها عند استخدام الخصم
     * (لكل 5 دنانير = 50 نقطة)
     */
    public function getPointsToDeduct(): int
    {
        if (!$this->isEligibleForDiscount()) {
            return 0;
        }
        
        $discountAmount = $this->getDiscountAmount();
        return ($discountAmount / 5) * 50;
    }
    
    /**
     * تطبيق الخصم (يخصم فقط النقاط المستحقة حسب قيمة الخصم)
     * مثال: 120 نقطة → خصم 10 دنانير → يخصم 100 نقطة → يتبقى 20 نقطة
     */
    public function applyDiscount(): float
    {
        $discountAmount = $this->getDiscountAmount();
        $pointsToDeduct = $this->getPointsToDeduct();
        
        if ($pointsToDeduct > 0 && $this->points >= $pointsToDeduct) {
            $this->deductPoints($pointsToDeduct);
            return $discountAmount;
        }
        
        return 0;
    }

    // ========== دوال مساعدة ==========
    public function getGreetingAttribute(): string
    {
        $name = $this->name;
        $hour = now()->hour;
        
        if ($hour < 12) {
            return "صباح الخير {$name} 🌞";
        }
        
        if ($hour < 18) {
            return "مساء الخير {$name} 🌤️";
        }
        
        return "مساء النور {$name} 🌙";
    }

    public function getAvatarAttribute(): string
    {
        return $this->avatar ?? 'https://ui-avatars.com/api/?background=ec4899&color=fff&name=' . urlencode($this->name);
    }
}