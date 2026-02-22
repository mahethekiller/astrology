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
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json(['redirect_url' => $this->redirectToDashboard()->getTargetUrl()]);
        }

        // return redirect()->intended(route('dashboard', absolute: false));
        // Custom redirect based on user role
        return $this->redirectToDashboard();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user && $user->hasRole('astrologer')) {
            $user->astrologerProfile()->update([
                'is_chat_online' => false,
                'is_call_online' => false,
                'is_online' => false,
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($user) {
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.login');
            } elseif ($user->hasRole('manager')) {
                return redirect()->route('manager.login');
            } elseif ($user->hasRole('astrologer')) {
                return redirect()->route('astrologer.login');
            }
        }

        return redirect('/');
    }

    protected function redirectToDashboard(): RedirectResponse
    {
        $user = Auth::user();

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
}
