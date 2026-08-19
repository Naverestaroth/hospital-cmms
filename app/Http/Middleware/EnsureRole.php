<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! empty($roles) && ! $user->hasRole(...$roles)) {
            // Special handling if developer mode is active
            if (in_array('developer', $roles, true) && $user->isDeveloper()) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            abort(403, 'Anda tidak memiliki otorisasi untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
