<?php

namespace App\Http\Middleware\EntryExit\Gate;

use App\Model\LogisticsEntryExitSecurityGuard;
use Closure;
use App\Model\Users;
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

        if (!$request->session()->get('security_guard_data')) {
            $request->session()->put('url', \Request::fullUrl());
            if($request->ajax()){
                abort(400,"Usuário não autenticado.");
            }else{
                return redirect('/controle/portaria');
            }
        } else {
            if (LogisticsEntryExitSecurityGuard::where('id', $request->session()->get('security_guard_data')->id)->count() == 0) {
                $request->session()->put('url', \URL::current());
                if($request->ajax()){
                    abort(400,"Usuário não autenticado.");
                }else{
                    return redirect('/controle/portaria');
                }
            }
        }

        // Adiciona o secret para requisições que não são ajax.
        if(!$request->ajax()) {
            $request->merge([
                'secret' => $request->session()->get('security_guard_data')->secret
            ]);
        }

        // Validando a hora de trabalho
        /*if ($request->secret != $request->session()->get('security_guard_data')->secret) {

            // Validar se o secret está valendo.
            if ($request->secret != $request->session()->get('security_guard_data')->secret) {
                // Validar o IP - 131.255.83.58
                if ($request->getClientIp() == '131.255.83.58'  || $request->getClientIp() == '177.91.235.122') {
					return $next($request);
                } else {
					abort(401);
				}	
            }

            
        } else {
            if($request->ajax()){
                $request->session()->flush();
                abort(400,"Você está fora do seu horário de trabalho.");
            }else{
                $request->session()->flush();
                return redirect('/controle/portaria')->with('error', 'Você está fora do seu horário de trabalha');
            }
        }*/
		
		if ($request->getClientIp() == '131.255.83.58'  || $request->getClientIp() == '177.91.235.122') {
			return $next($request);
		} else {
			abort(401);
		}	

        return $next($request);
    }
}
