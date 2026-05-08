<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'staff_id',
        'leave_type',
        'duration_type',
        'start_date',
        'end_date',
        'hours',
        'start_time',
        'end_time',
        'reason',
        'status',
        'admin_notes',
        'reviewed_at'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime'
    ];
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    
    public function getDaysCountAttribute()
    {
        if ($this->duration_type == 'hours') {
            return null;
        }
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
    
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'pending' => '⏳ قيد المراجعة',
            'approved' => '✅ تمت الموافقة',
            'rejected' => '❌ مرفوضة',
            default => '❓ غير معروف'
        };
    }
    
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => '#f59e0b',
            'approved' => '#10b981',
            'rejected' => '#ef4444',
            default => '#6b7280'
        };
    }
    
    public function getDisplayTextAttribute()
    {
        if ($this->duration_type == 'hours') {
            $startTime = substr($this->start_time, 0, 5);
            $endTime = substr($this->end_time, 0, 5);
            return "{$this->start_date->format('d/m/Y')} من {$startTime} إلى {$endTime} ({$this->hours} ساعة)";
        }
        
        $start = $this->start_date->format('d/m/Y');
        $end = $this->end_date->format('d/m/Y');
        $days = $this->days_count;
        return "{$start} → {$end} ({$days} " . ($days == 1 ? 'يوم' : 'أيام') . ")";
    }
}