<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Force JSON accept header for API requests
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return response('', 204)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN')
                ->header('Access-Control-Allow-Credentials', 'true');
        }
        
        // Get the response
        $response = $next($request);
        
        // Ensure JSON response for API routes
        if ($request->is('api/*')) {
            // If the response is not already JSON
            if (!$response instanceof \Illuminate\Http\JsonResponse) {
                $content = $response->getContent();
                $decoded = json_decode($content, true);
                
                $response = response()->json(
                    is_array($decoded) ? $decoded : ['data' => $content],
                    $response->getStatusCode(),
                    [],
                    JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                );
            }
            
            // Set JSON content type header
            $response->headers->set('Content-Type', 'application/json');
            
            // Add CORS headers to all API responses
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }
        
        return $response;
    }
}
