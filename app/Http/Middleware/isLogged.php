<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Users;
use Carbon\Carbon;
use App\Model\Notifications;
use Illuminate\Support\Facades\DB;

class isLogged
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

        if (!$request->session()->get('r_code')) {
            $request->session()->put('url', \Request::fullUrl());
            if($request->ajax()){
                abort(400,"Usuário não autenticado.");
            }else{
                return redirect('login');
            }
            
        } else {
            if (Users::where('r_code', $request->session()->get('r_code'))->count() == 0) {
                $request->session()->put('url', \URL::current());
                if($request->ajax()){
                    abort(400,"Usuário não autenticado.");
                }else{
                    return redirect('login');
                }
            }
            
        }
		
		if ($request->get('export')) {
            if ($request->get('start_date') and $request->get('end_date')) {
                $start = new Carbon($request->get('start_date'));
                $end = new Carbon($request->get('end_date'));
                if ($start->diff($end)->days > 90) {
                    return redirect()->back()->with('error', 'Você informou um período maior que 90 dias, tente um período menor.');
                }
            } else {
                return redirect()->back()->with('error', 'Você precisa informar uma data inicial e uma data final.');
            }
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}

        return $next($request);
    }
}