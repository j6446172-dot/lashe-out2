<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- التعديل الخاص بتوجيه المستخدم حسب الدور (Role-Based Redirect) ---
        
        $userRole = Auth::user()->role;

        return match ($userRole) {
            'owner'    => redirect()->intended(route('owner.dashboard')),
            'staff'    => redirect()->intended(route('staff.dashboard')),
            'customer' => redirect()->intended(route('dashboard')), // لوحة الزبائن الافتراضية
            default    => redirect()->intended(route('dashboard')),
        };
        
        // ------------------------------------------------------------------
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}