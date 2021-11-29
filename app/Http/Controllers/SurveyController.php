<?php

namespace App\Http\Controllers;

use App\Exports\DefaultExport;
use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Jobs\SendMailAttachJob;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Hash;
use App;
use Log;

use App\Model\Users;
use App\Model\UserSurvey;
use App\Model\UserSurveyAnswer;
use App\Model\SurveyNotifyUser;
use App\Model\Survey;
use App\Model\SurveyQuestions;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SurveyController extends Controller
{

    public function SurveyList(Request $request) {

        $survey = DB::table('survey')->orderBy('id', 'DESC');

        if (!hasPermManager(13)) {
            $survey->where('owner_r_code', $request->session()->get('r_code'));
        }

        return view('gree_i.survey.survey_list', [
            'survey' => $survey->paginate(10),
        ]);
    }

    public function SurveyEdit(Request $request, $id) {

        $frequency_week_select = collect([
            (object)['value'=>'2','text'=>'Segunda-Feira', 'selected'=>false],
            (object)['value'=>'3','text'=>'Terça-Feira', 'selected'=>false],
            (object)['value'=>'4','text'=>'Quarta-Feira', 'selected'=>false],
            (object)['value'=>'5','text'=>'Quinta-Feira', 'selected'=>false],
            (object)['value'=>'6','text'=>'Sexta-Feira', 'selected'=>false],
            (object)['value'=>'7','text'=>'Sábado', 'selected'=>false],
            (object)['value'=>'1','text'=>'Domingo', 'selected'=>false],
        ]);
        $frequency_month_select = collect([
            (object)['value'=>'1','text'=>'1', 'selected'=>false],
            (object)['value'=>'2','text'=>'2', 'selected'=>false],
            (object)['value'=>'3','text'=>'3', 'selected'=>false],
            (object)['value'=>'4','text'=>'4', 'selected'=>false],
            (object)['value'=>'5','text'=>'5', 'selected'=>false],
            (object)['value'=>'6','text'=>'6', 'selected'=>false],
            (object)['value'=>'7','text'=>'7', 'selected'=>false],
            (object)['value'=>'8','text'=>'8', 'selected'=>false],
            (object)['value'=>'9','text'=>'9', 'selected'=>false],
            (object)['value'=>'10','text'=>'10', 'selected'=>false],
            (object)['value'=>'11','text'=>'11', 'selected'=>false],
            (object)['value'=>'12','text'=>'12', 'selected'=>false],
            (object)['value'=>'13','text'=>'13', 'selected'=>false],
            (object)['value'=>'14','text'=>'14', 'selected'=>false],
            (object)['value'=>'15','text'=>'15', 'selected'=>false],
            (object)['value'=>'16','text'=>'16', 'selected'=>false],
            (object)['value'=>'17','text'=>'17', 'selected'=>false],
            (object)['value'=>'18','text'=>'18', 'selected'=>false],
            (object)['value'=>'19','text'=>'19', 'selected'=>false],
            (object)['value'=>'20','text'=>'20', 'selected'=>false],
            (object)['value'=>'21','text'=>'21', 'selected'=>false],
            (object)['value'=>'22','text'=>'22', 'selected'=>false],
            (object)['value'=>'23','text'=>'23', 'selected'=>false],
            (object)['value'=>'24','text'=>'24', 'selected'=>false],
            (object)['value'=>'25','text'=>'25', 'selected'=>false],
            (object)['value'=>'26','text'=>'26', 'selected'=>false],
            (object)['value'=>'27','text'=>'27', 'selected'=>false],
            (object)['value'=>'28','text'=>'28', 'selected'=>false],
            (object)['value'=>'29','text'=>'29', 'selected'=>false],
            (object)['value'=>'30','text'=>'30', 'selected'=>false],
        ]);

        if ($id == 0) {

            $name = "";
            $description = "";

            // $r_codes = ['4447','2794'];
            $r_codes = [];
            $survey_init = 1;
            $survey_frequency = 0;
            $frequency_time = null;
            $frequency_week = null;
            $frequency_month = null;

            $is_notify = 0;
            $is_active = 0;
            $questions = [
                (object)[
                    "id" => 0,
                    "title" => "",
                    "is_notify" => 0,
                    "answer_type" => 1,
                    "json_answer" => [
                        ["title" => ""]
                    ],
                    "is_required" => 1,
                    "show_obs" => 1
                ]];

        } else {

            $survey = Survey::find($id);

            if ($survey) {

                $name = $survey->name;
                $description = $survey->description;
                $is_active = $survey->is_active;
                $is_notify = $survey->is_notify;

                $survey_init = $survey->survey_init;
                $survey_frequency = $survey->survey_frequency;
                $frequency_time = $survey->frequency_time;
                $frequency_week = $survey->frequency_week;
                $frequency_month = $survey->frequency_month;

                $questions = SurveyQuestions::where('survey_id', $survey->id)->get();

                $r_codes = SurveyNotifyUser::where('survey_id', $survey->id)->pluck('user_r_code')->toArray();

                $week_select = $frequency_week_select->whereIn('value', $frequency_week )->transform(function($item, $key) {
                    $item->selected = true;
                    return $item;
                });

                if (!$week_select->isEmpty()) {
                    $frequency_week_select = $frequency_week_select->merge($week_select)->unique();
                }

                $month_select = $frequency_month_select->whereIn('value', $frequency_month )->transform(function($item, $key) {
                    $item->selected = true;
                    return $item;
                });

                if (!$month_select->isEmpty()) {
                    $frequency_month_select = $frequency_month_select->merge($month_select)->unique();
                }

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $userall = Users::all();

        return view('gree_i.survey.survey_edit', [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'is_active' => $is_active,
            'questions' => $questions,
            'userall' => $userall,


            'r_codes' => $r_codes,
            'is_notify' => $is_notify,

            'survey_init' => $survey_init,
            'survey_frequency' => $survey_frequency,
            'frequency_time' => $frequency_time,
            'frequency_week_select' => $frequency_week_select,
            'frequency_month_select' => $frequency_month_select,
            'frequency_week' => $frequency_week,
            'frequency_month' => $frequency_month,


        ]);
    }

    public function SurveyEdit_do(Request $request) {
        // dd($request);die;

        $erros_ocorridos = array();

        $validator = Validator::make($request->all(), [
            'survey_id' => 'required',
            'name' => 'required',
            //'description' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            $request->session()->put('error', "Erro ao Salvar Formulario");
            return redirect()->back()->withErrors($validator)->withInput();

        }

        DB::beginTransaction(); //inicio da transação no SGBD

        if ($request->survey_id == 0) {

            $survey = new Survey;
        } else {

            $survey = Survey::find($request->survey_id);

            if (!$survey) {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }


        }

        $survey->name = $request->name;
        $survey->description = $request->description;
        $survey->is_active = $request->is_active;
        $survey->is_notify = $request->is_notify;
        $survey->owner_r_code = $request->session()->get('r_code');
        $survey->survey_init = $request->survey_init;
        $survey->survey_frequency = $request->survey_frequency;
        $survey->frequency_time = $request->frequency_time;
        $survey->frequency_week = $request->frequency_week;
        $survey->frequency_month = $request->frequency_month;

        if($request->survey_init == 0 && $request->is_active == 1){
            $survey_active = Survey::where('id',"!=", $request->survey_id )
                ->where('survey_init', 0 )
                ->where('is_active', 1 )->first();

            if($survey_active){
                $request->session()->put('error', "Já existe uma pesquisa ativa ao iniciar o sistema");
                return redirect()->back();
            }
        }



        $survey->save();

        if($survey){
            $erros_ocorridos[] = false;
        }else{
            $erros_ocorridos[] = true;
        }
        $group = $request->group;

        if (!empty($group)) {
            for ($i = 0; $i < count($group); $i++) {
                if (!empty($group[$i]['title'])) {

                    if ($group[$i]['item_id'] != 0 and $group[$i]['item_id'] != "") {
                        $item = SurveyQuestions::find($group[$i]['item_id']);
                    } else {
                        $item = new SurveyQuestions;
                    }

                    $item->title = $group[$i]['title'];
                    $item->is_notify = isset($group[$i]['is_notify']) ? $group[$i]['is_notify'] : 0;
                    $item->is_required = isset($group[$i]['is_required']) ? $group[$i]['is_required'] : 0;
                    $item->show_obs = isset($group[$i]['show_obs']) ? $group[$i]['show_obs'] : 0;
                    $item->survey_id = $survey->id;

                    $item->answer_type = $group[$i]['answer_type'];


                    if (!empty($group[$i]['json_answer'])) {
                        $item->json_answer = $group[$i]['json_answer'];
                    }else{
                        $item->json_answer = null;
                    }

                    $item->save();

                    if($item){
                        $erros_ocorridos[] = false;
                    }else{
                        $erros_ocorridos[] = true;
                    }
                }
            }
        }

        $users_to_insert = collect($request->r_codes);

        if (!empty($users_to_insert)) {

            $surveyNotifyUser = SurveyNotifyUser::where('survey_id', $survey->id);
            $users_notify = $surveyNotifyUser->pluck('user_r_code');

            //Verifica se existe usuarios para serem notificados
            if (!empty($users_notify)) {

                $users_to_delete = $users_notify->diff($users_to_insert);
                $users_to_insert = $users_to_insert->diff(collect($users_notify));

                $surveyNotifyUser->whereIn('user_r_code', $users_to_delete)->delete();
                if($surveyNotifyUser){
                    $erros_ocorridos[] = false;
                }else{
                    $erros_ocorridos[] = true;
                }

            }

            foreach ($users_to_insert as $r_code) {

                $surveyNotifyUser = new SurveyNotifyUser;
                $surveyNotifyUser->survey_id = $survey->id;
                $surveyNotifyUser->user_r_code = $r_code;
                $surveyNotifyUser->save();

                if($surveyNotifyUser){
                    $erros_ocorridos[] = false;
                }else{
                    $erros_ocorridos[] = true;
                }
            }
        }




        if(in_array(true, $erros_ocorridos ) ){
            DB::rollBack(); //rollback no banco em caso de erro
            //Registrar Log de Erro
            $request->session()->put('error', "Erro ao Salvar Formulario");
        }else{
            DB::commit();
            $request->session()->put('success', "Lista de pesquisa atualizada com sucesso!");
            return redirect('/survey/all');
        }


    }

    public function SurveyExport(Request $request) {
        $date = $request->date;

        $survey = Survey::where('is_active', 1)->first();

        header('Content-Type: application/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=SurveyReportDaily-'. date('Y-m-d') .'.csv');

        $handle = fopen('php://output', 'w');
        fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

        $data = SurveyQuestions::where('survey_id', $survey->id)->get();

        $questions = array();
        array_push($questions, '#');
        foreach ($data as $key) {

            array_push($questions, $key->title);
        }
        fputcsv($handle, $questions, ";");

        $exports = DB::table('user_survey')
            ->leftJoin('users','user_survey.user_r_code','=','users.r_code')
            ->select('user_survey.*', 'users.first_name', 'users.last_name')
            ->whereYear('user_survey.created_at', date('Y', strtotime($date)))
            ->whereMonth('user_survey.created_at', date('m', strtotime($date)))
            ->whereDay('user_survey.created_at', date('d', strtotime($date)))
            ->where('user_survey.survey_id', $survey->id)
            ->get();

        foreach ($exports as $export) {

            $data = DB::table('user_survey_answer')
                ->where('user_answer_id', $export->id)
                ->get();

            $response = array();
            array_push($response, $export->first_name .' '. $export->last_name);
            foreach ($data as $key) {

                array_push($response, '('. $key->answer_option .') '. $key->answer_obs);
            }

            fputcsv($handle, $response, ";");
        }


        fclose($handle);
    }

    public function SurveyDelete(Request $request, $id) {
        $question = SurveyQuestions::find($id);

        if ($question) {
            SurveyQuestions::where('id', $id)->delete();

            $user_s = UserSurveyAnswer::where('question_id', $id)->delete();

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function SurveyAswers(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('surveyf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('surveyf_r_code');
        }

        if (!empty($request->input('survey_id'))) {
            $request->session()->put('surveyf_survey_id', $request->input('survey_id'));
        } else {
            $request->session()->forget('surveyf_survey_id');
        }
        if (!empty($request->input('start_date'))) {
            $request->session()->put('surveyf_start_date', $request->input('start_date'));
        } else {
            $request->session()->forget('surveyf_start_date');
        }
        if (!empty($request->input('end_date'))) {
            $request->session()->put('surveyf_end_date', $request->input('end_date'));
        } else {
            $request->session()->forget('surveyf_end_date');
        }

        $answer = UserSurvey::with('users', 'survey', 'userSurveyAnswer')
            ->orderBy('user_survey.id', 'DESC');

        if (!hasPermManager(13)) {
            $answer->where('user_survey.owner_r_code', $request->session()->get('r_code'));
        }

        if (!empty($request->session()->get('surveyf_r_code'))) {
            $answer->where('users.r_code', $request->session()->get('surveyf_r_code'));
        }
        if (!empty($request->session()->get('surveyf_survey_id'))) {
            $answer->where('user_survey.survey_id', $request->session()->get('surveyf_survey_id'));
        }
        if (!empty($request->session()->get('surveyf_start_date'))) {
            $date = $request->session()->get('surveyf_start_date');
            $date = Carbon::parse($date)->format('Y-m-d');

            $answer->whereRaw("date(user_survey.created_at) >= date('".$date."')" );
        }

        if (!empty($request->session()->get('surveyf_end_date'))) {
            $date = $request->session()->get('surveyf_end_date');
            $date = Carbon::parse($date)->format('Y-m-d');

            $answer->whereRaw("date(user_survey.created_at) <= date('".$date."')" );
        }

        if ($request->export) {
            if (empty($request->session()->get('surveyf_survey_id')))
                return redirect()->back()->with('error', 'Você precisa selecionar a pesquisa que deseja exportar!');

            $search = SurveyQuestions::where('survey_id', $request->session()->get('surveyf_survey_id'))->get();
            $heading = [];

            $heading[] = 'Usuário';
            foreach ($search->pluck('title')->toArray() as $arr) {
                $heading[] = $arr;
            }
            $heading[] = 'Respondido em';

            $rows = array();
            foreach ($answer->get() as $val) {
                $line = array();
                $description = array();
                $line[] = $val->users? $val->users->short_name : 'Anônimo';
                //$description[] = '#';

                foreach ($val->userSurveyAnswer as $i => $asw) {
                    $line[] = '('.$asw->answer_option.') '.$asw->answer_obs;
                    if ($i == 0)
                        $description[] = $asw->answer_obs;
                    else
                        $description[] = '';
                }

                $line[] = date('d-m-Y', strtotime($val->created_at));
                //$description[] = '#';
                array_push($rows, $line);
                //array_push($rows, $description);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'SurveyExport-'. date('Y-m-d') .'.xlsx');
        }


        $userall = Users::all();
        $surveys = Survey::where('is_active', 1)->get();

        return view('gree_i.survey.answers', [
            'userall' => $userall,
            'surveys' => $surveys,
            'answer' => $answer->paginate(10),
        ]);
    }

    public function SurveyAswersView(Request $request, $id) {

        $answer = DB::table('user_survey_answer')
            ->leftJoin('survey_questions','user_survey_answer.question_id','=','survey_questions.id')
            ->select('user_survey_answer.*', 'survey_questions.title')
            ->where('user_survey_answer.user_answer_id', $id)
            ->get();

        return view('gree_i.survey.answers_view', [
            'answer' => $answer,
        ]);
    }

    public function userSurveyAswer(Request $request) {

        $s_id = $request->survey_id;
        $svy_get = Survey::find($s_id);
        if ($svy_get) {
            $survey = UserSurvey::where('user_r_code', $request->session()->get('r_code'))
                ->where('survey_id', $s_id)
                ->orderBy('id', 'DESC')->first();
            if ($survey) {
                if (date('Y-m-d', strtotime($survey->created_at)) >= date('Y-m-d')) {

                    if($svy_get->survey_init == 0 ){
                        $request->session()->put('s_report', 1);
                    }

                    if($svy_get->survey_init == 1 ){
                        $this->updateUserSurveyAswer($request, $svy_get, $survey);
                    }

                } else {
                    $this->insertUserSurveyAswer($request, $svy_get);
                }
            } else {
                $this->insertUserSurveyAswer($request, $svy_get);
            }
        }
    }

    private function insertUserSurveyAswer(Request $request, $svy_get){
        $s_id = $request->survey_id;
        $svy_notify = $svy_get->is_notify;


        $erros_ocorridos = array();
        DB::beginTransaction(); //inicio da transação no SGBD

        $questions = array();
        $q_survey = SurveyQuestions::where('survey_id', $request->survey_id)->count();
        $q_total = $q_survey + 1;
        $resp = 0;

        $survey = new UserSurvey;
        $survey->survey_id = $s_id;
        $survey->user_r_code = $request->session()->get('r_code');
        $survey->owner_r_code = $svy_get->owner_r_code;
        $survey->save();

        if($survey){
            $erros_ocorridos[] = false;
        }else{
            $erros_ocorridos[] = true;
        }

        if($svy_get->survey_init == 0 ){
            $request->session()->put('s_report', 1);
        }

        for ($i = 1; $i < $q_total; $i++) {

            $f_question = SurveyQuestions::find($request->input('question_'.$request->survey_id.'_'. $i .'_id'));

            $save_q = new UserSurveyAnswer;
            $save_q->question_id = $request->input('question_'.$request->survey_id.'_'. $i .'_id');
            $save_q->user_answer_id = $survey->id;
            $save_q->user_r_code = $request->session()->get('r_code');


            if($f_question->answer_type == 3){
                $save_q->answer_option = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $save_q->answer_option = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $save_q->answer_obs = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $save_q->save();

            if($save_q){
                $erros_ocorridos[] = false;
            }else{
                $erros_ocorridos[] = true;
            }


            if ($resp == 0 and $f_question->is_notify == 1) {
                if($f_question->answer_type == 1){
                    $resp = $request->input('question_'.$request->survey_id.'_'. $i .'') == 'Sim' ? 1 : 0;
                }
            }


            $push = array();

            if($f_question->answer_type == 3){
                $push['radio'] = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $push['radio'] = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $push['input'] = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $push['title'] = $f_question->title;

            array_push($questions, $push);
        }

        $me = Users::where('r_code', $request->session()->get('r_code'))->first();

        $pattern = array(
            'questions' => $questions,
            'user' => $me,
            'title' => strtoupper(strip_tags($svy_get->name)),
            'description' => '',
            'template' => 'survey.userInternal',
            'subject' => ucfirst(strip_tags($svy_get->name)) .' "'. getENameF($request->session()->get('r_code')) .'"',
        );

        if(in_array(true, $erros_ocorridos ) ){
            DB::rollBack(); //rollback no banco em caso de erro
            //Registrar Log de Erro
            $request->session()->put('error', "Erro ao Enviar Formulario");
        }else{
            DB::commit();
            if ($resp == 1 || $svy_notify==1) {
                $notify = SurveyNotifyUser::where('survey_id', $request->survey_id)->get();

                foreach ($notify as $key) {
                    $user = Users::where('r_code', $key->user_r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);
                }
            }
        }
    }

    private function updateUserSurveyAswer(Request $request, $svy_get, $survey){
        $s_id = $request->survey_id;
        $svy_notify = $svy_get->is_notify;


        $erros_ocorridos = array();
        DB::beginTransaction(); //inicio da transação no SGBD

        $questions = array();
        $q_survey = SurveyQuestions::where('survey_id', $request->survey_id)->count();
        $q_total = $q_survey + 1;
        $resp = 0;

        $surv = UserSurvey::find($survey->id);
        $surv->owner_r_code = $svy_get->owner_r_code;
        $surv->save();

        if($svy_get->survey_init == 0 ){
            $request->session()->put('s_report', 1);
        }

        for ($i = 1; $i < $q_total; $i++) {

            $f_question = SurveyQuestions::find($request->input('question_'.$request->survey_id.'_'. $i .'_id'));

            $save_q = UserSurveyAnswer::where('question_id', $request->input('question_'.$request->survey_id.'_'. $i .'_id'))
                ->where('user_answer_id', $survey->id)
                ->where('user_r_code', $request->session()->get('r_code'))->first();
            if(!$save_q){
                $save_q = new UserSurveyAnswer;
                $save_q->question_id = $request->input('question_'.$request->survey_id.'_'. $i .'_id');
                $save_q->user_answer_id = $survey->id;
                $save_q->user_r_code = $request->session()->get('r_code');
            }


            if($f_question->answer_type == 3){
                $save_q->answer_option = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $save_q->answer_option = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $save_q->answer_obs = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $save_q->save();

            if($save_q){
                $erros_ocorridos[] = false;
            }else{
                $erros_ocorridos[] = true;
            }


            if ($resp == 0 and $f_question->is_notify == 1) {
                if($f_question->answer_type == 1){
                    $resp = $request->input('question_'.$request->survey_id.'_'. $i .'') == 'Sim' ? 1 : 0;
                }
            }


            $push = array();

            if($f_question->answer_type == 3){
                $push['radio'] = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $push['radio'] = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $push['input'] = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $push['title'] = $f_question->title;

            array_push($questions, $push);
        }

        $me = Users::where('r_code', $request->session()->get('r_code'))->first();

        $pattern = array(
            'questions' => $questions,
            'user' => $me,
            'title' => strtoupper(strip_tags($svy_get->name)),
            'description' => '',
            'template' => 'survey.userInternal',
            'subject' => ucfirst(strip_tags($svy_get->name)) .' "'. getENameF($request->session()->get('r_code')) .'"',
        );

        if(in_array(true, $erros_ocorridos ) ){
            DB::rollBack();
            //Registrar Log de Erro
            $request->session()->put('error', "Erro ao Enviar Formulario");
        }else{
            DB::commit();
            if ($resp == 1 || $svy_notify==1) {
                $notify = SurveyNotifyUser::where('survey_id', $request->survey_id)->get();

                foreach ($notify as $key) {
                    $user = Users::where('r_code', $key->user_r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);
                }
            }
        }
    }

    public function SurveyAswersEdit(Request $request, $id) {
        $show_alert = false;
        $response_again = false;
        $s_report_anonymous = $request->session()->get('s_report_anonymous');

        $client_ip = $request->session()->get('client_ip');

        $new_response = $request->new_response;

        if (empty($client_ip)) {
            $request->session()->put('client_ip', $request->ip());
        }

        if (!empty($s_report_anonymous)) {
            $is_response = $s_report_anonymous->where('id',$id);
            if($is_response->isNotEmpty()){
                $show_alert = true;
            }
        }
        if($new_response){
            $show_alert = false;
        }

        $survey = App\Model\Survey::where('id', $id)->where('is_active', 1)->whereIn('survey_init', [1,2] )->first();
        $question = null;
        if($survey){
            $question = App\Model\SurveyQuestions::leftJoin('survey','survey_questions.survey_id','=','survey.id')
                ->select('survey_questions.*', 'survey.name', 'survey.description')
                ->where('survey_id', $survey->id)
                ->orderBy('created_at', 'asc')->get();
        }else{
            abort(404);
            $show_alert = false;
        }



        return view('gree_i.survey.answers_edit', [
            'survey' => $survey,
            'question' => $question,
            'show_alert' => $show_alert,
            'response_again' => $response_again,
        ]);
    }

    public function anonymousSurveyAswer(Request $request) {

        $s_id = $request->survey_id;
        $svy_get = Survey::find($s_id);
        if ($svy_get) {
            $this->insertAnonymousSurveyAswer($request, $svy_get);
        }
    }

    private function insertAnonymousSurveyAswer(Request $request, $svy_get){
        $s_id = $request->survey_id;
        $svy_notify = $svy_get->is_notify;


        $erros_ocorridos = array();
        DB::beginTransaction(); //inicio da transação no SGBD

        $questions = array();
        $q_survey = SurveyQuestions::where('survey_id', $request->survey_id)->count();
        $q_total = $q_survey + 1;
        $resp = 0;

        $survey = new UserSurvey;
        $survey->survey_id = $s_id;
        $survey->user_r_code = "anonimo";
        $survey->owner_r_code = $svy_get->owner_r_code;
        $survey->client_ip = $request->session()->get('client_ip');
        $survey->save();

        if($survey){
            $erros_ocorridos[] = false;
        }else{
            $erros_ocorridos[] = true;
        }

        if($svy_get->survey_init == 0 ){
            $request->session()->put('s_report', 1);
        }

        for ($i = 1; $i < $q_total; $i++) {

            $f_question = SurveyQuestions::find($request->input('question_'.$request->survey_id.'_'. $i .'_id'));

            $save_q = new UserSurveyAnswer;
            $save_q->question_id = $request->input('question_'.$request->survey_id.'_'. $i .'_id');
            $save_q->user_answer_id = $survey->id;
            $save_q->user_r_code = "anonimo";
            $save_q->client_ip = $request->session()->get('client_ip');


            if($f_question->answer_type == 3){
                $save_q->answer_option = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $save_q->answer_option = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $save_q->answer_obs = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $save_q->save();

            if($save_q){
                $erros_ocorridos[] = false;
            }else{
                $erros_ocorridos[] = true;
            }


            if ($resp == 0 and $f_question->is_notify == 1) {
                if($f_question->answer_type == 1){
                    $resp = $request->input('question_'.$request->survey_id.'_'. $i .'') == 'Sim' ? 1 : 0;
                }
            }


            $push = array();

            if($f_question->answer_type == 3){
                $push['radio'] = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $push['radio'] = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $push['input'] = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $push['title'] = $f_question->title;

            array_push($questions, $push);
        }

        $pattern = array(
            'questions' => $questions,
            'user' => "Anônimo",

            'title' => strtoupper(htmlspecialchars(strip_tags($svy_get->name)) ),
            'description' => '',
            'template' => 'survey.userAnonymous',
            'subject' => ucfirst(htmlspecialchars(strip_tags($svy_get->name))),
        );



        if(in_array(true, $erros_ocorridos ) ){
            DB::rollBack(); //rollback no banco em caso de erro
            //Registrar Log de Erro
            $request->session()->put('error', "Erro ao Enviar Formulario");
        }else{
            DB::commit();

            $s_report_anonymous = $request->session()->get('s_report_anonymous');
            if (!empty($s_report_anonymous)) {

                $is_response = $s_report_anonymous->where('id',$s_id);
                if($is_response->isEmpty()){
                    $s_report_anonymous->push(['id'=> $s_id]);
                    $request->session()->put('s_report_anonymous', $s_report_anonymous );
                }
            }else{
                $request->session()->put('s_report_anonymous', collect([['id'=> $s_id]]) );
            }


            if ($resp == 1 || $svy_notify==1) {
                $notify = SurveyNotifyUser::where('survey_id', $request->survey_id)->get();

                foreach ($notify as $key) {
                    $user = Users::where('r_code', $key->user_r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);
                }
            }

        }
    }

    private function updateAnonymousSurveyAswer(Request $request, $svy_get, $survey){
        $s_id = $request->survey_id;
        $svy_notify = $svy_get->is_notify;


        $erros_ocorridos = array();
        DB::beginTransaction(); //inicio da transação no SGBD

        $questions = array();
        $q_survey = SurveyQuestions::where('survey_id', $request->survey_id)->count();
        $q_total = $q_survey + 1;
        $resp = 0;

        if($svy_get->survey_init == 0 ){
            $request->session()->put('s_report', 1); //criar novo mecanismo de sessao

        }

        for ($i = 1; $i < $q_total; $i++) {

            $f_question = SurveyQuestions::find($request->input('question_'.$request->survey_id.'_'. $i .'_id'));

            $save_q = UserSurveyAnswer::where('question_id', $request->input('question_'.$request->survey_id.'_'. $i .'_id'))
                ->where('user_answer_id', $survey->id)
                ->where('client_ip', $request->session()->get('client_ip'))->first();
            if(!$save_q){
                $save_q = new UserSurveyAnswer;
                $save_q->question_id = $request->input('question_'.$request->survey_id.'_'. $i .'_id');
                $save_q->user_answer_id = $survey->id;
                $save_q->user_r_code = "anonimo";
                $save_q->client_ip = $request->session()->get('client_ip');
            }


            if($f_question->answer_type == 3){
                $save_q->answer_option = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $save_q->answer_option = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $save_q->answer_obs = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $save_q->save();

            if($save_q){
                $erros_ocorridos[] = false;
            }else{
                $erros_ocorridos[] = true;
            }


            if ($resp == 0 and $f_question->is_notify == 1) {
                if($f_question->answer_type == 1){
                    $resp = $request->input('question_'.$request->survey_id.'_'. $i .'') == 'Sim' ? 1 : 0;
                }
            }


            $push = array();

            if($f_question->answer_type == 3){
                $push['radio'] = json_encode($request->input('question_'.$request->survey_id.'_'. $i .''));
            }else{
                $push['radio'] = $request->input('question_'.$request->survey_id.'_'. $i .'');
            }

            $push['input'] = $request->input('question_'.$request->survey_id.'_'. $i .'_input');
            $push['title'] = $f_question->title;

            array_push($questions, $push);
        }

        $pattern = array(
            'questions' => $questions,
            'title' => strtoupper(strip_tags($svy_get->name)),
            'description' => '',
            'template' => 'survey.userAnonymous',
            'subject' => ucfirst(strip_tags($svy_get->name)),
        );

        if(in_array(true, $erros_ocorridos ) ){
            DB::rollBack();
            //Registrar Log de Erro
            $request->session()->put('error', "Erro ao Enviar Formulario");
        }else{
            DB::commit();


            $s_report_anonymous = $request->session()->get('s_report_anonymous');
            if (!empty($s_report_anonymous)) {

                $is_response = $s_report_anonymous->where('id',$s_id);
                if($is_response->isEmpty()){
                    $s_report_anonymous->push(['id'=> $s_id]);
                    $request->session()->put('s_report_anonymous', $s_report_anonymous );
                }
            }else{
                $request->session()->put('s_report_anonymous', collect([['id'=> $s_id]]) );
            }

            if ($resp == 1 || $svy_notify==1) {
                $notify = SurveyNotifyUser::where('survey_id', $request->survey_id)->get();

                foreach ($notify as $key) {
                    $user = Users::where('r_code', $key->user_r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);
                }
            }
        }
    }

}
