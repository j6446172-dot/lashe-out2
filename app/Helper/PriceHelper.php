<?php

namespace App\Helpers;

class PriceHelper
{
    public static function calculatePrice($originalPrice, $duration)
    {
        switch ($duration) {
            case 'one_time':
                return $originalPrice * 0.5; // 50%
            case 'weekly':
                return $originalPrice * 0.65; // 65%
            case 'monthly':
            default:
                return $originalPrice; // 100%
        }
    }
}