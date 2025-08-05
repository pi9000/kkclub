<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\DomainList;
use App\Models\Settings;

class DetectWhitelabel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $host = $request->getHost();

        $whitelabel = DomainList::where('domain', $host)->first();

        if (empty($whitelabel)) {
            return response()->json(['error' => 'Settings not found for this domain'], 404);
        }

        $settings = Settings::where('agent_id', $whitelabel->agent_id)->first();

        if (empty($settings)) {
            return response()->json(['error' => 'Settings not found for this domain'], 404);
        }

        config(['agent_id' => $whitelabel->agent_id]);

        app()->singleton('settings', function () use ($settings) {
            return $settings;
        });

        return $next($request);
    }
}
