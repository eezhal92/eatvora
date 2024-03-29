<?php

namespace App\Http\Middleware;

use Closure;

class EatvoraAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()) {
            return redirect('/');
        }

        $authenticatedAndAdmin = $request->user() && $request->user()->is_admin;

        if (!$authenticatedAndAdmin) {
            if ($request->isJson()) {
                return response()->json([], 401);
            }

            return redirect('/');
        };

        return $next($request);
    }
}
