<?php

namespace App\Http\Middleware\Commercial;

use Closure;
use App\Model\Users;
use App\Model\UserOnPermissions;
use Illuminate\Support\Facades\DB;
use App;

class hasSchemePerm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param $perm [nome da permissão necessária]
     * @param $type [view = visualizar ou edit = Editar]
     * @return mixed
     */
    public function handle($request, Closure $next, $perm, $type)
    {

        $scheme = App\Model\Commercial\UserOnPermissions::where('r_code', $request->session()->get('r_code'))->first();

        if (!$scheme) {
            App::setLocale($request->session()->get('lang'));
            if($request->ajax()){
                abort(400,__('layout_i.not_permissions'));
            }else{
                return redirect()->back()->with('error', __('layout_i.not_permissions'));
            }
        }

        $obj = json_decode($scheme->scheme, true);

        if (!isset($obj[$perm][$type])) {
            App::setLocale($request->session()->get('lang'));
            if($request->ajax()){
                abort(400,__('layout_i.not_permissions'));
            }else{
                return redirect()->back()->with('error', __('layout_i.not_permissions'));
            }
        }


        return $next($request);
    }
}
