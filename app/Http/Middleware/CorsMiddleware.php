<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class CorsMiddleware
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
        $headers = [
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, X-2N-Tool'
        ];

        // Request origin
        $origin = $request->header('Origin');

        // Set approved origins in the environment file
        $allowed = array_map('trim', explode(',', env('ALLOWED_ORIGINS', '')));

        // Allow request origin if exists in list
        if (in_array($origin, $allowed)) {
            $headers['Access-Control-Allow-Origin'] = $origin;
        }

        // Preflight response
        if ($request->isMethod('options')) {
            return response('OK', 200)->withHeaders($headers);
        }

        // Execute route and attach headers to response
        $response = $next($request);

        // BinaryFileResponse doesn't support the 'withHeaders' method
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        return $response;
    }
}
