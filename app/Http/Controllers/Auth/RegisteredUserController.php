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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user'); // Assign default role

        event(new Registered($user));

        Auth::login($user);

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
}
