<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApproverMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            Log::warning('ApproverMiddleware: User not logged in.');
            abort(403, 'Unauthorized: User not logged in.');
        }

        $role = $user->role ?? 'undefined';
        Log::info('ApproverMiddleware: User ID = ' . $user->id . ', Role = ' . $role);

        $allowedRoles = ['admin', 'approver1', 'approver2'];

        if (in_array($role, $allowedRoles)) {
            Log::info('ApproverMiddleware: Authorized role detected. Proceeding...');
            return $next($request);
        }

        Log::warning("ApproverMiddleware: Unauthorized role '$role' attempted to access protected route.");
        abort(403, 'Unauthorized: Your role is not allowed to access this resource.');
    }
}
