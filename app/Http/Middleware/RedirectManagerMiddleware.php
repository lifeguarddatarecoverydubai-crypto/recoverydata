<?php

namespace App\Http\Middleware;

use App\Models\RedirectRule;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectManagerMiddleware
{
    /**
     * Handle an incoming request and redirect if a matching active rule exists.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Enforce canonical www.recoverydata.ae and strip accidental /public/ prefix
        $requestUri = $request->getRequestUri();
        $host = strtolower($request->getHost());
        $isNonWww = ($host === 'recoverydata.ae');
        $hasPublicPrefix = preg_match('#^/public(?:/(.*))?$#i', $requestUri, $matches);

        if ($isNonWww || $hasPublicPrefix) {
            $cleanPath = $hasPublicPrefix ? ('/'.ltrim($matches[1] ?? '', '/')) : $requestUri;
            $targetSchemeHost = (app()->environment('production') || $host === 'recoverydata.ae' || $host === 'www.recoverydata.ae')
                ? 'https://www.recoverydata.ae'
                : ($request->getScheme().'://'.$request->getHttpHost());

            return redirect()->to($targetSchemeHost.$cleanPath, 301);
        }

        // Only inspect safe read requests (GET and HEAD)
        if (! in_array($request->method(), ['GET', 'HEAD'])) {
            return $next($request);
        }

        // Never redirect critical system and admin management paths
        if ($request->is('admin') ||
            $request->is('admin/*') ||
            $request->is('api/*') ||
            $request->is('login*') ||
            $request->is('logout') ||
            $request->is('register*') ||
            $request->is('up') ||
            $request->is('_debugbar*')) {
            return $next($request);
        }

        // Never intercept static assets or source files
        $path = $request->path();
        if (preg_match('/\.(css|js|png|jpe?g|gif|svg|webp|avif|ico|woff2?|ttf|eot|map|json|xml|txt)$/i', $path)) {
            return $next($request);
        }

        // Match against active cached redirect rules
        $match = RedirectRule::matchRequest($request);

        if ($match !== null) {
            // Track SEO traffic hit asynchronously / safely
            RedirectRule::recordHitById($match['rule_id']);

            // Status 410 Content Gone
            if ($match['target_url'] === '410_GONE' || $match['status_code'] === 410) {
                abort(410, 'This page has been permanently removed.');
            }

            return redirect()->to($match['target_url'], $match['status_code']);
        }

        return $next($request);
    }
}
