<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role',
        'total_bookings', 'last_eye_shape', 'last_style_preference',
        'last_lash_duration', 'avatar',
        'default_latitude', 'default_longitude', 'default_address',
        'default_building_number', 'default_apartment',
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

    // ========== نظام النقاط والخصم ==========
    
    /**
     * الحصول على رصيد النقاط الحالي
     */
    public function getPointsAttribute(): int
    {
        // استخدام DB مباشرة لضمان الحصول على القيمة الصحيحة
        $loyalty = DB::table('loyalty_points')->where('user_id', $this->id)->first();
        return $loyalty ? $loyalty->points : 0;
    }
    
    /**
     * إضافة نقاط للعميلة
     */
    public function addPoints(int $points): void
    {
        // استخدام DB مباشرة بدل Eloquent لضمان العمل
        $existing = DB::table('loyalty_points')->where('user_id', $this->id)->first();
        
        if ($existing) {
            DB::table('loyalty_points')->where('user_id', $this->id)->increment('points', $points);
        } else {
            DB::table('loyalty_points')->insert([
                'user_id' => $this->id,
                'points' => $points,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
    
    /**
     * خصم نقاط من العميلة
     */
    public function deductPoints(int $points): void
    {
        DB::table('loyalty_points')->where('user_id', $this->id)->decrement('points', $points);
    }
    
    /**
     * هل العميلة مؤهلة للحصول على خصم؟
     */
    public function isEligibleForDiscount(): bool
    {
        return $this->points >= 50;
    }
    
    /**
     * قيمة الخصم المستحق (15% من السعر)
     */
    public function getDiscountAmount($basePrice = null): float
    {
        if (!$this->isEligibleForDiscount() || !$basePrice) {
            return 0;
        }
        
        // خصم 15% مرة واحدة فقط
        $discountValue = $basePrice * 0.15;
        
        // الحد الأقصى للخصم 50% من السعر
        $maxDiscount = $basePrice * 0.5;
        
        return min($discountValue, $maxDiscount);
    }
    
    /**
     * عدد النقاط التي سيتم خصمها (50 نقطة فقط)
     */
    public function getPointsToDeduct(): int
    {
        if (!$this->isEligibleForDiscount()) {
            return 0;
        }
        
        return 50;
    }
    
    /**
     * تطبيق الخصم
     */
    public function applyDiscount($basePrice = null): float
    {
        if (!$basePrice) {
            return 0;
        }
        
        $discountAmount = $this->getDiscountAmount($basePrice);
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