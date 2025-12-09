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
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('backoffice.email')
                ->withErrors(['email' => 'Please log in to access the backoffice.']);
        }

        // Check if user is a super admin
        if (!Auth::user()->is_super_admin) {
            Auth::logout();
            return redirect()->route('backoffice.email')
                ->withErrors(['email' => 'You do not have access to the backoffice.']);
        }

        // Check if backoffice session is authenticated (code was verified)
        if (!session('backoffice_authenticated')) {
            return redirect()->route('backoffice.email')
                ->withErrors(['email' => 'Please verify your access code to continue.']);
        }

        return $next($request);
    }
}
