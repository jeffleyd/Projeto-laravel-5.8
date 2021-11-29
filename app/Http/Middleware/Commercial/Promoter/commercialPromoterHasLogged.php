<?php

namespace App\Http\Middleware\Commercial\Promoter;

use Closure;
use App\Model\PromoterUsers;
use App\Model\Notifications;
use Illuminate\Support\Facades\DB;

class commercialPromoterHasLogged
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

        if ($request->session()->get('promoter_data')) {
            if (PromoterUsers::where('id', $request->session()->get('promoter_data')->id)->count() > 0) {
                return redirect('/promotor/dashboard');
            }
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}

        return $next($request);
    }
}