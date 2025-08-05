<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $apikey = $request->header('X-API-KEY');
        $secretkey = $request->header('X-SECRET-KEY');

        if (!$apikey || !$secretkey) {
            return response()->json([
                'code' => 0,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (env('APP_ACCESS_KEY') !== $apikey || env('APP_SECRET_KEY') !== $secretkey) {
            return response()->json([
                'code' => 0,
                'message' => 'Invalid credentials'
            ], 403);
        }

        return $next($request);
    }
}
