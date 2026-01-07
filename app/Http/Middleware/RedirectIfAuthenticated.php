<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (auth()->check()) {

            $user = auth()->user();

            if ($user->isAdmin()) {
                return redirect(route('admin.dashboard'));
            } else if ($user->isAstrologer()) {
                return redirect(route('astrologer.dashboard'));
            } else if ($user->isUser()) {
                return redirect(route('user.dashboard'));
            }

            return redirect('/');
        }

        return $next($request);
    }
}
