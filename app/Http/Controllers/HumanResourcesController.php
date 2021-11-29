<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Redirect;
use App;
use App\Model\Users;
use App\Model\RecruitmentTest;
use App\Model\RecruitmentTestQuestions;
use App\Model\RecruitmentTestCandidates;
use App\Model\RecruitmentTestQuestionsAnswer;
use App\Model\RecruitmentTestResponse;
use App\Model\RecruitmentTestResponseOptions;

use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Exports\DefaultExport;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Maatwebsite\Excel\Facades\Excel;
use Hash;

use App\Model\UserNotificationExternal;
use App\Model\UserNotificationExternalMsg;

use App\Http\Controllers\Services\FileManipulationTrait;

class HumanResourcesController  extends Controller
{
    use FileManipulationTrait;

    public function recruitmentQuestionAll(Request $request) {

        $recruitment = RecruitmentTest::with('users')->orderBy('id', 'desc');

        if (!hasPermManager(25)) {
            $recruitment->where('owner_r_code', $request->session()->get('r_code'));
        }

        $array_input = collect([
            'title',
            'date_create',
            'is_progress',
			'sector',
        ]);

        $array_input = putSession($request, $array_input, 'rec_');
        $filter_session = getSessionFilters('rec_');

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."title"){
                    $recruitment->where('title', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."date_create"){
                    $recruitment->whereDate('created_at', '=', $value_filter);
                }
				if ($name_filter == $filter_session[1]."sector") {
					$recruitment->whereHas('users', function($q) use($value_filter) {
						$q->where('sector_id', $value_filter);
					});
				}
                if($name_filter == $filter_session[1]."is_progress"){
                    $value = $value_filter == 99 ? 0 : $value_filter;
                    $recruitment->where('is_progress', $value);
                }
            }
        }

		$sectors = \App\Model\Sector::all();
        return view('gree_i.rh.recruitment_question_all', [
            'recruitment_test' => $recruitment->paginate(10),
			'sectors' => $sectors
        ]);
    }
	
	public function recruitmentTestDuplicate(Request $request, $id) {
		
		$recruitment = RecruitmentTest::with('recruitment_test_questions.recruitment_test_questions_answer')->find($id);
		
		if (!$recruitment)
			return redirect()->back()->with('error', 'Não foi possível encontra a prova para realizar a duplicação');
		
		$newTest = $recruitment->replicate();
		$newTest->is_progress = 0;
		$newTest->is_send = 0;
		$newTest->save();
			
		foreach ($recruitment->recruitment_test_questions as $key) {
			$newRTQ = $key->replicate();
			$newRTQ->recruitment_test_id = $newTest->id;
			$newRTQ->save();
			
			foreach ($key->recruitment_test_questions_answer as $key1) {
				$newRTQA = $key1->replicate();
				$newRTQA->recruitment_test_questions_id = $newRTQ->id;
				$newRTQA->save();
			}
		}
		
		return redirect()->back()->with('success', 'Prova foi duplicada com sucesso!');
	}

    public function recruitmentQuestionNew(Request $request, $id) {

        $email_content = "Bom dia, [{NOME}]";
        $email_content .= "\nVocê foi selecionado para participar do processo seletivo gree, abaixo segue sua informações de acesso.\n\n";
        $email_content .= "Email: [{EMAIL}]\n";
        $email_content .= "Senha: [{SENHA}]\n\n";
        $email_content .= "Para acessar a prova clique no link abaixo:\n";
        $email_content .= "[{LINK}]";

        return view('gree_i.rh.recruitment_question_new', [
            'id' => $id,
            'email_content' => $email_content
        ]);
    }

    public function recruitmentQuestionEdit(Request $request, $id) {

        $test = RecruitmentTest::with('recruitment_test_candidates_all')->find($id);

        $questions = RecruitmentTestQuestions::with(['recruitment_test_questions_answer'])->where('recruitment_test_id', $id);
        
        return view('gree_i.rh.recruitment_question_edit', [
            'id' => $id,
            'test' => $test,
            'questions_id' => $questions->pluck('id')->toArray(),
            'question' => $questions->first() ? $questions->first()->toArray() : null,
            'is_progress' => $test->is_progress
        ]);
    }

    public function recruitmentQuestionEdit_do(Request $request) {

        $arr = json_decode($request->arr_answers, true);

        if($request->recruitment_test_id == 0) {

            $recruitment = new RecruitmentTest;
            $total_questions = 0;
        } else {

            $recruitment = RecruitmentTest::with('recruitment_test_questions')->find($request->recruitment_test_id);
            if(!$recruitment) {
                $request->session()->put('error', "Questionário de prova não encontrado!");
                return redirect()->back();
            }
            $total_questions = $recruitment->total_questions;
        }

        DB::beginTransaction();

        try {

            $recruitment->title = $request->title_test;
            $recruitment->description = $request->title_description;
            $recruitment->instructions = $request->test_instructions;
            $recruitment->owner_r_code = $request->session()->get('r_code');
            $recruitment->total_questions = $total_questions;
            $recruitment->test_percent = $request->test_percent;
            $recruitment->test_time = $request->test_time;
            $recruitment->email_subject = $request->email_subject;
            $recruitment->email_content_title = $request->email_content_title;
            $recruitment->email_content = $request->email_content;
            $recruitment->save();

            if (!empty($request->question)) {

                $item = new RecruitmentTestQuestions;
                $item->recruitment_test_id = $recruitment->id;
                $item->title = $request->question;
                
                if($item->save()) {
                    $recruitment->total_questions = $recruitment->total_questions + 1;
                    $recruitment->save();
                }    

                $arr_answers = json_decode($request->arr_answers, true);

                if(count($arr_answers) > 0) {

                    foreach ($arr_answers as $key) {

                        $answer = new RecruitmentTestQuestionsAnswer;
                        $answer->recruitment_test_questions_id = $item->id;
                        $answer->description = $key['answer'];
                        $answer->is_correct = $key['is_correct'];
                        $answer->save();
                    }
                } else {
                    throw new \Exception('Título não adicionado à pergunta!');
                }
            }

            if($request->arr_candidates != null) {

                if($request->recruitment_test_id != 0) {
                    RecruitmentTestCandidates::where('recruitment_test_id', $recruitment->id)->delete();
                }

                $candidates = json_decode($request->arr_candidates, true);
                foreach ($candidates as $index => $key) {

                    $candidate = new RecruitmentTestCandidates;
                    $candidate->recruitment_test_id = $recruitment->id;
                    $candidate->name = $key['name'];
                    $candidate->email = $key['email'];
                    $new_pass = rand(10000, 99999);
                    $candidate->password = Hash::make($new_pass);
                    $candidate->code_link = date('YmdHis').$index;
                    $candidate->save();
                    
                    $candidates[$index]['code_link'] = $candidate->code_link;
                    $candidates[$index]['pass'] = $new_pass;
                }
            }

            DB::commit();
            
            if($request->is_send == 1 && $recruitment->is_progress == 0) {

                foreach ($candidates as $candidate) {

                    $vars = array(
                        "[{NOME}]" => $candidate['name'],
                        "[{EMAIL}]" => $candidate['email'],
                        "[{SENHA}]" => $candidate['pass'],
                        "[{LINK}]" => '<a href="'.$request->root().'/recrutamento/prova/'.$candidate['code_link'].'">Acesse sua prova aqui</a>'
                    );

                    $message = strtr($request->email_content, $vars);

                    $pattern = array(
                        'title' => $request->email_content_title,
                        'description' => nl2br($message),
                        'template' => 'misc.DefaultExternal',
                        'subject' => $request->email_subject,
                    );
                    SendMailJob::dispatch($pattern, $candidate['email']);
                }

                $recruit = RecruitmentTest::find($recruitment->id);
                $recruit->is_send = 1;
                $recruit->is_progress = 1;
                $recruit->save();

                $request->session()->put('success', 'Prova enviada com sucesso!');
                return redirect('/recruitment/question/all');

            } else {

                if (!empty($request->question)) {
                    $request->session()->put('success', 'Questão cadastrada com sucesso!');
                    return redirect('/recruitment/question/edit/'.$recruitment->id.'');
                }
                else {
                    $request->session()->put('success', 'Prova atualizada com sucesso!');
                    return redirect('/recruitment/question/all');
                }    
            }
        } 
        catch (\Exception $e) {

            DB::rollBack();
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function recruitmentAnswerDeleteAJax(Request $request) {

        try {
            $answer = RecruitmentTestQuestionsAnswer::find($request->answer_id);
            if(!$answer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resposta não encontrada!',
                ]);
            }

            $answer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Resposta excluída com sucesso!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

    }

    public function recruitmentAnswerUpdateAJax(Request $request) {

        try {
            $answer = RecruitmentTestQuestionsAnswer::find($request->answer_id);
            if(!$answer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resposta não encontrada!',
                ]);
            }

            $answer->description = $request->field_answer;
            $answer->is_correct = $request->answer_status;
            $answer->save();

            return response()->json([
                'success' => true,
                'message' => 'Resposta atualizada com sucesso!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function recruitmentAnswerEditAJax(Request $request) {
        
        $answer = RecruitmentTestQuestionsAnswer::find($request->id_answer);
        if(!$answer) {
            return response()->json([
                'success' => false,
                'message' => 'Resposta não encontrada!',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'answer' => $answer,
        ], 200);
    }

    public function recruitmentQuestionEditAJax(Request $request) {

        $question = RecruitmentTestQuestions::find($request->id_question);
        if(!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Questão não encontrada!',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'question' => $question,
        ], 200);
    }

    public function recruitmentAnswerEditNewAjax(Request $request) {

        try {
            $answer = new RecruitmentTestQuestionsAnswer;
            $answer->recruitment_test_questions_id = $request->question_id;
            $answer->description = $request->description;
            $answer->is_correct = $request->is_correct;
            $answer->save();

            return response()->json([
                'success' => true,
                'message' => 'Questão adicionada com sucesso!',
                'answer' => $answer
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function recruitmentQuestionUpdateAJax(Request $request) {

        try {
            $question = RecruitmentTestQuestions::find($request->question_id);
            if(!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questão não encontrada!',
                ]);
            }

            $question->title = $request->question_content;
            $question->save();

            return response()->json([
                'success' => true,
                'message' => 'Questão atualizada com sucesso!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    function recruitmentQuestionDeleteAJax(Request $request, $id) {
        
        try {
            $question = RecruitmentTestQuestions::find($id);
            if(!$question) {
                return redirect()->back()->with('error', 'Questão não encontrada!');
            }

            if($question->delete()) {

                $answer = RecruitmentTestQuestionsAnswer::where('recruitment_test_questions_id', $id)->get();
                $id_arr = $answer->pluck('id')->toArray();

                if(count($id_arr) > 0) {
                    RecruitmentTestQuestionsAnswer::whereIn('id', $id_arr)->delete();
                }

                $test = RecruitmentTest::find($question->recruitment_test_id);
                $test->total_questions = $test->total_questions - 1;
                $test->save();
            }

            return redirect()->back()->with('success', 'Questão e respostas excluídas com sucesso!');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function recruitmentAnswerLogin(Request $request, $code) {

        $candidate = RecruitmentTestCandidates::with(['recruitment_test'])->whereHas('recruitment_test', function ($query) {
            $query->where('is_progress', 1);
        })->where('code_link', $code)->first();

        return view('gree_i.rh.recruitment_aswer_login', [
            'candidate' => $candidate
        ]);
    }

    public function recruitmentAnswerLoginVerify(Request $request) {

        $candidate = RecruitmentTestCandidates::where('code_link', $request->code)->first();
        if($candidate) {
            if (Hash::check($request->password, $candidate->password)) {

                $request->session()->put('recruitment_candidate_id', $candidate->id);
                $request->session()->put('recruitment_candidate_name', $candidate->name);
                $request->session()->put('recruitment_candidate_email', $candidate->email);

                if ($request->session()->get('url')) {

                    $url = $request->session()->get('url');
                    $request->session()->forget('url');
                    return redirect($url);
                } else {

                    return redirect('/recrutamento/prova/resolver/'.$request->code.'');
                } 
            } else {
                $request->session()->put('error', 'Senha incorreta!');
                return redirect()->back();
            }
        } else {
            $request->session()->put('error', 'Senha incorreta!');
            return redirect()->back();
        }
    }

    public function recruitmentAnswerEdit(Request $request, $code) {

        $candidate = RecruitmentTestCandidates::with(['recruitment_test.recruitment_test_questions'])->where('code_link', $code)->first();

        return view('gree_i.rh.recruitment_answer_edit', [
            'candidate_id' => $candidate->id,
            'test_id' => $candidate->recruitment_test->id,
            'questions_id' => $candidate->recruitment_test->recruitment_test_questions->pluck('id')->shuffle()->toArray(),
            'instructions' => $candidate->recruitment_test->instructions,
            'title' => $candidate->recruitment_test->title,
            'description' => $candidate->recruitment_test->description,
            'is_concluded' => $candidate->is_concluded,
            'is_approved' => $candidate->is_approved,
            'time' => $candidate->recruitment_test->test_time
        ]);
    }

    public function recruitmentQuestionAjax(Request $request) {

        $question = RecruitmentTestQuestions::with('recruitment_test_questions_answer')->find($request->question_id);
        
        return response()->json([
            'success' => true,
            'question' => $question,
        ], 200); 
    }

    public function recruitmentQuestionResponse(Request $request) {

        DB::beginTransaction();

        try {

            $test_response = new RecruitmentTestResponse;
            $test_response->recruitment_test_candidates_id = $request->candidate_id;
            $test_response->recruitment_test_id = $request->test_id;
            $test_response->save();
            
            $response = $request->arr_response;
            if(count($response) > 0) {

                foreach ($response as $resp) {

                    $option = new RecruitmentTestResponseOptions;
                    $option->recruitment_test_response_id = $test_response->id;
                    $option->recruitment_test_questions_id = $resp['question_id'];
                    $option->answer_option_id = $resp['option_id'];
                    $option->save();
                }    
            }    
            
            DB::commit();

            return response()->json([
                'success' => true,
                'is_approv' => $this->verifyApprov($response, $request->test_id, $request->candidate_id)
            ], 200); 
        }     
        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $request->session()->put('error', $e->getMessage())
            ], 200); 
        }
    }  
    
    private function verifyApprov($response, $test_id, $candidate_id) {

        
        $options_id = collect($response)->pluck('option_id')->toArray();
        $options_correct = RecruitmentTestQuestionsAnswer::whereIn('id', $options_id)->where('is_correct', 1)->count();

        $percent = RecruitmentTest::find($test_id)->test_percent;
        $approv_test = count($response) * $percent / 100;

        $approv = RecruitmentTestCandidates::find($candidate_id);
        $approv->is_concluded = 1;
        $approv->questions_correct = $options_correct;
        $approv->date_concluded = date('Y-m-d H:i:s');

        if($options_correct >= intval($approv_test)) {
            $approv->is_approved = 1;
            $approv->save();

            $this->verifyTestFinish($test_id);

            return true;
        } else {

            $approv->is_approved = 0;
            $approv->save();

            $this->verifyTestFinish($test_id);

            return false;
        }
    }

    public function logout(Request $request) {

        $request->session()->flush();
        return Redirect::to('https://gree.com.br');
    }

    public function recruitmentAnswerCandidates(Request $request, $id) {

        $candidates = RecruitmentTestCandidates::with('recruitment_test')->where('recruitment_test_id', $id)->orderBy('id', 'DESC');

        if(!$candidates) {
            $request->session()->put('error', "Candidatos desta prova não foram encontrados!");
            return redirect()->back();
        }

        $array_input = collect([
            'name',
            'email',
            'is_approved',
        ]);

        $array_input = putSession($request, $array_input, 'cand_');
        $filter_session = getSessionFilters('cand_');

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."name"){
                    $candidates->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."email"){
                    $candidates->where('email', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."is_approved"){

                    $value = $value_filter == 1 ? 1 : 0;
                    $candidates->where('is_approved', $value);
                }
            }
        }

        if($request->export == 1) {

            $heading = array('Nome', 'Email', 'Prova', 'Realizada', 'Total acertadas', 'Status');
			$rows = array();

            foreach ($candidates->get() as $key) {
                $line = array();

                $line[0] = $key->name;
                $line[1] = $key->email;
                $line[2] = $key->recruitment_test->title;
                $line[3] = date('d/m/Y H:i:s', strtotime($key->created_at));
                $line[4] = $key->questions_correct;
                $line[5] = $key->is_approved == 1 ? 'APROVADO' : 'REPROVADO';
                
                array_push($rows, $line);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'CandidatesExport-'. date('Y-m-d') .'.xlsx');
        }    

        return view('gree_i.rh.recruitment_answer_candidates', [
            'candidates' => $candidates->paginate(10)
        ]);
    }    

    public function recruitmentAnswerCandidatesResponse(Request $request, $id) {

        $response = RecruitmentTestResponse::with('recruitment_test.recruitment_test_questions.recruitment_test_questions_answer', 
                                                  'recruitment_test_response_options')
                                                  ->where('recruitment_test_candidates_id', $id)->first();

        return view('gree_i.rh.recruitment_answer_candidates_response', [
            'title' => $response->recruitment_test->title, 
            'questions' =>  $response->recruitment_test->recruitment_test_questions,
            'response' => $response->recruitment_test_response_options,
            'arr_alphabet' => $this->alphabet()
        ]);
    }

    public function recruitmentQuestionResponseTimeout(Request $request) {

        DB::beginTransaction();
        try {

            $test_response = RecruitmentTestCandidates::find($request->candidate_id);

            if($test_response->is_concluded == 0) {

                $test_response->is_concluded = 1;
                $test_response->is_timeout = 1;
                $test_response->save();
                
                DB::commit();

                $this->verifyTestFinish($request->test_id);

                return response()->json([
                    'success' => true,
                    'is_approv' => false
                ], 200); 

            } else {
                return response()->json([
                    'success' => true,
                    'is_approv' => true
                ], 200); 
            }
        }     
        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $request->session()->put('error', $e->getMessage())
            ], 200); 
        }
    }

    public function recruitmentQuestionImageUpload(Request $request) {

        if ($request->hasFile('file')) {
            
            $response = $this->uploadS3(1, $request->file, $request);

            if ($response['success']) {

                return response()->json([
                    'success' => true,
                    'url' => $response['url']
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível fazer upload do arquivo!'
                ], 400);
            }
        }  else {

            return response()->json([
                'success' => false,
                'message' => 'Arquivo não adicionado para upload!'
            ], 400);
        }
    }
	
	public function notifyCollaboratorLiberate(Request $request) {

        $users_notify = UserNotificationExternal::orderBy('id', 'desc');

        $array_input = collect([
            'r_code',
            'name',
            'status',
        ]);

        $array_input = putSession($request, $array_input, 'notif_');
        $filter_session = getSessionFilters('notif_');

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."r_code"){
                    $users_notify->where('r_code', $value_filter);
                }
                if($name_filter == $filter_session[1]."name"){
                    $users_notify->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){

                    $value = $value_filter == 99 ? 0 : $value_filter;
                    $users_notify->where('status', $value);
                }
            }
        }

        return view('gree_i.rh.notify_collaborator_liberate', [
            'users_notify' => $users_notify->paginate(10)
        ]);
    }

    public function notifyCollaboratorLiberate_do(Request $request, $id, $type) {
        
        $user = UserNotificationExternal::find($id);
        if(!$user) {
            $request->session()->put('error', 'Colaborador não encontrado!');
            return redirect()->back();
        }

        try {
            $user->status = $type;
            $user->save();

            $msg = $type == 1 ? 'Colaborador foi liberado com sucesso!' : 'Colaborador foi removido com sucesso!';
            $request->session()->put('success', $msg);
            return redirect()->back();
        
        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function notifyCollaboratorNew(Request $request) {

        $users_notify = UserNotificationExternal::with(['user_notification_external_msg' => function($query) {
            $query->orderBy('id', 'DESC');
        }])->where('status', 1)->orderBy('id', 'desc');

        $array_input = collect([
            'r_code',
            'name',
            'sector'
        ]);

        $array_input = putSession($request, $array_input, 'collab_');
        $filter_session = getSessionFilters('collab_');

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."r_code"){
                    $users_notify->where('r_code', $value_filter);
                }
                if($name_filter == $filter_session[1]."name"){
                    $users_notify->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."sector"){
                    $users_notify->where('sector', $value_filter);
                }
            }
        }

        return view('gree_i.rh.notify_collaborator_new', [
            'users_notify' => $users_notify->paginate(5),
            'msg_last_user' => $users_notify->first() ? $users_notify->first()->user_notification_external_msg()->orderBy('id', 'DESC')->paginate(5, ['*'], 'notif') : collect([]),
            'first_collaborator' => $users_notify->first() ? $users_notify->first()->name : null,
            'collaborator_id' => $users_notify->first() ? $users_notify->first()->id : null
        ]);
    }
    
    public function notifyCollaboratorMsgAjax(Request $request) {

        $msg = UserNotificationExternalMsg::where('user_notification_external_id', $request->data_id)->orderBy('id', 'DESC');
        if(!$msg) {
            return response()->json([
                'success' => false,
                'message' => 'Mensagens não foram encontradas',
            ], 400);    
        }

        return response()->json([
            'success' => true,
            'msg' => $msg->paginate(5),
        ]);
    }

    public function notifyCollaboratorListAjax(Request $request) {
        
        $users_notify = UserNotificationExternal::orderBy('id', 'DESC');
        
        return response()->json([
            'success' => true,
            'users_notify' => $users_notify->paginate(5),
        ]);
    }

    public function notifyCollaboratorNotifySend(Request $request) {

        try {
    
            $user = UserNotificationExternal::find($request->collaborator_id);
            if(!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não foi encontrado',
                ], 400);
            }

            $new_notify = new UserNotificationExternalMsg;
            $new_notify->user_notification_external_id = $request->collaborator_id;
            $new_notify->title = $request->title_notify;
            $new_notify->message = $request->msg_notify;
            $new_notify->priority = $request->priority;
            $new_notify->r_code_sector = $request->session()->get('sector');
            $new_notify->r_code = $request->session()->get('r_code');
            $new_notify->save();

            $fields = array(
                'notification' => array(
                    'title' => $new_notify->title,
                    'body' => strWordCut($new_notify->message, 65),
                    'click_action' => 'https://gree.com.br/novidades/notice',
                    'icon' => 'https://gree-app.com.br/media/favicons/apple-touch-icon-180x180.png'
                ),
                'to'  => $user->token,
            );
            $notification = sendPushNotification($fields);

            $arr_user = [
                'priority' => $new_notify->priority,
                'collaborator_name' => $request->session()->get('first_name') .' '. substr($request->session()->get('last_name'), 0, 1),
                'r_code' => $user->r_code,
                'title' => $new_notify->title,
                'message' => $new_notify->message,
                'created_at' => $new_notify->created_at,
                'id' => $new_notify->id
            ];

            if($notification->success == 1) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifificação enviada com sucesso!',
                    'user' => $arr_user
                ]);
            }

            if($notification->failure == 1) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cadastrado com sucesso, notificação não enviada!',
                    'user' => $arr_user
                ]);
            }

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function notifyCollaboratorMsgDelete(Request $request) {
        
        try {
            $notif =  UserNotificationExternalMsg::find($request->notif_id);
            if(!$notif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada',
                ]);
            }

            $notif->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notificação excluída com sucesso!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }        
    }

    private function verifyTestFinish($test_id) {

        $candidates = RecruitmentTestCandidates::where('recruitment_test_id', $test_id)->get();

        if($candidates->where('is_concluded', 1)->count() == $candidates->count()) {
            $test = RecruitmentTest::find($test_id);
            $test->is_progress = 2;
            $test->save();
        }
    }

    private function alphabet() {
        return ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    }
}   