<?php

namespace App\Http\Middleware;

use Closure;
use App;
use App\Model\Users;
use Illuminate\Support\Facades\DB;

class Lang
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
        if (!$request->session()->get('lang'))
        {
            $request->session()->put('lang', 'pt-br');
            App::setLocale('pt-br');
        } else {
            App::setLocale($request->session()->get('lang'));
        }

        return $next($request);
    }
}