<?php

namespace App\Http\Middleware;

use Closure;

class CronValidation
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

        if ($request->getClientIp() != "172.26.2.250") {
            return \Log::error('Tentativa de conexão: '. $request->getClientIp());
        }

        return $next($request);
    }
}
