<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAppToken
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
        if($request->input('api_key') == env("API_KEY"))
            return $next($request);
        else
            return Response()->json([
                'Unauthenticated'
            ], 401);
    }
}
