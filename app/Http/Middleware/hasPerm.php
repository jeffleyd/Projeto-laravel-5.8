<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Users;
use App\Model\UserOnPermissions;
use Illuminate\Support\Facades\DB;
use App;

class hasPerm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $perm, $mng, $approv)
    {
        // lipando os buffs do PHP
        if (ob_get_contents()){
            ob_end_clean();
        }


        $has_perm = "";
        // $perm = Id permission, $mng = is manager of permission?, $approv = Can approval request?
        if ($mng == 1) {
            $has_perm = hasPermManager($perm);
        }
        if ($approv == 1 and !$has_perm) {
            $has_perm = hasPermApprov($perm);
        }
        if ($mng == 0 and $approv == 0 and !$has_perm) {
            $has_perm = hasPerm($perm);
        }
		
        if (!$has_perm) {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return redirect('news');
        }

        return $next($request);
    }
}
