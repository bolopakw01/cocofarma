<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        Log::info('RoleMiddleware check', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'user_role' => Auth::check() ? Auth::user()->role : null,
            'required_roles' => $roles,
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson()
        ]);

        if (!Auth::check()) {
            Log::warning('RoleMiddleware: User not authenticated, redirecting to login');
            return redirect()->route('backoffice.login');
        }

        $user = Auth::user();
        
        // Check if user has one of the required roles
        if (!in_array($user->role, $roles)) {
            Log::warning('RoleMiddleware: User role not authorized', [
                'user_role' => $user->role,
                'required_roles' => $roles
            ]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Anda tidak memiliki akses ke fitur ini.'
                ], 403);
            }
            abort(403, 'Unauthorized. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
