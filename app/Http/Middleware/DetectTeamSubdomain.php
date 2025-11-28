<?php

namespace App\Http\Middleware;

use App\Models\AppSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectTeamSubdomain
{
    /**
     * Handle an incoming request.
     *
     * Detects team from subdomain and injects settings into the request.
     * Supports both subdomain-based routing (team.example.com) and
     * path-based routing (example.com/{unique_url}) as fallback.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a subdomain request
        $subdomain = $this->extractSubdomain($request);

        // If there's a subdomain, resolve settings from it
        if ($subdomain) {
            $settings = AppSettings::where('subdomain_url', $subdomain)->first();

            if (!$settings) {
                abort(404, 'Team not found');
            }

            // Check if there's also a unique_url in the path
            // If so, validate that it belongs to the same team (security check)
            $uniqueUrl = $request->route('unique_url');
            if ($uniqueUrl) {
                // The unique_url must match the subdomain's team
                if ($settings->unique_url !== $uniqueUrl) {
                    abort(404, 'Team not found');
                }
            }

            // Inject the settings into the request for controllers to use
            $request->attributes->set('team_settings', $settings);
            $request->attributes->set('team_id', $settings->team_id);
            $request->attributes->set('is_subdomain', true);

            return $next($request);
        }

        // For path-based routing, check unique_url parameter
        $uniqueUrl = $request->route('unique_url');
        if ($uniqueUrl) {
            $settings = AppSettings::where('unique_url', $uniqueUrl)->first();

            if (!$settings) {
                abort(404, 'Team not found');
            }

            $request->attributes->set('team_settings', $settings);
            $request->attributes->set('team_id', $settings->team_id);
            $request->attributes->set('is_subdomain', false);

            return $next($request);
        }

        // No subdomain and no unique_url - let the request pass through
        // This allows the main app routes to work normally
        return $next($request);
    }

    /**
     * Extract subdomain from the request host.
     *
     * Examples:
     * - acme.whoopboard.com -> acme
     * - www.whoopboard.com -> null (www is ignored)
     * - whoopboard.com -> null (no subdomain)
     * - acme.staging.whoopboard.com -> acme (handles multi-level domains)
     */
    protected function extractSubdomain(Request $request): ?string
    {
        $host = $request->getHost();
        $baseDomain = config('app.base_domain', parse_url(config('app.url'), PHP_URL_HOST));

        // If no base domain configured, try to detect it
        if (!$baseDomain) {
            return null;
        }

        // Remove port if present
        $baseDomain = preg_replace('/:\d+$/', '', $baseDomain);
        $host = preg_replace('/:\d+$/', '', $host);

        // Check if host ends with base domain
        if (!str_ends_with($host, $baseDomain)) {
            return null;
        }

        // Extract the subdomain part
        $subdomain = rtrim(str_replace($baseDomain, '', $host), '.');

        // Ignore empty subdomain or www
        if (empty($subdomain) || $subdomain === 'www') {
            return null;
        }

        // Handle nested subdomains (e.g., acme.staging -> acme)
        // Take only the first part of the subdomain
        $parts = explode('.', $subdomain);
        $teamSubdomain = $parts[0];

        // Ignore common non-team subdomains
        $ignoredSubdomains = ['www', 'api', 'admin', 'mail', 'smtp', 'ftp', 'staging', 'dev', 'test'];
        if (in_array(strtolower($teamSubdomain), $ignoredSubdomains)) {
            return null;
        }

        return $teamSubdomain;
    }
}
