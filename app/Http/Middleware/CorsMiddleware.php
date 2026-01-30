<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Runtime CORS middleware (fallback) — echoes approved origins and supports Vercel previews.
     */
    public function handle(Request $request, Closure $next)
    {
        // Build allowed origins from environment (comma-separated) with sensible defaults
        $allowedOrigins = array_filter(array_map('trim', explode(',', env('FRONTEND_URLS', 'http://localhost:5173,http://127.0.0.1:5173,http://localhost:3000,http://127.0.0.1:3000,https://resume-portfolio-platform.vercel.app'))));

        $origin = $request->header('Origin');

        // Determine origin host if present
        $originHost = $origin ? parse_url($origin, PHP_URL_HOST) : null;

        // Allow if origin exactly matches list OR is a vercel.app subdomain (preview deployments)
        $isAllowed = $origin && (in_array($origin, $allowedOrigins, true) || ($originHost && str_ends_with($originHost, '.vercel.app')) || preg_match('/^https?:\/\/([a-zA-Z0-9-]+\.)*vercel\.app$/', $origin));

        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            if ($isAllowed) {
                return response('', 204)
                    ->header('Access-Control-Allow-Origin', $origin)
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                    ->header('Access-Control-Allow-Credentials', 'true')
                    ->header('Access-Control-Max-Age', '3600');
            }

            return response('', 204);
        }

        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            // Report the exception so it's captured in logs
            report($e);

            // If origin is allowed, return a JSON 500 response with CORS headers
            if ($isAllowed) {
                return response()->json(['message' => 'Internal Server Error'], 500)
                    ->header('Access-Control-Allow-Origin', $origin)
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                    ->header('Access-Control-Allow-Credentials', 'true')
                    ->header('Access-Control-Max-Age', '3600');
            }

            // If origin not allowed, rethrow so normal handler handles it
            throw $e;
        }

        if ($isAllowed) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '3600');
        }

        return $response;
    }
}
