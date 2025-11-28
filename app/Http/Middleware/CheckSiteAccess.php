<?php

namespace App\Http\Middleware;

use App\Models\AppSettings;
use App\Models\SiteAccessInvite;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for subdomain requests
        if (!$request->attributes->get('is_subdomain')) {
            return $next($request);
        }

        $settings = $request->attributes->get('subdomain_settings');

        if (!$settings) {
            return $next($request);
        }

        // Refresh settings from database to get latest values
        $settings->refresh();

        // Check maintenance mode first
        if ($settings->isInMaintenanceMode()) {
            return $this->maintenanceResponse($request, $settings);
        }

        // Check if site is private
        if ($settings->isPrivate()) {
            // Allow access verification routes
            if ($this->isAccessVerificationRoute($request)) {
                return $next($request);
            }

            // Check if user has valid access
            if (!$this->hasValidAccess($request, $settings)) {
                return $this->accessDeniedResponse($request, $settings);
            }
        }

        return $next($request);
    }

    /**
     * Check if the current route is an access verification route.
     */
    protected function isAccessVerificationRoute(Request $request): bool
    {
        $path = $request->path();
        return in_array($path, [
            'access',
            'access/request-code',
            'access/verify',
        ]);
    }

    /**
     * Check if the user has valid access to the site.
     */
    protected function hasValidAccess(Request $request, AppSettings $settings): bool
    {
        // Check session for verified access
        $verifiedEmail = $request->session()->get('site_access_email');
        $verifiedTeamId = $request->session()->get('site_access_team_id');

        if ($verifiedEmail && $verifiedTeamId == $settings->team_id) {
            // Verify the invite still exists and is valid
            $invite = SiteAccessInvite::forEmailAndTeam($verifiedEmail, $settings->team_id)
                ->first();

            if ($invite && $invite->hasValidAccess()) {
                return true;
            }

            // Clear invalid session data
            $request->session()->forget(['site_access_email', 'site_access_team_id']);
        }

        return false;
    }

    /**
     * Return maintenance mode response.
     */
    protected function maintenanceResponse(Request $request, AppSettings $settings): Response
    {
        return response()->view('public.maintenance', [
            'settings' => $settings,
            'message' => $settings->maintenance_message,
            'endsAt' => $settings->maintenance_ends_at,
        ], 503);
    }

    /**
     * Return access denied response (redirect to login).
     */
    protected function accessDeniedResponse(Request $request, AppSettings $settings): Response
    {
        // Store the intended URL
        $request->session()->put('site_access_intended_url', $request->fullUrl());

        return redirect()->route('public.access.login');
    }
}
