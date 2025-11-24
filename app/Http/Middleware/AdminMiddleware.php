<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Responses\Auth\AuthResponse;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            Log::warning('Unauthenticated admin access attempt', [
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);
            return AuthResponse::error('errors.auth.unauthorized', 401);
        }

        $adminUserId = config('auth_settings.admin.user_id');

        if (Auth::user()->user_id !== $adminUserId) {
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);
            return AuthResponse::error('errors.security.suspicious_activity', 403);
        }

        // Check for suspicious activity
        if ($this->isSuspiciousActivity($request)) {
            Log::warning('Suspicious admin activity detected', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);
            return AuthResponse::error('errors.security.suspicious_activity', 403);
        }

        return $next($request);
    }

    private function isSuspiciousActivity(Request $request): bool
    {
        // Implement your suspicious activity detection logic here
        // Example: Check for unusual IP, time of access, etc.
        return false;
    }
}
