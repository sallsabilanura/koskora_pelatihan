<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha_v3.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
                
                $result = $response->json();
                
                if (!$result || !isset($result['success']) || !$result['success']) {
                    $fail('Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
                }
                
                // reCAPTCHA v3 returns a score (1.0 is very likely a good interaction, 0.0 is very likely a bot)
                if (isset($result['score']) && $result['score'] < 0.5) {
                    $fail('Aktivitas mencurigakan terdeteksi (skor reCAPTCHA terlalu rendah).');
                }
            }],
        ], [
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA diperlukan.',
        ]);

        // Proteksi Brute-Force (Rate Limiting)
        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Keamanan aktif. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::clear($throttleKey);
            
            // Check if the logged in user is actually an admin
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();

                return redirect()->intended('/admin/dashboard');
            } else {
                // If not admin, logout and redirect back
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Access denied. You are not an admin.',
                ])->onlyInput('email');
            }
        }

        // Catat percobaan gagal
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout the admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
