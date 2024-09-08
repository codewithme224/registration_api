<?php

namespace App\Http\Middleware;

use App\Models\AppService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValidateService
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
        $service = AppService::where(["name" => $request->service_name, "key" => $request->service_key])->first();
        if (!$service) {
            return response()->unprocessable('Invalid AppService credentials');
        }

        return $next($request);
    }
}
