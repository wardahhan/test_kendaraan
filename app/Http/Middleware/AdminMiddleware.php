<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized: Your role is not allowed to access this resource.');
        }

        return $next($request);
    }
}
