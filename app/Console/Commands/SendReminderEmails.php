<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\BookingReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    /**
     * اسم الأمر (اللي حتنادي فيه)
     */
    protected $signature = 'reminders:send';

    /**
     * وصف الأمر
     */
    protected $description = 'إرسال تذكيرات للمواعيد القادمة';

    /**
     * تنفيذ الأمر
     */
    public function handle()
    {
        // جلب الحجوزات المؤكدة ليوم الغد ولم يتم إرسال تذكير لها بعد
        $tomorrowBookings = Booking::where('booking_date', now()->addDay()->toDateString())
            ->where('status', 'confirmed')
            ->where('reminder_sent', false)
            ->get();

        $count = 0;

        foreach ($tomorrowBookings as $booking) {
            // التحقق من وجود مستخدم لهذا الحجز
            if ($booking->user && $booking->user->email) {
                Mail::to($booking->user->email)->send(new BookingReminderMail($booking));
                $booking->update(['reminder_sent' => true]);
                $count++;
                $this->info("✅ تم إرسال تذكير إلى: {$booking->user->email}");
            }
        }

        $this->info("📧 تم إرسال {$count} تذكير بنجاح");
    }
}