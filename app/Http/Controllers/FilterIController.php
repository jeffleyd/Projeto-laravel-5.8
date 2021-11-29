<?php

namespace App\Http\Controllers;

use App\Model\Users;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\Regions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Log;

class FilterIController extends Controller
{

    // FILTERS TASK
    public function filterTaskUsers(Request $request) {
        $sector = $request->input('sector');

        $user = '<option value=""></option>';

        $data = Users::where('sector_id', $sector)->get();

        foreach ($data as $key) {
            $selected = $key->r_code == $request->session()->get('taskf_rcodes') ? "selected" : "";

            $user .= '<option '. $selected .' value="'. $key->r_code .'">'. $key->first_name .' '. $key->last_name .'('. $key->r_code .')' .'</option>';
        }

        return $user;
    }

}