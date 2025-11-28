<?php

namespace App\Http\Middleware;

use App\Models\AppSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubdomainRouting
{
    /**
     * Handle subdomain-based routing.
     *
     * When a request comes from a subdomain (e.g., demo.wbapp.com),
     * this middleware detects the team and makes settings available
     * to the application without requiring the unique_url in the path.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = $this->extractSubdomain($request);

        if ($subdomain) {
            $settings = AppSettings::where('subdomain_url', $subdomain)->first();

            if ($settings) {
                // Store settings in request for controllers
                $request->attributes->set('team_settings', $settings);
                $request->attributes->set('team_id', $settings->team_id);
                $request->attributes->set('is_subdomain', true);
                $request->attributes->set('subdomain', $subdomain);
            }
        }

        return $next($request);
    }

    /**
     * Extract subdomain from the request host.
     */
    protected function extractSubdomain(Request $request): ?string
    {
        $host = $request->getHost();
        $baseDomain = config('app.base_domain', parse_url(config('app.url'), PHP_URL_HOST));

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

        // Take only the first part of nested subdomains
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
