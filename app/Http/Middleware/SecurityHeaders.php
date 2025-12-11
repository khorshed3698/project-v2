<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // New Permissions-Policy header
        $response->headers->set('Permissions-Policy', 'microphone=*');
        
        // Legacy Feature-Policy header for older browsers
        $response->headers->set('Feature-Policy', 'microphone *');
        
        return $response;
    }
}