<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Users;
use Illuminate\Support\Facades\DB;

class hasLogin
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

        if ($request->session()->get('r_code')) {
            if (Users::where('r_code', $request->session()->get('r_code'))->count() > 0) {
                return redirect('news');
            }
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}

        return $next($request);
    }
}