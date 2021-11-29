<?php

namespace App\Http\Middleware\Commercial\Promoter;

use Closure;
use App\Model\PromoterUsers;
use App\Model\Notifications;
use Illuminate\Support\Facades\DB;

class commercialPromoterHasLogin
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

        if (!$request->session()->get('promoter_data')) {
            $request->session()->put('url', \URL::current());
            return redirect('/promotor/login');
        } else {
            if (PromoterUsers::where('id', $request->session()->get('promoter_data')->id)->count() == 0) {
                $request->session()->put('url', \URL::current());
                return redirect('/promotor/login');
            }
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}

        return $next($request);
    }
}