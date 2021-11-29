<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\RecruitmentTestCandidates;
use Illuminate\Support\Facades\DB;

class  RecruitmentTestHasLogged
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

        $code = $request->route('code');

        if ($request->session()->get('recruitment_candidate_id')) {
            if (RecruitmentTestCandidates::where('id', $request->session()->get('recruitment_candidate_id'))->count() > 0) {
                return redirect('/recrutamento/prova/resolver/'.$code);
            }
        }

        return $next($request);
    }
}