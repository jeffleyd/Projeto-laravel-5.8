<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\SacClient;
use App\Model\Notifications;
use Illuminate\Support\Facades\DB;

class sacClientHasLogin
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

        if (!$request->session()->get('sac_client_id')) {
            $request->session()->put('url', \URL::current());
            return redirect('/suporte');
        } else {
            if (SacClient::where('id', $request->session()->get('sac_client_id'))->count() == 0) {
                $request->session()->put('url', \URL::current());
                return redirect('/suporte');
            }            
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}

        return $next($request);
    }
}