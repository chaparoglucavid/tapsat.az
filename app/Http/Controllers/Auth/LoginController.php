<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FailedLogins;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        notify()->success('Laravel Notify is awesome!');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = Str::lower($request->email);
        $ip = $request->ip();

        $ipAttempt = FailedLogins::firstOrCreate(
            ['email' => $email],
            ['ip_address' => $ip],
            ['attempts' => 0]
        );

        if ($ipAttempt->locked_until && now()->lessThan($ipAttempt->locked_until)) {
            return response()->json([
                'status' => false,
                'message' => 'IP müvəqqəti bloklandı',
                'blocked_in' => now()->diffInSeconds($ipAttempt->locked_until)
            ], 423);
        }

        $user = User::where('email', $email)->first();

        if ($user && $user->locked_until && now()->lessThan($user->locked_until)) {
            return response()->json([
                'status' => false,
                'message' => 'Hesab müvəqqəti bloklandı',
                'blocked_in' => now()->diffInSeconds($user->locked_until)
            ], 423);
        }

        if (Auth::attempt(['email' => $email, 'password' => $request->password])) {

            if ($user) {
                $user->update([
                    'failed_login_attempts' => 0,
                    'locked_until' => null
                ]);
            }

            $ipAttempt->update([
                'attempts' => 0,
                'locked_until' => null
            ]);

            $request->session()->regenerate();

            return response()->json([
                'status' => true,
                'redirect' => route('dashboard')
            ]);
        }

        $ipAttempt->increment('attempts');
        $ipAttempt->refresh();

        if ($ipAttempt->attempts >= 5) {
            $ipAttempt->update([
                'locked_until' => now()->addMinutes(3)
            ]);
        }

        if ($user) {
            $user->increment('failed_login_attempts');
            $user->refresh();

            if ($user->failed_login_attempts >= 5) {
                $user->update([
                    'locked_until' => now()->addMinutes(3)
                ]);

                return response()->json([
                    'status' => false,
                    'attempts_left' => 0,
                    'message' => 'Hesab bloklandı',
                    'blocked_in' => now()->diffInSeconds($user->locked_until)
                ], 423);
            }

            $attemptsLeft = 5 - $user->failed_login_attempts;
        } else {
            $attemptsLeft = null;
        }

        return response()->json([
            'status' => false,
            'message' => 'Email və ya şifrə səhvdir',
            'attempts_left' => $attemptsLeft
        ], 422);
    }



    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
