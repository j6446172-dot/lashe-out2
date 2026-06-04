<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تأكيد الحجز</title>
</head>
<body style="font-family: Arial, sans-serif; background: #F3EDE6; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #B08D57, #9a7848); padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0;">✨ Lashe Out ✨</h1>
            <p style="color: rgba(255,255,255,0.8); margin-top: 10px;">تأكيد حجزك</p>
        </div>
        
        {{-- Content --}}
        <div style="padding: 30px; text-align: right;">
            <p style="font-size: 18px; color: #2B1E1A;">مرحباً {{ $user->name }},</p>
            <p>تم تأكيد حجزك بنجاح! 🎉</p>
            
            <div style="background: #F3EDE6; padding: 15px; border-radius: 16px; margin: 20px 0;">
                <h3 style="color: #B08D57; margin-bottom: 15px;">📋 تفاصيل الحجز:</h3>
                <p><strong>الخدمة:</strong> 
                    @if($booking->service_type == 'classic') Classic Set
                    @elseif($booking->service_type == 'wet') Wet Set
                    @elseif($booking->service_type == 'wispy') Wispy Set
                    @elseif($booking->service_type == 'volume') Volume Set
                    @elseif($booking->service_type == 'anime') Anime Set
                    @else {{ $booking->service_type }}
                    @endif
                </p>
                <p><strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                <p><strong>الوقت:</strong> {{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</p>
                <p><strong>الموقع:</strong> {{ $booking->location == 'salon' ? 'في الصالون' : 'خدمة منزلية' }}</p>
                <p><strong>السعر:</strong> {{ $booking->price }} د.أ</p>
            </div>
            
            <p style="color: #7C8574; font-size: 14px;">يمكنك عرض تفاصيل الحجز من خلال حسابك في الموقع.</p>
            
            <a href="{{ route('customer.bookings.show', $booking->id) }}" 
               style="display: inline-block; background: #B08D57; color: white; padding: 12px 24px; text-decoration: none; border-radius: 12px; margin-top: 20px;">
                عرض تفاصيل الحجز
            </a>
        </div>
        
        {{-- Footer --}}
        <div style="background: #2B1E1A; padding: 20px; text-align: center;">
            <p style="color: #7C8574; font-size: 12px;">&copy; 2024 Lashe Out - جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>