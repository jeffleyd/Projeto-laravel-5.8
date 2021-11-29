<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\RecruitmentTestCandidates;
use Illuminate\Support\Facades\DB;

class RecruitmentTestHasLogin
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

        if (!$request->session()->get('recruitment_candidate_id')) {
            $request->session()->put('url', \URL::current());
            return redirect('/recrutamento/prova/'.$code);
        } else {
            if (RecruitmentTestCandidates::where('id', $request->session()->get('recruitment_candidate_id'))->count() == 0) {
                $request->session()->put('url', \URL::current());
                return redirect('/recrutamento/prova/'.$code);
            }
        }

        return $next($request);
    }
}