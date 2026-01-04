<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isManager() && !auth()->user()->isAdmin())) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
