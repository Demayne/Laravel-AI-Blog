<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictToOwner
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access only from localhost
        if ($request->ip() !== '127.0.0.1') {
            abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }
}