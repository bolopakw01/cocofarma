<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('AdminAuth middleware check', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'is_ajax' => $request->ajax()
        ]);

        if (!Auth::check()) {
            \Illuminate\Support\Facades\Log::warning('AdminAuth: User not authenticated, redirecting to login');
            return redirect()->route('backoffice.login');
        }

        return $next($request);
    }
}
