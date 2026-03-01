<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use Illuminate\Support\Facades\Cache;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP and Registration Data
        Cache::put('reg_otp_' . $request->email, $otp, 600);
        session(['reg_data' => $request->only('name', 'email', 'password')]);

        // Send Email via Reusable Brevo Service
        \App\Services\BrevoService::sendEmail(
            ['email' => $request->email, 'name' => $request->name],
            'Your Registration OTP',
            view('emails.registration-otp', ['otp' => $otp])->render()
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'otp_sent',
                'message' => 'OTP sent to your email. Please verify.',
                'email' => $request->email
            ]);
        }

        return redirect()->route('register.verify-otp')->with('email', $request->email);
    }

    /**
     * Show the OTP verification view.
     */
    public function showOtpVerify()
    {
        if (!session()->has('reg_data')) {
            return redirect()->route('register');
        }

        return view('auth.otp-verify');
    }

    /**
     * Verify OTP and complete registration.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $regData = session('reg_data');

        if (!$regData) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Registration session expired.'], 422);
            }
            return redirect()->route('register')->withErrors(['otp' => 'Registration session expired.']);
        }

        $email = $regData['email'];
        $cachedOtp = Cache::get('reg_otp_' . $email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => ['otp' => ['Invalid or expired OTP.']]], 422);
            }
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // OTP Valid - Create User
        $user = User::create([
            'name' => $regData['name'],
            'email' => $regData['email'],
            'password' => Hash::make($regData['password']),
        ]);

        $user->assignRole('user'); // Assign default role

        $user->markEmailAsVerified();

        event(new Registered($user));

        Auth::login($user);

        // Clear session and cache
        Cache::forget('reg_otp_' . $email);
        session()->forget('reg_data');

        if ($request->wantsJson()) {
            return response()->json(['redirect_url' => $this->redirectToDashboard($user)->getTargetUrl()]);
        }

        return $this->redirectToDashboard($user);
    }

    protected function redirectToDashboard($user): RedirectResponse
    {
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->hasRole('manager')) {
            return redirect()->intended(route('manager.dashboard'));
        } elseif ($user->hasRole('astrologer')) {
            return redirect()->intended(route('astrologer.dashboard'));
        } else {
            return redirect()->intended(route('user.dashboard'));
        }
    }

    /**
     * Resend registration OTP.
     */
    public function resendOtp(Request $request)
    {
        $regData = session('reg_data');

        if (!$regData) {
            return response()->json(['message' => 'Registration session expired.'], 422);
        }

        $email = $regData['email'];
        $name = $regData['name'];

        // Generate new OTP
        $otp = rand(100000, 999999);

        // Store OTP in Cache (10 minutes)
        Cache::put('reg_otp_' . $email, $otp, 600);

        // Send Email
        \App\Services\BrevoService::sendEmail(
            ['email' => $email, 'name' => $name],
            'Your Registration OTP (Resent)',
            view('emails.registration-otp', ['otp' => $otp])->render()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'OTP has been resent to your email.'
        ]);
    }
}
