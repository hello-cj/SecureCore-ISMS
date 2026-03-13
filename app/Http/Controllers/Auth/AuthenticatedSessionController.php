<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'               => ['required', 'email'],
            'password'            => ['required'],
            'g-recaptcha-response'=> ['required'],
        ]);

        // Verify CAPTCHA
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]
        );

        $captcha = json_decode($response->body());

        if (!$captcha->success) {
            return back()->withErrors([
                'g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.',
            ]);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if account is locked
        if ($user && $user->isLocked()) {
            $minutes = ceil($user->lockoutSecondsRemaining() / 60);

            Log::channel('security')->warning('Login attempted on locked account', [
                'email'      => $request->email,
                'ip_address' => $request->ip(),
                'locked_until' => $user->locked_until,
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Account is temporarily locked due to too many failed attempts. Try again in {$minutes} minute(s).",
                ])
                ->with('lockout', true)
                ->with('lockout_seconds', $user->lockoutSecondsRemaining());
        }

        // Attempt login
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Reset failed attempts on success
            $user->resetFailedAttempts();

            Log::channel('security')->info('Successful login', [
                'user_id'    => auth()->id(),
                'email'      => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended('/dashboard');
        }

        // Failed login — increment counter
        if ($user) {
            $user->incrementFailedAttempts();

            $attemptsLeft = max(0, 5 - $user->fresh()->failed_login_attempts);

            Log::channel('security')->warning('Failed login attempt', [
                'email'           => $request->email,
                'ip_address'      => $request->ip(),
                'user_agent'      => $request->userAgent(),
                'attempts_so_far' => $user->fresh()->failed_login_attempts,
            ]);

            // Just got locked out on this attempt
            if ($user->fresh()->isLocked()) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'Too many failed attempts. Your account has been locked for 5 minutes.',
                    ])
                    ->with('lockout', true)
                    ->with('lockout_seconds', $user->fresh()->lockoutSecondsRemaining());
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Invalid credentials. {$attemptsLeft} attempt(s) remaining before lockout.",
                ]);
        }

        // Email not found — generic message (don't reveal user existence)
        Log::channel('security')->warning('Failed login attempt — email not found', [
            'email'      => $request->email,
            'ip_address' => $request->ip(),
        ]);

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Invalid credentials.',
            ]);
    }

    public function destroy(Request $request)
    {
        Log::channel('security')->info('User logged out', [
            'user_id'    => auth()->id(),
            'ip_address' => $request->ip(),
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}