<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إلغاء حجز</title>
</head>
<body style="font-family: 'Tajawal', sans-serif; background-color: #F3EDE6; padding: 20px;">
    <div style="max-width: 500px; margin: auto; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <div style="background: linear-gradient(135deg, #dc2626, #b91c1c); padding: 20px; text-align: center;">
            <h1 style="color: white; margin: 0;">❌ تم إلغاء حجزك</h1>
        </div>
        
        <div style="padding: 25px; text-align: right;">
            <p style="font-size: 16px; color: #2B1E1A;">عزيزتي {{ $user->name }},</p>
            <p style="color: #7C8574; line-height: 1.6;">تم إلغاء حجزك للخدمة <strong>{{ $booking->service_type }}</strong> بتاريخ <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</strong> الساعة <strong>{{ $booking->booking_time }}</strong>.</p>
            <p style="color: #7C8574;">عذراً للإزعاج، يمكنك حجز موعد آخر مناسب من خلال حسابك.</p>
            
            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('customer.bookings.step1') }}" style="background: #B08D57; color: #F3EDE6; padding: 12px 25px; border-radius: 30px; text-decoration: none; display: inline-block;">
                    حجز موعد جديد
                </a>
            </div>
        </div>
        
        <div style="background: #F3EDE6; padding: 15px; text-align: center;">
            <p style="color: #7C8574; font-size: 12px;">&copy; {{ date('Y') }} لاش أوت - جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>