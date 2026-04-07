<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // السطر السحري اللي بيشيل الأخطاء

class RoleManager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. التأكد أن المستخدم مسجل دخول باستخدام Facade
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. جلب دور المستخدم الحالي
        $authUserRole = Auth::user()->role;

        // 3. التحقق من الدور وتوجيه المستخدم إذا حاول الدخول لمكان غير مسموح
        if ($authUserRole !== $role) {
            return match($authUserRole) {
                'owner' => redirect()->route('owner.dashboard'),
                'staff' => redirect()->route('staff.dashboard'),
                'customer' => redirect()->route('dashboard'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}