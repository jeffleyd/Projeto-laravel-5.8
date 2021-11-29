<?php

namespace App\Http\Middleware\Commercial\Salesman;

use Closure;
use App\Model\Commercial\Salesman;

class commercialSalesmanHasLogged
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
			$salesman = Salesman::where('r_code', $request->session()->get('r_code'))->where('is_direction', 3)->first();
			if ($salesman) {
				$request->session()->put('salesman_data', $salesman);
				$request->session()->put('sal_code', $salesman->code);

				if($salesman->otpauth)
					$request->session()->put('sal_otpauth', $salesman->otpauth);
				else
					$request->session()->put('sal_otpauth', 'NDXJUTKSDFP573UWP4LNBVANOTDYV2JT');

				return redirect('/comercial/operacao/dashboard');
			}
		} else if ($request->session()->get('salesman_data')) {
            if (Salesman::where('id', $request->session()->get('salesman_data')->id)->count() > 0) {
                return redirect('/comercial/operacao/dashboard');
            }
        }
		
		if (!$request->isSecure()) {
			return redirect()->secure($request->getRequestUri());
		}

        return $next($request);
    }
}
