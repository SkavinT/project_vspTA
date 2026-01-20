<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403);
    }
}
