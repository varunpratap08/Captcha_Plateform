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
        // Force JSON accept header
        $request->headers->set('Accept', 'application/json');
        
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }
        
        // Get the response
        $response = $next($request);
        
        // Only modify non-JSON responses
        if (!$response instanceof \Illuminate\Http\JsonResponse) {
            $response = response()->json([
                'status' => 'error',
                'message' => 'Invalid response format',
                'debug' => [
                    'expected' => 'application/json',
                    'received' => $response->headers->get('Content-Type')
                ]
            ], 500);
        }
        
        // Add CORS headers if not already present
        $corsHeaders = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
            'Content-Type' => 'application/json',
        ];
        
        foreach ($corsHeaders as $key => $value) {
            if (!$response->headers->has($key)) {
                $response->headers->set($key, $value);
            }
        }
        
        return $response;
    }
}
