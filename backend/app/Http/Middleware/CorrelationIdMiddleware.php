<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class CorrelationIdMiddleware
{
    public function handle($request, Closure $next)
    {
        $correlationId = $request->header('X-Correlation-ID') ?? Str::ulid()->toString();
        app()->instance('correlationId', $correlationId);

        return $next($request)->header('X-Correlation-ID', $correlationId);
    }
}
