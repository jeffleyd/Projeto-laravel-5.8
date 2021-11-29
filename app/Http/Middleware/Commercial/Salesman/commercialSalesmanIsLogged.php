<?php

namespace App\Http\Middleware\Commercial\Salesman;

use Closure;
use App\Model\Commercial\Salesman;

class commercialSalesmanIsLogged
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

        if (!$request->session()->get('sal_otpauth')) {
            $request->session()->put('url', \URL::current());
            if($request->ajax()){
                abort(400,"Usuário não autenticado.");
            }else{
                return redirect('/comercial/operacao/login');
            }
        } elseif (!$request->session()->has('salesman_data')) {
            $request->session()->put('url', \URL::current());
            if($request->ajax()){
                abort(400,"Usuário não autenticado.");
            }else{
                return redirect('/comercial/operacao/login');
            }
        } else {

            $sallesman = Salesman::where('id', $request->session()->get('salesman_data')->id)->first();
            if ($sallesman) {
                if($sallesman->is_active == 0) {
                    $request->session()->put('url', \URL::current());
                    if($request->ajax()){
                        abort(400,"Usuário não autenticado.");
                    }else{
                        return redirect('/comercial/operacao/login');
                    }
                }                
            } 
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}
		
        return $next($request);

    }
}
