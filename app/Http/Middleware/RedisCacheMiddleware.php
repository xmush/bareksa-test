<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->fullUrl();
        
        // check redis cache by url
        $cache = Redis::get($key);

        if (is_null($cache)) {

            return $next($request);
            
        } else {

            $data = json_decode($cache, FALSE);

            return response()->json(['message' => 'success', 'data' => $data, 'source' => 'cache'], 200);

        }
        
    }
}
