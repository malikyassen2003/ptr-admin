<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_admin) {  // or whatever column you use
            return $next($request);
        }

        abort(403, 'Unauthorized. Admin access only.');
        // or: return redirect()->route('login');
    }
}