<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsModerator
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'moderator'])) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
