<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\SacAuthorized;
use App\Model\Notifications;
use Illuminate\Support\Facades\DB;

class sacAuthorizedHasLogin
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

        if (!$request->session()->get('sac_authorized_id')) {
            $request->session()->put('url', \URL::current());
            return redirect('/autorizada');
        } else {
            if (SacAuthorized::where('id', $request->session()->get('sac_authorized_id'))->count() == 0) {
                $request->session()->put('url', \URL::current());
                return redirect('/autorizada');
            }
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}

        return $next($request);
    }
}