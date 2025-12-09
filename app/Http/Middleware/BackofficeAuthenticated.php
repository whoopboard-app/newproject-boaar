<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BackofficeAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin user is authenticated using the admin guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('backoffice.email')
                ->withErrors(['email' => 'Please log in to access the backoffice.']);
        }

        // Check if backoffice session is authenticated (code was verified)
        if (!session('backoffice_authenticated')) {
            return redirect()->route('backoffice.email')
                ->withErrors(['email' => 'Please verify your access code to continue.']);
        }

        return $next($request);
    }
}
