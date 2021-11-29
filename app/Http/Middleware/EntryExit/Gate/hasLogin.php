<?php

namespace App\Http\Middleware\EntryExit\Gate;

use App\Model\LogisticsEntryExitSecurityGuard;
use Closure;
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

        if ($request->session()->get('security_guard_data')) {
            if (LogisticsEntryExitSecurityGuard::where('id', $request->session()->get('security_guard_data')->id)->count() > 0) {
                return redirect('/controle/portaria/principal');
            }
        }
		
		// Valida o IP - 131.255.83.58
		if ($request->getClientIp() == '131.255.83.58' || $request->getClientIp() == '177.91.235.122') {
			return $next($request);
		} else {
			if($request->ajax()){
				return response()->json([
                        'msg' => 'Você precisa estar em uma rede de conexão autorizada da GREE DO BRASIL'
                    ], 400);
			} else {
				abort(401);
			}
		}	
    }
}
