<?php

namespace App\Http\Middleware\Commercial\Salesman;

use Closure;
use App\Model\Commercial\Salesman;

class commercialSalesmanIsLoggedDashboard
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

        if (!$request->session()->get('salesman_data')) {
            $request->session()->put('url', \URL::current());
            if($request->ajax()){
                abort(400,"Usuário não autenticado.");
            }else{
                return redirect('/comercial/operacao/login');
            }
            
        } else {
            if (Salesman::where('id', $request->session()->get('salesman_data')->id )->count() == 0) {
                $request->session()->put('url', \URL::current());
                if($request->ajax()){
                    abort(400,"Usuário não autenticado.");
                }else{
                    return redirect('/comercial/operacao/login');
                }
            }
        }
        return $next($request);
    }
}
