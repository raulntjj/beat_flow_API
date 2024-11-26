<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $model) {
        $resource = $request->route($model);

        if (!$resource || Auth::id() !== $resource->user_id) {
            return response()->json(['status' => 'failed', 'response' => 'Unauthorized'], 403);
        }
    
        return $next($request);
    }    
}
