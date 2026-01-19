<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLargeFileUploads
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Increase memory limit for large file uploads
        ini_set('memory_limit', '512M');
        
        // Increase execution time for large file uploads
        ini_set('max_execution_time', 600);
        
        // Increase input time for large file uploads
        ini_set('max_input_time', 600);
        
        // Set upload limits
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        
        return $next($request);
    }
}
