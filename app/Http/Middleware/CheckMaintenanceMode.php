<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($request->is('api/*')) {
                // Check App Maintenance
                $isAppMaintenance = Setting::where('key', 'app_maintenance')->value('value');
                if ($isAppMaintenance === '1' || strtolower($isAppMaintenance) === 'true') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Service Unavailable. System is under maintenance.',
                        'code' => 503
                    ], 503);
                }
            } else {
                // Check Web Maintenance
                // Exclude admin routes so we don't lock out the admin
                if (!$request->is('admin*') && !$request->is('login')) {
                    $isWebMaintenance = Setting::where('key', 'web_maintenance')->value('value');
                    if ($isWebMaintenance === '1' || strtolower($isWebMaintenance) === 'true') {
                        return response()->view('errors.maintenance', [], 503);
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore DB errors if settings table doesn't exist yet (e.g. during fresh migrations)
        }

        return $next($request);
    }
}
