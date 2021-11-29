<?php

namespace App\Http\Controllers;

use App\Model\FinancyRefundDirAnalyze;
use App\Model\FinancyLendingDirAnalyze;
use App\Model\Users;
use App\Model\UserDebtors;
use App\Model\LogAccess;
use App\Model\Countries;
use App\Model\Regions;
use App\Model\Trips;
use App\Model\TripPlan;
use App\Model\TripPeoples;
use App\Model\TripAnalyze;
use App\Model\TripAgency;
use App\Model\TripAgencyBudget;
use App\Model\UserOnPermissions;
use App\Model\Permissions;
use App\Model\UserImmediate;
use App\Model\Notifications;
use App\Model\TripAgencyChat;
use App\Model\TripPlanCredit;
use App\Model\TripBudgetFiles;
use App\Model\ProjectResponsible;
use App\Model\ProjectHistory;
use App\Model\ProjectAnalyze;
use App\Model\ListTransmission;
use App\Model\Guests;

use App\Model\ProductAirUnity;
use App\Model\Voltages;
use App\Model\ProductCategory;
use App\Model\ProductAir;
use App\Model\ProductControl;
use App\Model\ProductSubLevel1;
use App\Model\ProductSubLevel2;
use App\Model\ProductSubLevel3;

use App\Model\ProductParts;
use App\Model\Parts;

use App\Model\Task;
use App\Model\TaskResponsible;
use App\Model\TaskHistory;
use App\Model\TaskAnalyze;
use App\Model\TaskAnalyzeCompleted;
use App\Model\TaskCopyContact;
use App\Model\TaskSub;

use App\Model\UserFinancy;
use App\Model\FinancyLending;
use App\Model\FinancyLendingAttach;
use App\Model\FinancyLendingFnyAnalyze;
use App\Model\FinancyLendingMngAnalyze;
use App\Model\FinancyLendingPresAnalyze;
use App\Model\FinancyRPayment;
use App\Model\FinancyRPaymentNf;
use App\Model\FinancyRPaymentAttach;
use App\Model\FinancyRefund;
use App\Model\FinancyRefundItem;
use App\Model\FinancyRefundItemAttach;

use App\Model\FinancyRefundMngAnalyze;
use App\Model\FinancyRefundFnyAnalyze;
use App\Model\FinancyRefundPresAnalyze;

use App\Model\FinancyRPaymentMngAnalyze;
use App\Model\FinancyRPaymentFnyAnalyze;
use App\Model\FinancyRPaymentPresAnalyze;

use App\Model\FinancyAccountability;
use App\Model\FinancyAccountabilityItem;
use App\Model\FinancyAccountabilityAttach;
use App\Model\FinancyAccountabilityObservationHistory;
use App\Model\FinancyAccountabilityReceiverHistory;

use App\Model\Settings;
use App\Model\ChangeLogs;
use App\Model\Sector;
use App\Model\Sector2;
use App\Model\Sector3;
use App\Model\Project;
use App\Model\BlogPost;
use App\Model\BlogPostAttach;
use App\Model\BlogCategory;
use App\Model\BlogAuthor;
use App\Model\BlogPostIt;
use App\Model\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Jobs\SendMailAttachJob;
use App\Jobs\SacAlertOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Hash;
use App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Log;
use Intervention\Image\ImageManager;
use App\Exports\TripPlanExport;
use App\Exports\ProjectExport;
use App\Exports\LendingExport;
use App\Exports\SurveyDailyExport;
use App\Exports\SacExpeditionExport;
use App\Exports\PaymentExport;
use App\Exports\DefaultExport;

use App\Imports\PartsImport;

use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Charts\SacChart;
use App\Model\TaskDay;
use App\Events\EventSocket;
use App\Model\ChatGroup;
use App\Model\ChatMessage;
use App\Model\ChatPm;
use App\Model\UserSurvey;
use App\Model\UserSurveyAnswer;
use App\Model\SurveyNotifyUser;
use App\Model\Survey;
use App\Model\SurveyQuestions;

use App\Model\SacProtocol;
use App\Model\SacModelProtocol;
use App\Model\SacPartProtocol;
use App\Model\SacAuthorized;
use App\Model\SacClient;
use App\Model\SacMsgProtocol;
use App\Model\SacOsProtocol;
use App\Model\SacExpeditionRequest;
use App\Model\SacBuyPart;
use App\Model\SacFaq;
use App\Model\SacBuyParts;
use App\Model\SacShop;
use App\Model\SacShopParts;
use App\Model\SacType;
use App\Model\SacAuthorizedType;
use App\Model\Representation;
use App\Model\SacModelOs;
use App\Model\SacMsgOs;

use App\Model\SacAuthorizedHistoric;
use App\Model\SacAuthorizedNotify;

use App\Model\TiBacklog;
use App\Model\TiBacklogHistory;
use App\Model\TiBacklogMngAnalyze;
use App\Model\TiBacklogDirAnalyze;

use App\Model\Qrcode;
use App\Model\QrcodeAnalistAnalyze;
use App\Model\QrcodeMngAnalyze;
use App\Helpers\ModuleRules;
use App\Helpers\RulesFinancyLending;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

use App\Model\AdmRequestAnalyze;
use App\Model\AdmRequestFiles;
use App\Model\AdmRequestObservers;
use App\Model\AdmRequests;
use App\Model\SacRemittancePart;
use App\Model\SacRemittanceParts;

use App\Services\Departaments\Administration\Trip\TripPlan as TripPlanAnalyze;
use App\Services\Departaments\ProcessAnalyze;
use \App\Http\Controllers\Services\FileManipulationTrait;
use Carbon\Carbon;

class UserIController extends Controller
{

    use FileManipulationTrait;

    public function login(Request $request) {

        return view('gree_i.login');
    }

    public function systemStatus() {

        return view('gree_i.status');
    }

    public function notificationsToken(Request $request, $type) {
        $token = $request->Input('token');

        $user = Users::find($request->session()->get('user_id'));

        if ($user) {
            if ($type == 1) {
                $user->token = $token;
            } else {
                $user->token_mobile = $token;
            }
            $user->save();
        }
    }

    public function active2FAUser(Request $request) {
        $user = Users::find($request->session()->get('user_id'));

        if ($user) {

            if ($request->active_otp == 1) {
                $code = $user->id .''. rand(1000, 9999);

                // get first letter of each word
                $words = explode(" ", $user->last_name);
                $lastname = "";

                $i = 0;
                $len = count($words);
                foreach ($words as $w) {

                    if (strlen($w) > 2) {
                        if ($i == $len - 1) {
                            $lastname .= $w[0];
                        } else {
                            $lastname .= $w[0] .".";
                        }
                    } else {
                        $i++;
                        continue;
                    }
                    $i++;
                }

                $source = array('(', ')', ' ');
                $replace = array('', '', '');
                $fname = str_replace($source, $replace, $user->first_name);
                $name = remove_accents($fname ."". strtoupper($lastname));
                $result = optAuthGoogleAuthentication($code, $name, 'gree.app');

                $user->otpauth = $result->secret;
                $user->save();
                return response()->json([
                    "success" => true,
                    "html" => $result->qr,
                ], 200);
            } else {

                $user->otpauth = null;
                $user->save();

                return response()->json([
                    'success' => true,
                    'html' => ""
                ], 200);
            }
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível encontrar o usuário'
            ], 400);
        }
    }

    public function notificationsGet(Request $request) {
        $notify = Notifications::where('r_code', $request->session()->get('r_code'))->where('has_read', 0)->orderBy('id', 'DESC')->paginate(4);
        $total = Notifications::where('r_code', $request->session()->get('r_code'))->where('has_read', 0)->orderBy('id', 'DESC')->count();

        $inc = 0;
        $data_notify = array();
        foreach ($notify as $key) {
            $data_notify[$inc][0] = $key->icon;
            $data_notify[$inc][1] = $key->type;
            $data_notify[$inc][2] = $key->code;
            $data_notify[$inc][3] = $key->url;
            $data_notify[$inc][4] = date('Y-m-d H:i', strtotime($key->created_at));
            $data_notify[$inc][5] = $key->has_read;
            $data_notify[$inc][6] = $key->title;
            $data_notify[$inc][7] = $key->id;

            $inc++;
        }

        return response()->json([
            'success' => true,
            'inc' => $inc,
            'notify' => $data_notify,
            'notify_count' => $total,
        ]);
    }

    public function notificationsRead(Request $request) {
        $notify = Notifications::where('r_code', $request->session()->get('r_code'))->where('has_read', 0)->orderBy('id', 'DESC')->get();

        foreach ($notify as $key) {
            $s_notify = Notifications::find($key->id);
            $s_notify->has_read = 1;
            $s_notify->save();
        }

        return response()->json([
            'success' => true,
        ]);

    }

    public function notificationsReadOnly(Request $request) {

        $s_notify = Notifications::find($request->id);
        $s_notify->has_read = 1;
        $s_notify->save();
        return response()->json([
            'success' => true,
        ]);
    }

    public function notificationsChangeStatus(Request $request, $id, $status) {

        $notify = Notifications::find($id);

        if ($notify) {
            $notify->has_read = $status;
            $notify->save();

            LogSystem("Colaborador mudou status da notificação id: ". $id, $request->session()->get('r_code'));

            $request->session()->put('success', 'Notificação atualizada com sucesso!');
            return redirect()->back();
        }
        else {
            $request->session()->put('success', 'Notificação não encontrada!');
            return redirect()->back();
        }
    }

    public function verifyLogin(Request $request) {
        $r_code = $request->input('r_code');
        $password = $request->input('password');

        $user = Users::where('r_code', $r_code)->first();
        if ($user) {
            if (Hash::check($password, $user->password)) {

                if ($user->is_active == 0) {

                    // Write Log
                    LogSystem("Colaborador bloqueado pela administração, está tentando acessar o sistema.", $user->r_code);
                    return response()->json([
                        'success' => false,
                        'msg' => 'You have been locked in our system, talk to admin.'
                    ], 200);

                } else {

                    if ($user->otpauth == null) {
                        // Write Log
                        LogSystem("Colaborador conseguiu realizar o acesso.", $user->r_code);
                        $request->session()->put('user_id', $user->id);
                        $request->session()->put('r_code', $user->r_code);
                        $request->session()->put('is_holiday', $user->is_holiday);
                        $request->session()->put('my_holiday_perm', \App\Model\UserHoliday::where('receiver_r_code', $user->r_code)->where('date_end', '>', date('Y-m-d H:i:s'))->get());
                        $request->session()->put('picture', $user->picture);
                        $request->session()->put('first_name', $user->first_name);
                        $request->session()->put('last_name', $user->last_name);
                        $request->session()->put('sector', $user->sector_id);
                        $request->session()->put('email', $user->email);
                        $request->session()->put('user_version', $user->version);
                        $request->session()->put('permissoes_usuario', ($user->permissoes_usuario));
                        $request->session()->put('filter_line', ($user->filter_line));

                        $has_survey = Survey::where('is_active', 1)->first();
                        if ($has_survey) {
                            $survey = UserSurvey::where('user_r_code', $user->r_code)->orderBy('id', 'DESC')->first();
                            if ($survey) {
                                if (date('Y-m-d', strtotime($survey->created_at)) >= date('Y-m-d')) {
                                    $request->session()->put('s_report', 1);
                                } else {
                                    $request->session()->put('s_report', 0);
                                }
                            } else {
                                $request->session()->put('s_report', 0);
                            }
                        } else {
                            $request->session()->put('s_report', 1);
                        }

                        $user->retry = 3;
                        $user->save();

                        $request->session()->put('lang', $user->lang);
                        if ($request->session()->get('url')) {
                            $url = $request->session()->get('url');
                            $request->session()->put('url', '');

                            return response()->json([
                                'success' => true,
                                'url' => $url,
                            ], 200);
                        } else {
                            return response()->json([
                                'success' => true,
                                'url' => '/news',
                            ], 200);
                        }
                    } else {

                        $request->session()->put('otpauth', $user->otpauth);
                        $request->session()->put('temp_r_code', $user->r_code);

                        return response()->json([
                            'success' => true,
                            'url' => ''
                        ], 200);
                    }
                }
            } else {

                if ($user->retry > 0) {
                    $user->retry = $user->retry - 1;

                    if ($user->retry == 0) {

                        $user->retry_time = date('Y-m-d H:i:s');
                        $user->is_active = 0;
                        $user->save();

                        // Write Log
                        LogSystem("Colaborador foi bloqueado no sistema por usar todas as suas tentativas.", $user->r_code);
                        return response()->json([
                            'success' => false,
                            'msg' => 'You have been locked in our system, talk to admin.'
                        ], 200);
                    } else {

                        $user->retry_time = date('Y-m-d H:i:s');
                        $user->save();
                        // Write Log
                        LogSystem("Colaborador tentou acessar e errou sua senha. Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                        return response()->json([
                            'success' => false,
                            'msg' => 'Registration or password incorrect. You have '. $user->retry .' attemp(s)'
                        ], 200);
                    }
                } else {

                    // Write Log
                    LogSystem("Colaborador está tentando acesso, mesmo já tendo sido bloqueado!", $user->r_code);
                    return response()->json([
                        'success' => false,
                        'msg' => 'You have been locked in our system, talk to admin.'
                    ], 200);
                }
            }
        } else {

            // Write Log
            LogSystem("Colaborador não identificado, tentou acessar o sistema.", 0);
            return response()->json([
                'success' => false,
                'msg' => 'You do not have an account yet.'
            ], 200);
        }

    }

    public function verifyOtpAuth(Request $request) {

        if ($request->pin) {

            $result = optAuthGoogleAuthenticationVerify($request->session()->get('otpauth'), $request->pin);

            if ($result == 'true') {

                $user = Users::where('r_code', $request->session()->get('temp_r_code'))->first();

                LogSystem("Colaborador conseguiu realizar o acesso com autenticador.", $user->r_code);
                $request->session()->put('user_id', $user->id);
                $request->session()->put('r_code', $user->r_code);
                $request->session()->put('is_holiday', $user->is_holiday);
                $request->session()->put('my_holiday_perm', \App\Model\UserHoliday::where('receiver_r_code', $user->r_code)->where('date_end', '>', date('Y-m-d H:i:s'))->get());
                $request->session()->put('picture', $user->picture);
                $request->session()->put('first_name', $user->first_name);
                $request->session()->put('last_name', $user->last_name);
                $request->session()->put('sector', $user->sector_id);
                $request->session()->put('email', $user->email);
                $request->session()->put('user_version', $user->version);
                $request->session()->put('permissoes_usuario', ($user->permissoes_usuario));
                $request->session()->put('filter_line', ($user->filter_line));

                $has_survey = Survey::where('is_active', 1)->first();
                if ($has_survey) {
                    $survey = UserSurvey::where('user_r_code', $user->r_code)->orderBy('id', 'DESC')->first();
                    if ($survey) {
                        if (date('Y-m-d', strtotime($survey->created_at)) >= date('Y-m-d')) {
                            $request->session()->put('s_report', 1);
                        } else {
                            $request->session()->put('s_report', 0);
                        }
                    } else {
                        $request->session()->put('s_report', 0);
                    }
                } else {
                    $request->session()->put('s_report', 1);
                }

                $user->retry = 3;
                $user->save();

                $request->session()->put('lang', $user->lang);
                if ($request->session()->get('url')) {
                    $url = $request->session()->get('url');
                    $request->session()->put('url', '');

                    return response()->json([
                        'success' => true,
                        'url' => $url,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => true,
                        'url' => '/news',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'Your verification code is invalid!'
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'Field "Código de autenticação" is required!'
            ], 200);
        }
    }
	
	public function wdgtCalendarEventsJsonEvents(Request $request) {

        $postits = BlogPostIt::where('r_code', $request->session()->get('r_code'))
            ->whereBetween('date_notify', [$request->start_date, $request->end_date])
            ->get();

        $data = [];
        foreach ($postits as $key) {
            if (!isset($data[date('Y-m-d', strtotime($key->date_notify))])) {
                $data[date('Y-m-d', strtotime($key->date_notify))] = array([
                    'description' => $key->description,
                    'priority' => $key->priority
                ]);
            } else {
                array_push($data[date('Y-m-d', strtotime($key->date_notify))], [
                    'description' => $key->description,
                    'priority' => $key->priority
                ]);
            }
        }

        if (count($data))
            return response()->json($data);
        else
            return null;
    }

    public function news(Request $request) {

        $posts = BlogPost::where('is_publish', 1)->orderBy('id', 'DESC')->paginate(8);
        $postits = BlogPostIt::where('r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC')->paginate(4);
		
		$wdget_approv = [];
        if (Storage::disk('wdget_approv')->exists($request->session()->get('user_id').'.json')) {
            $wdget_approv = json_decode(Storage::disk('wdget_approv')->get($request->session()->get('user_id').'.json'));
        }
        
        return view('gree_i.blog.news', [
            'posts' => $posts,
            'postits' => $postits,
			'wdget_approv' => $wdget_approv
        ]);
    }
	
	public function newsPostItEdit_do(Request $request) {

        try {

            if($request->postit_id == 0) {
                $postit = new BlogPostIt;
            } else { 
                $postit = BlogPostIt::find($request->postit_id);
            }

            $postit->description = $request->description;
            $postit->priority = $request->priority;
            $postit->r_code = $request->session()->get('r_code');
            $postit->is_notify = $request->is_notify;
            $postit->date_notify = $request->is_notify == 1 ? date('Y-m-d', strtotime(str_replace('/', '-', $request->date_notify))) : null;
			$postit->url_notify = $request->notify_url;
            $postit->save();

            if($request->is_notify == 0 && $request->job_id != null) {
                Jobs::where('id', $request->job_id)->delete();
            }

            if($postit->is_notify == 1) {

				$format_date = str_replace('/', '-', $request->date_notify);
				$hours = date('H:i:s', strtotime('5 minute'));
				$date_convert = date('Y-m-d '.$hours, strtotime($format_date));
                //$date_notify = new Carbon(Carbon::createFromFormat('d/m/Y', $request->date_notify)->format('Y-m-d'));
                //$seconds_notify = $date_actual->diffInSeconds($date_notify);

                $arr_priority = [
                    1 => 'Baixa',
                    2 => 'Média',
                    3 => 'Alta',
                    4 => 'Crítica'
                ];

                $pattern = array(
                    'title' => 'Lembrete / Aviso',
					'notify_r_code' => $request->session()->get('r_code'),
                    'notify_url' => $request->notify_url ? $request->notify_url : '#',
                    'description' => nl2br($postit->description.
                                            "\n Prioridade: ".$arr_priority[$request->priority].
                                            "\n Data criação: ". date('d/m/Y', strtotime($postit->created_at)).
                                            "\n\nVeja mais informações no link abaixo: \n <a href='".$request->root()."/news'>".$request->root()."/news</a>"),

                    'template' => 'misc.Default',
                    'subject' => 'Lembrete: '. strWordCut($postit->description, 35),
                );

                SendMailJob::dispatch($pattern, $request->session()->get('email'), true)->delay(Carbon::parse($date_convert));
                
                $job = DB::table('jobs')->select('id')->orderBy('id', 'DESC')->first();
                $postit->job_id = $job->id;
                $postit->save();
				
            }

            return redirect()->back()->with('success', 'Lembrete cadastrado com sucesso!');

        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
	
	public function newsPostItNext(Request $request) {

        $postit = BlogPostIt::where('r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC')->paginate(4);

        return response()->json([
            'postit' => $postit
        ], 200);
    }
	
	public function newsPostItDelete(Request $request, $id) {

        $postit = BlogPostIt::find($id);
        if(!$postit) {
            return redirect()->back()->with('error', 'Lembrete não encontrado!');
        } else {
            $job = Jobs::find($postit->job_id);
            if($job) {
                $job->delete();
            }
            $postit->delete();
            return redirect()->back()->with('success', 'Lembrete deletado com sucesso!');
        }
    }

    public function newsPostItEdit(Request $request) {

        $postit = BlogPostIt::find($request->id);
        if(!$postit) {
            return response()->json([
                'success' => false,
                'message' => 'Lembrete não encontrado!'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'postit' => $postit
        ], 200);
    }    

    public function postsSegmentAll(Request $request) {

        $post = BlogPost::where('is_publish', 1);

        $array_input = collect([
            'setor',
        ]);

        // SAVE FILTERS
        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."setor"){

                    if ($valor_filtro == 1) {
                        $post->where('category_id', 7)
                            ->orWhere(function ($query) {
                                $query->where('is_publish', 1)
                                    ->where('category_id', 6);
                            });

                    } else if ($valor_filtro == 2) {
                        $post->where('category_id', 100);

                    } else if ($valor_filtro == 3) {
                        $post->where('category_id', 2);

                    } else if ($valor_filtro == 4) {
                        $post->where('category_id', 1);

                    } else if ($valor_filtro == 5) {
                        $post->where('category_id', 3);

                    } else {
                        $post->leftJoin('sector','blog_post.category_id','=','sector.id')
                            ->select('blog_post.*', 'sector.name')
                            ->where('blog_post.category_id', '!=', 2)
                            ->where('blog_post.category_id', '!=', 1)
                            ->where('blog_post.category_id', '!=', 3)
                            ->where('blog_post.category_id', '!=', 6)
                            ->where('blog_post.category_id', '!=', 7)
                            ->where('blog_post.category_id', '!=', 100);
                    }
                }
            }
        }

        return view('gree_i.blog.blog_segment_all', [
            'post' => $post->orderBy('id', 'DESC')->paginate(10)
        ]);
    }

    public function postsSegment(Request $request, $type) {

        if ($type == 1) {
            $post = BlogPost::where('is_publish', 1)
                ->where('category_id', 7)
                ->orWhere(function ($query) {
                    $query->where('is_publish', 1)
                        ->where('category_id', 6);
                })
                ->orderBy('id', 'DESC')
                ->paginate(10);
        } else if ($type == 2) {
            $post = BlogPost::where('is_publish', 1)
                ->where('category_id', 100)
                ->orderBy('id', 'DESC')
                ->paginate(10);

        } else if ($type == 3) {
            $post = BlogPost::where('is_publish', 1)
                ->where('category_id', 2)
                ->orderBy('id', 'DESC')
                ->paginate(10);

        } else if ($type == 4) {
            $post = BlogPost::where('is_publish', 1)
                ->where('category_id', 1)
                ->orderBy('id', 'DESC')
                ->paginate(10);

        } else if ($type == 5) {
            $post = BlogPost::where('is_publish', 1)
                ->where('category_id', 3)
                ->orderBy('id', 'DESC')
                ->paginate(10);

        } else {
            $post = BlogPost::leftJoin('sector','blog_post.category_id','=','sector.id')
                ->select('blog_post.*', 'sector.name')
                ->where('blog_post.category_id', '!=', 2)
                ->where('blog_post.category_id', '!=', 1)
                ->where('blog_post.category_id', '!=', 3)
                ->where('blog_post.category_id', '!=', 6)
                ->where('blog_post.category_id', '!=', 7)
                ->where('blog_post.category_id', '!=', 100)
                ->where('is_publish', 1)
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }

        return view('gree_i.blog.blog_segment', [
            'post' => $post,
            'type' => $type
        ]);
    }

    public function postSingle(Request $request, $id) {
        $category = $request->input('category');
        $post = BlogPost::find($id);
        $post_ct = BlogPost::count();
        $categories = BlogCategory::leftJoin('blog_post','blog_category.id','=','blog_post.category_id')
            ->select(DB::raw('count(blog_post.id) as post_total, blog_category.*'))
            ->groupBy('blog_category.id')
            ->where('blog_category.is_visible', 1)
            ->get();
        $attachs = BlogPostAttach::where('blog_post_id', $id)->get();

        if ($post) {

            return view('gree_i.blog.single', [
                'attachs' => $attachs,
                'post' => $post,
                'post_ct' => $post_ct,
                'categories' => $categories,
                'category' => $category,
            ]);
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function authorEdit(Request $request) {

        $id = $request->Input('id');
        $r_code = $request->Input('r_code');
        $sector = $request->Input('sector');

        $author = BlogAuthor::find($id);

        if ($author) {

            $author->r_code = $r_code;
            $author->category_id = $sector;
            $author->save();

            LogSystem("Colaborador atualizou author com identificação: ". $author->id, $request->session()->get('r_code'));
        } else {


            if (BlogAuthor::where('r_code', $r_code)->where('category_id', $sector)->count() == 0) {
                $author = new BlogAuthor;
                $author->r_code = $r_code;
                $author->category_id = $sector;
                $author->save();
            } else {
                $request->session()->put('error', "O autor já existe nessa categoria.");
                return Redirect('/blog/author/all');
            }

            LogSystem("Colaborador criou um novo author com identificação: ". $author->id, $request->session()->get('r_code'));
        }

        $request->session()->put('success', "Lista de autores atualizada com sucesso!");
        return Redirect('/blog/author/all');
    }

    public function transmissionList(Request $request) {

        // SAVE FILTERS
        if (!empty($request->input('email'))) {
            $request->session()->put('blogf_email', $request->input('email'));
        } else {
            $request->session()->forget('blogf_email');
        }

        $list = ListTransmission::orderBy('id', 'DESC');

        if (!empty($request->session()->get('blogf_email'))) {
            $list->where('email', 'like', '%'. $request->session()->get('blogf_email') .'%');
        }

        return view('gree_i.blog.blog_transmission', [
            'list' => $list->paginate(10),
        ]);
    }

    public function transmissionDelete(Request $request, $id) {
        $list = ListTransmission::find($id);

        if ($list) {

            ListTransmission::where('id', $id)->delete();
            LogSystem("Colaborador fez a exclusão do email da lista de transmissão com identificação: ". $id, $request->session()->get('r_code'));
            $request->session()->put('success', "Você realizou a exclusão do email da lista de transmissão com sucesso!");
            return Redirect('/blog/transmission');

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function transmissionEdit(Request $request) {

        $id = $request->Input('id');
        $email = $request->Input('email');

        $list = ListTransmission::find($id);

        if ($list) {

            $list->email = $email;
            $list->save();

            LogSystem("Colaborador atualizou lista de transmissão com identificação: ". $list->id, $request->session()->get('r_code'));
        } else {


            if (ListTransmission::where('email', $email)->first()) {

                $request->session()->put('error', "O email já existe na lista de transmissão.");
                return Redirect('/blog/transmission');
            } else if (Users::where('email', $email)->first()) {

                $request->session()->put('error', "O email já existe no cadastro de usuários.");
                return Redirect('/blog/transmission');
            } else {
                $list = new ListTransmission;
                $list->email = $email;
                $list->save();
            }

            LogSystem("Colaborador criou um novo email para lista de transmissão com identificação: ". $list->id, $request->session()->get('r_code'));
        }

        $request->session()->put('success', "Lista de transmissão atualizada com sucesso!");
        return Redirect('/blog/group/transmission');
    }

    public function authorList(Request $request) {

        $author = BlogAuthor::leftJoin('sector','blog_author.category_id','=','sector.id')
            ->select('blog_author.*', 'sector.name')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $userall = Users::all();
        $sectors = Sector::all();

        return view('gree_i.blog.blog_author_all', [
            'author' => $author,
            'userall' => $userall,
            'sectors' => $sectors,
        ]);
    }

    public function authorDelete(Request $request, $id) {
        $author = BlogAuthor::find($id);

        if ($author) {

            BlogAuthor::where('id', $id)->delete();
            LogSystem("Colaborador fez a exclusão do author com identificação: ". $id, $request->session()->get('r_code'));
            $request->session()->put('success', "Você realizou a exclusão do autor com sucesso!");
            return Redirect('/blog/author/all');

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function blogEdit(Request $request, $id) {

        $author = BlogAuthor::leftJoin('sector','blog_author.category_id','=','sector.id')
            ->select('blog_author.*', 'sector.name')
            ->where('r_code', $request->session()->get('r_code'))
            ->get();

        if (count($author) > 0) {

            $request->session()->put('temp_id', $id);
            if ($id == 0) {
                $post = new BlogPost;

                $title_pt = '';
                $title_en = '';
                $description_pt = '';
                $description_en = '';
                $picture = '';
                $attachs = '';
                $category = '';
                $is_publish = 1;
                $caution = 1;

            } else {
                $post = BlogPost::find($id);

                if ($id) {

                    $has_cat = 0;
                    foreach ($author as $key) {
                        if ($post->category_id == $key->category_id) {
                            $has_cat = 1;
                        }
                    }
                    if ($has_cat == 0) {

                        $request->session()->put('error', 'Você não é um autor dessa categoria para publicar.');
                        return Redirect('/news');
                    }
                    $title_pt = $post->title_pt;
                    $title_en = $post->title_en;
                    $description_pt = $post->description_pt;
                    $description_en = $post->description_en;
                    $picture = $post->picture;
                    $attachs = BlogPostAttach::where('blog_post_id', $post->id)->get();
                    $category = $post->category_id;
                    $is_publish = $post->is_publish;
                    $caution = $post->caution;

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            }

            $sector = Sector::all();

            return view('gree_i.blog.blog_edit', [
                'id' => $id,
                'author' => $author,
                'title_pt' => $title_pt,
                'title_en' => $title_en,
                'description_pt' => $description_pt,
                'description_en' => $description_en,
                'picture' => $picture,
                'attachs' => $attachs,
                'is_publish' => $is_publish,
                'caution' => $caution,
                'category' => $category,
                'sector' => $sector,
            ]);

        } else {

            $request->session()->put('error', 'Você não é um autor para publicar.');
            return Redirect('/news');
        }
    }

    public function blogUpdate(Request $request) {
        $picture = $request->file('picture');
        $picture = $request->picture;
        $attach = $request->file('attach');

        $post = BlogPost::find($request->session()->get('temp_id'));

        if (!$post) {
            $post = new BlogPost;
        }

            $post->title_pt = $request->input('title_pt');
            $post->title_en = $request->input('title_en');
            $post->description_pt = $request->input('description_pt');
            $post->description_en = $request->input('description_en');
            $post->category_id = $request->input('category');
            $post->r_code = $request->session()->get('r_code');
            $post->is_publish = $request->input('is_publish');
            $post->caution = $request->input('caution');

            if ($request->hasFile('picture')) {
                $extension = $request->picture->extension();
                if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {
    
                    $validator = Validator::make(
                                [
                                'picture' => $picture,
                                ],
                                [
                                'picture' => 'required|max:1000',
                                ]
                    );
    
                    if ($validator->fails()) { 
    
                        $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                        return Redirect('/blog/edit/'. $request->session()->get('temp_id'));
                    } else {
    
                        $img_name = '1-'. date('YmdHis') .'.'. $extension;
                        
                        $request->picture->storeAs('/', $img_name, 's3');
                        $url = Storage::disk('s3')->url($img_name);
                        $post->picture = $url;
                    }
                    
                } else {
    
                    $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                    return Redirect('/blog/edit/'. $request->session()->get('temp_id'));
                }
            }

            $post->save();
            if ($attach) {
                $i = 1;
                BlogPostAttach::where('blog_post_id', $post->id)->delete();
                foreach ($attach as $f_attach) {
                    $post_attachs = new BlogPostAttach;
                    $post_attachs->name = $f_attach->getClientOriginalName();
                    $post_attachs->size = $f_attach->getSize();
                    $post_attachs->blog_post_id = $post->id;
    
                    $img_name = $i .'-'. date('YmdHis') .'.'. $f_attach->extension();
                    $f_attach->storeAs('/', $img_name, 's3');
                    $url = Storage::disk('s3')->url($img_name);
                    $post_attachs->url = $url;
                    $post_attachs->save();
                    $i++;
                }
            }

            if ($post->is_publish == 1) {

                $pattern = array(
                    'id' => $post->id,
                    'image' => $post->picture,
                    'title' => $post->title_pt,
                    'description' => $post->description_pt,
                    'template' => 'blog.Publish',
                    'subject' => $post->title_pt,
                );

                // send email
                if ($request->notify == 2 or $request->notify == 3 or $request->notify == 4) {

					$place = [
						// Manaus
						3 => [2],
						// São Paulo
						4 => [3], 
						// Todos
						2 => [2,3]];
						
                    DB::table('users')->where('is_active', 1)->whereIn('gree_id', $place[$request->notify])->orderBy('id', 'DESC')->chunk(100, function($users) use ($pattern, $attach)
                    {
                        foreach ($users as $user)
                        {
                            delayQueueEmail($pattern, $user->email);
                            //if ($attach) {
                            //    SendMailAttachJob::dispatch($pattern, $user->email);
                            //} else {
                            //    SendMailJob::dispatch($pattern, $user->email);
                            //}
                            
                        }
                    });

                    DB::table('list_transmission')->orderBy('id', 'DESC')->chunk(100, function($transmissions) use ($pattern, $attach)
                    {
                        foreach ($transmissions as $transmission)
                        {
							delayQueueEmail($pattern, $transmission->email);
                            //if ($attach) {
                            //    SendMailAttachJob::dispatch($pattern, $transmission->email);
                            //} else {
                            //    SendMailJob::dispatch($pattern, $transmission->email);
                            //}
                            
                        }
                    });

                    DB::table('user_notification_external')->orderBy('id', 'DESC')->chunk(100, function($users_external) use ($post) {

                        foreach($users_external as $user) {
                            
                            if ($user->token) {
                                $fields = array(
                                    'notification' => array(
                                        'title' => $post->title_pt,
                                        'body' => strWordCut($post->description_pt, 65),
                                        'click_action' => 'https://gree.com.br/novidades', // Alterar link
                                        'icon' => 'https://gree-app.com.br/media/favicons/apple-touch-icon-180x180.png'
                                    ),
                                    'to'  => $user->token,
                                );
                                sendPushNotification($fields);
                            }
                        }
                    });

                } else if ($request->notify == 1) {

                    DB::table('users')->where('sector_id', $post->category_id)->where('is_active', 1)->orderBy('id', 'DESC')->chunk(100, function($users) use ($pattern, $attach)
                    {
                        foreach ($users as $user)
                        {
							delayQueueEmail($pattern, $user->email);
                            //if ($attach) {
                            //    SendMailAttachJob::dispatch($pattern, $user->email);
                           // } else {
                            //    SendMailJob::dispatch($pattern, $user->email);
                            //}
                        }
                    });
                }
            }

            if ($request->session()->get('temp_id') == 0) {
                LogSystem("Colaborador criou uma nova publicação no blog", $request->session()->get('r_code'));
                $request->session()->put('success', "Nova publicação criada com sucesso!.");

            } else {
                LogSystem("Colaborador atualizou publicação no blog", $request->session()->get('r_code'));
                $request->session()->put('success', "Publicação atualizada com sucesso!.");
            }
            
            return Redirect('/blog/view/all');
    }

    public function blogCategoryAll() {

        $categories = BlogCategory::leftJoin('blog_post','blog_category.id','=','blog_post.category_id')
            ->select(DB::raw('count(blog_post.id) as post_total, blog_category.*'))
            ->groupBy('blog_category.id')
            ->paginate(10);

        return view('gree_i.blog.categories_list', [
            'categories' => $categories,
        ]);
    }

    public function blogCategoryUpdate(Request $request) {
        $id = $request->Input('id');
        $name_pt = $request->Input('name_pt');
        $name_en = $request->Input('name_en');
        $isvisible = $request->Input('isvisible');

        $cat = BlogCategory::find($id);

        if ($cat) {

            $cat->name_pt = $name_pt;
            $cat->name_en = $name_en;
            $cat->is_visible = $isvisible == 1 ? 1: 0;
            $cat->save();

            LogSystem("Colaborador atualizou categoria com identificação: ". $id, $request->session()->get('r_code'));
        } else {

            $cat = new BlogCategory;
            $cat->name_pt = $name_pt;
            $cat->name_en = $name_en;
            $cat->is_visible = $isvisible == 1 ? 1: 0;
            $cat->save();

            LogSystem("Colaborador criou uma nova categoria com identificação: ". $id, $request->session()->get('r_code'));
        }

        $request->session()->put('success', "Lista de categorias atualizadas com sucesso!");
        return Redirect('/blog/view/categories');
    }

    public function blogAll(Request $request) {

        $post = BlogPost::orderBy('id', 'DESC')->paginate(10);

        return view('gree_i.blog.blog_all', [
            'post' => $post,
        ]);
    }

    public function blogDelete(Request $request, $id) {
        $post = BlogPost::find($id);

        if ($post) {

            BlogPost::where('id', $id)->delete();
            $request->session()->put('success', "Você realizou a exclusão dessa publicação com sucesso!");
            return Redirect('/blog/view/all');

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }
	
	public function  tripView(Request $request) {

        $trips = TripPlan::where('has_analyze', 1)->ValidAnalyzeProccess($request->session()->get('r_code'))->orderBy('id', 'DESC');
        return view('gree_i.trip.trip_view', [
            'trips' => $trips->paginate(10),
            'url' => '/trip/analyze/update'
        ]);
    }

    public function tripView_(Request $request) {
        $error = $request->input('error');
        $success = $request->input('success');

        if ($request->session()->get('r_code') != '0004' and $request->session()->get('r_code') != '0005' and $request->session()->get('r_code') != '1842') {
            $trips = TripPlan::leftJoin('trips','trip_plan.trip_id','=','trips.id')
                ->leftJoin('users','trips.r_code','=','users.r_code')
                ->leftJoin('user_immediate','users.r_code','=','user_immediate.user_r_code')
                ->select('trip_plan.*', 'users.first_name', 'users.last_name', 'users.picture', 'users.r_code')
                ->where('trip_plan.has_analyze', 1)
                ->where('trip_plan.origin_date', '>', date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 day')))
                ->where('user_immediate.immediate_r_code', $request->session()->get('r_code'))
                ->groupBy('trip_plan.id')
                ->paginate(10);
        } else {
            $trips = TripPlan::leftJoin('trips','trip_plan.trip_id','=','trips.id')
                ->leftJoin('users','trips.r_code','=','users.r_code')
                ->select('trip_plan.*', 'users.first_name', 'users.last_name', 'users.picture', 'users.r_code')
                ->where('trip_plan.has_analyze', 1)
                ->groupBy('trip_plan.id')
                ->paginate(10);
        }

        return view('gree_i.trip.trip_view', [
            'error' => $error,
            'success' => $success,
            'trips' => $trips,
        ]);


    }

    public function tripViewApprov(Request $request) {
        $error = $request->input('error');
        $success = $request->input('success');

        // SAVE FILTERS
        if (!empty($request->input('r_code'))) {
            $request->session()->put('tripf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('tripf_r_code');
        }
        if (!empty($request->input('start_date'))) {
            $request->session()->put('tripf_start_date', $request->input('start_date'));
        } else {
            $request->session()->forget('tripf_start_date');
        }
        if (!empty($request->input('end_date'))) {
            $request->session()->put('tripf_end_date', $request->input('end_date'));
        } else {
            $request->session()->forget('tripf_end_date');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('tripf_status', $request->input('status'));
        } else {
            $request->session()->forget('tripf_status');
        }

        $userall = Users::all();

        $total = TripPlan::where('is_approv', 1)->where('is_completed', 0)->where('is_cancelled', 0)->count();
        $left3 = TripPlan::where('origin_date', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 day')))->where('is_approv', 1)->where('is_completed', 0)->where('is_cancelled', 0)->count();
        $left7 = TripPlan::where('origin_date', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 day')))->where('is_approv', 1)->where('is_completed', 0)->where('is_cancelled', 0)->count();
        $news = TripPlan::where('origin_date', '>=', date('Y-m-d', strtotime(date('Y-m-d'). ' + 8 day')))->where('is_approv', 1)->where('is_completed', 0)->where('is_cancelled', 0)->count();

        $trips = TripPlan::leftJoin('trips','trip_plan.trip_id','=','trips.id')
            ->leftJoin('users','trips.r_code','=','users.r_code')
            ->select('trip_plan.*', 'users.first_name', 'users.last_name', 'users.picture', 'users.r_code')
            ->groupBy('trip_plan.id');

        if (!empty($request->session()->get('tripf_r_code'))) {
            $trips->where('trips.r_code', $request->session()->get('tripf_r_code'));
        }
        if (!empty($request->session()->get('tripf_start_date'))) {
            $trips->where('trip_plan.origin_date', '>=', $request->session()->get('tripf_start_date'));
        }
        if (!empty($request->session()->get('tripf_end_date'))) {
            $trips->where('trip_plan.origin_date', '<=', $request->session()->get('tripf_end_date'));
        }
        if (!empty($request->session()->get('tripf_status'))) {
            if ($request->session()->get('tripf_status') == 1) {
                $trips->where('trip_plan.is_completed', 1);
            } else if ($request->session()->get('tripf_status') == 2) {
                $trips->where('trip_plan.is_completed', 0)
					->where('trip_plan.is_cancelled', 0)
                    ->where('trip_plan.is_approv', 1);
            } else if ($request->session()->get('tripf_status') == 3) {
                $trips->where('trip_plan.is_completed', 0)
                    ->where('trip_plan.is_reprov', 1);
            } else if ($request->session()->get('tripf_status') == 4) {
                $trips->where('trip_plan.has_analyze', 1);
            } else if ($request->session()->get('tripf_status') == 5) {
                $trips->where('trip_plan.is_completed', 0)
                    ->where('trip_plan.is_reprov', 0)
                    ->where('trip_plan.is_approv', 0);
            }
        }

        return view('gree_i.trip.trip_view_approv', [
            'total' => $total,
            'left3' => $left3,
            'left7' => $left7,
            'news' => $news,
            'error' => $error,
            'success' => $success,
            'userall' => $userall,
            'trips' => $trips->orderBy('trip_plan.id', 'DESC')->paginate(10),
        ]);
    }

    public function tripCancelManager(Request $request, $id) {

        $trip_plan = TripPlan::find($id);

        if ($trip_plan) {

            $trip_plan->is_cancelled = $trip_plan->is_cancelled == 1 ? 0 : 1;
            $trip_plan->has_analyze = 0;
            $trip_plan->save();

            $request->session()->put('success', 'Status da rota, foi alterado com sucesso!');
            return redirect()->back();

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }

    }

    public function tripEditManager(Request $request, $id) {

        $trip_plan = TripPlan::find($id);

        if ($trip_plan) {

            $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->get();
            $trip_budget = TripAgencyBudget::where('trip_plan_id', $id)->where('is_approv', 1)->first();
            $agencys = TripAgency::all();

            return view('gree_i.trip.trip_edit_manager', [
                'agencys' => $agencys,
                'trip_plan' => $trip_plan,
                'trip_budget' => $trip_budget,
                'peoples' => $peoples,
            ]);

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }

    }

    public function tripEditManager_do(Request $request, $id) {

        $trip_plan = TripPlan::find($id);
        $total = $request->Input('total');
        $status = $request->Input('status');
        $is_notify = $request->Input('is_notify');
        $ticket = $request->ticket;
        $hotel = $request->hotel;

        if ($trip_plan) {
            if ($status == 1) {
                $trip_plan->is_completed = 1;
                $trip_plan->is_cancelled = 0;
                $trip_plan->is_credit = 0;
            } else if ($status == 2) {
                $trip_plan->is_completed = 0;
                $trip_plan->is_cancelled = 1;
                $trip_plan->is_credit = 0;
            } else if ($status == 3) {
                $trip_plan->is_completed = 1;
                $trip_plan->is_credit = 1;
                $trip_plan->is_cancelled = 0;
            }

            $trip_plan->save();

            if ($total) {
                $budget = TripAgencyBudget::where('trip_plan_id', $trip_plan->id)->where('is_approv', 1)->first();

                if ($budget) {
                    if ($total) {
                        $budget->total = $total;
                    }
                } else {
                    $budget = new TripAgencyBudget;
                    $budget->is_approv = 1;
                    $budget->trip_plan_id = $trip_plan->id;
                    $budget->agency_id = 15;
                    $budget->total = $total;
                }


                if ($request->hasFile('ticket')) {
                    $extension = $request->ticket->extension();
                    if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                        $validator = Validator::make(
                            [
                                'ticket' => $ticket,
                            ],
                            [
                                'ticket' => 'required|max:1000',
                            ]
                        );

                        if ($validator->fails()) {

                            $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                            return Redirect()->back();
                        } else {

                            $img_ticket = '2-'. date('YmdHis') .'-'. $id .'.'. $extension;

                            $request->ticket->storeAs('/', $img_ticket, 's3');
                            $url = Storage::disk('s3')->url($img_ticket);
                            $budget->ticket_url = $url;
                        }

                    } else {

                        $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                        return Redirect()->back();
                    }
                }

                if ($request->hasFile('hotel')) {
                    $extension = $request->hotel->extension();
                    if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                        $validator = Validator::make(
                            [
                                'hotel' => $hotel,
                            ],
                            [
                                'hotel' => 'required|max:1000',
                            ]
                        );

                        if ($validator->fails()) {

                            $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                            return Redirect()->back();
                        } else {

                            $img_ticket = '4-'. date('YmdHis') .'-'. $id .'.'. $extension;

                            $request->hotel->storeAs('/', $img_ticket, 's3');
                            $url = Storage::disk('s3')->url($img_ticket);
                            $budget->ticket_hotel = $url;
                        }

                    } else {

                        $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                        return Redirect()->back();
                    }
                }


                $budget->save();

                if ($status == 3) {
                    $credit = new TripPlanCredit;
                    $credit->trip_plan_id = $trip_plan->id;
                    $credit->agency_id = $budget->agency_id;
                    $credit->total = $total;
                    $credit->save();
                }

                if ($is_notify == 1) {

                    $t_r = Trips::where('id', $trip_plan->trip_id)->first();

                    $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->get();

                    $pattern = array(
                        'attach' => $budget->ticket_url,
                        'attach_hotel' => $budget->ticket_hotel,
                        'name' => getENameFull($t_r->r_code),
                        'id' => $trip_plan->id,
                        'r_code' => $t_r->r_code,
                        'finality' => finalityName($trip_plan->finality),
                        'goal' => $trip_plan->goal,
                        'origin_country' => GetCountryName($trip_plan->origin_country),
                        'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                        'origin_city' => $trip_plan->origin_city,
                        'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                        'destiny_country' => GetCountryName($trip_plan->destiny_country),
                        'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                        'destiny_city' => $trip_plan->destiny_city,
                        'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                        'has_hotel' => $trip_plan->has_hotel,
                        'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                        'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                        'dispatch' => $trip_plan->dispatch,
                        'dispatch_reason' => $trip_plan->dispatch_reason,
                        'peoples' => count($peoples),
                        'peoples_ticket' => $peoples,
                        'title' => 'BILHETE DE VIAGEM',
                        'description' => 'Seu bilhete de viagem está no link acima, use-o para ir, caso precise alterar o bilhete, entre em contato com administração e aguarde o próximo email com o novo bilhete!',
                        'template' => 'trip.RequestApprovAttach',
                        'subject' => 'Bilhete da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                    );

                    $user = Users::where('r_code', $t_r->r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);

                    App::setLocale($user->lang);
                    NotifyUser(__('layout_i.n_trip_004_title'), $user->r_code, 'fa-cloud-download-alt', 'text-success', __('layout_i.n_trip_004'), $request->root() .'/trip/review/'. $trip_plan->id);
                    App::setLocale($request->session()->get('lang'));
                }
            }

            return Redirect('/trip/edit/route/'. $id);
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function tripMngPeoples(Request $request, $id) {

        $people = $request->people;
        $identity = $request->identity;

        $p_plan = TripPeoples::where('trip_plan_id', $id)->where('identity', $identity)->first();

        if ($p_plan) {

            if ($request->hasFile('people')) {
                $extension = $request->people->extension();
                if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                    $validator = Validator::make(
                        [
                            'people' => $people,
                        ],
                        [
                            'people' => 'required|max:1000',
                        ]
                    );

                    if ($validator->fails()) {

                        $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                        return Redirect('/trip/edit/route/'. $id);
                    } else {

                        $img_ticket = '4-'. date('YmdHis') .'-'. $id .'.'. $extension;

                        $request->people->storeAs('/', $img_ticket, 's3');
                        $url = Storage::disk('s3')->url($img_ticket);
                        $p_plan->ticket_url = $url;
                        $p_plan->save();
                    }

                } else {

                    $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                    return Redirect('/trip/edit/route/'. $id);
                }

                $request->session()->put('success', "Bilhete de viagem do passageiro atualizado.");
            }

            return Redirect('/trip/edit/route/'. $id);

        } else {

            $request->session()->put('error', "Usuário não encontrado!");
            return Redirect('/trip/edit/route/'. $id);
        }

    }

    public function tripAnalyze(Request $request, $rcode, $plan) {
        $error = $request->input('error');
        $success = $request->input('success');

        $trip = TripPlan::leftJoin('trips','trip_plan.trip_id','=','trips.id')
            ->leftJoin('users','trips.r_code','=','users.r_code')
            ->select('trip_plan.*', 'users.r_code')
            ->where('trip_plan.id', $plan)
            ->first();

        if ($trip) {
            $adm = UserOnPermissions::where('user_r_code', $rcode)->where('perm_id', 1)->where('can_approv', 1)->first();
            if ($adm or hasPermApprov(1)) {
                $user = UserOnPermissions::where('user_r_code', $trip->r_code)->first();
                if ($user) {

                    $is_analyze = $trip->has_analyze;

                    $request->session()->put('temp_id', $plan);
                    $request->session()->put('temp_rcode', $rcode);
                    return view('gree_i.trip.trip_analyze', [
                        'error' => $error,
                        'success' => $success,
                        'trip' => $trip,
                        'is_analyze' => $is_analyze,
                    ]);

                } else {
                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return \Redirect::route('news');
                }
            } else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return \Redirect::route('news');
            }
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }
    }

    public function tripDashboard(Request $request) {

        $last_year = date('Y') - 1;

        if (!empty($request->input('r_code'))) {
            $request->session()->put('tripdf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('tripdf_r_code');
        }

        if ($request->session()->get('r_code') != '0004' and $request->session()->get('r_code') != '0005' and $request->session()->get('r_code') != '1842') {
            $request->session()->put('tripdf_r_code', $request->session()->get('r_code'));
        }

        if ($request->session()->get('tripdf_r_code')) {
            $cfy_actual = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                    ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->whereMonth('trip_plan.origin_date', $i)
                    ->whereYear('trip_plan.origin_date', date('Y'))
                    ->sum('trip_agency_budget.total');

                $total = $total == null ? 0 : $total;
                $cfy_actual->push(number_format($total, 2, '.', ''));
            }

            $amount_actual = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                ->where('trip_plan.is_completed', 1)
                ->where('trip_plan.is_cancelled', 0)
                ->whereYear('trip_plan.origin_date', date('Y'))
                ->sum('trip_agency_budget.total');

            $cfy_last = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                    ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->whereMonth('trip_plan.origin_date', $i)
                    ->whereYear('trip_plan.origin_date', $last_year)
                    ->sum('trip_agency_budget.total');
                $total = $total == null ? 0 : $total;
                $cfy_last->push(number_format($total, 2, '.', ''));
            }

            $amount_last = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                ->where('trip_plan.is_completed', 1)
                ->where('trip_plan.is_cancelled', 0)
                ->whereYear('trip_plan.origin_date', $last_year)
                ->sum('trip_agency_budget.total');

            $compareFlightYear = new SacChart;
            $compareFlightYear->labels(['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']);
            $compareFlightYear->dataset('2019', 'line', $cfy_last)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(71, 148, 207, 0.2)");
            $compareFlightYear->dataset('2020', 'line', $cfy_actual)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(110, 174, 224)");

            $borderColors = [
                "rgba(255, 99, 132, 1.0)",
                "rgba(22,160,133, 1.0)",
                "rgba(255, 205, 86, 1.0)",
                "rgba(51,105,232, 1.0)",
                "rgba(244,67,54, 1.0)",
                "rgba(34,198,246, 1.0)",
                "rgba(153, 102, 255, 1.0)",
                "rgba(255, 159, 64, 1.0)",
                "rgba(233,30,99, 1.0)",
                "rgba(205,220,57, 1.0)"
            ];
            $fillColors = [
                "rgba(255, 99, 132, 0.2)",
                "rgba(22,160,133, 0.2)",
                "rgba(255, 205, 86, 0.2)",
                "rgba(51,105,232, 0.2)",
                "rgba(244,67,54, 0.2)",
                "rgba(34,198,246, 0.2)",
                "rgba(153, 102, 255, 0.2)",
                "rgba(255, 159, 64, 0.2)",
                "rgba(233,30,99, 0.2)",
                "rgba(205,220,57, 0.2)"

            ];

            $fay_actual = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {

                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                    ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->where('trip_plan.finality', $fin)
                    ->whereYear('trip_plan.origin_date', date('Y'))
                    ->sum('trip_agency_budget.total');
                $total = $total == null ? 0 : $total;
                $fay_actual->push(number_format($total, 2, '.', ''));
            }


            $fay_last = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {
                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                    ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->where('trip_plan.finality', $fin)
                    ->whereYear('trip_plan.origin_date', $last_year)
                    ->sum('trip_agency_budget.total');
                $total = $total == null ? 0 : $total;
                $fay_last->push(number_format($total, 2, '.', ''));
            }

            $finalityAmountYear = new SacChart;
            $finalityAmountYear->minimalist(true);
            $finalityAmountYear->labels([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);
            $finalityAmountYear->dataset('2019', 'bar', $fay_last)
                ->color($borderColors)
                ->backgroundcolor($fillColors);
            $finalityAmountYear->dataset('2020', 'bar', $fay_actual)
                ->color($borderColors)
                ->backgroundcolor($fillColors);


            $fac_actual = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {

                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                    ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->where('trip_plan.finality', $fin)
                    ->whereYear('trip_plan.origin_date', date('Y'))
                    ->count('trip_plan.finality');
                $total = $total == null ? 0 : $total;
                $fac_actual->push($total);
            }

            $finalityAmountCount = new SacChart;
            $finalityAmountCount->minimalist(true);
            $finalityAmountCount->labels([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);
            $finalityAmountCount->dataset('Status', 'doughnut', $fac_actual)
                ->color($borderColors)
                ->backgroundcolor($fillColors);


            $fac_last = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {

                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::leftJoin('trips', 'trip_plan.trip_id', '=', 'trips.id')
                    ->where('trips.r_code', $request->session()->get('tripdf_r_code'))
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->where('trip_plan.finality', $fin)
                    ->whereYear('trip_plan.origin_date', $last_year)
                    ->count('trip_plan.finality');
                $total = $total == null ? 0 : $total;
                $fac_last->push($total);
            }

            $finalityAmountCountL = new SacChart;
            $finalityAmountCountL->minimalist(true);
            $finalityAmountCountL->labels([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);
            $finalityAmountCountL->dataset('Status', 'doughnut', $fac_last)
                ->color($borderColors)
                ->backgroundcolor($fillColors);

        } else {

            $cfy_actual = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->whereMonth('trip_plan.origin_date', $i)
                    ->whereYear('trip_plan.origin_date', date('Y'))
                    ->sum('trip_agency_budget.total');

                $total = $total == null ? 0 : $total;
                $cfy_actual->push(number_format($total, 2, '.', ''));
            }

            $amount_actual = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                ->where('trip_plan.is_completed', 1)
                ->where('trip_plan.is_cancelled', 0)
                ->whereYear('trip_plan.origin_date', date('Y'))
                ->sum('trip_agency_budget.total');

            $cfy_last = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->whereMonth('trip_plan.origin_date', $i)
                    ->whereYear('trip_plan.origin_date', $last_year)
                    ->sum('trip_agency_budget.total');
                $total = $total == null ? 0 : $total;
                $cfy_last->push(number_format($total, 2, '.', ''));
            }

            $amount_last = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                ->where('trip_plan.is_completed', 1)
                ->where('trip_plan.is_cancelled', 0)
                ->whereYear('trip_plan.origin_date', $last_year)
                ->sum('trip_agency_budget.total');

            $compareFlightYear = new SacChart;
            $compareFlightYear->labels(['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']);
            $compareFlightYear->dataset('2019', 'line', $cfy_last)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(71, 148, 207, 0.2)");
            $compareFlightYear->dataset('2020', 'line', $cfy_actual)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(110, 174, 224)");

            $borderColors = [
                "rgba(255, 99, 132, 1.0)",
                "rgba(22,160,133, 1.0)",
                "rgba(255, 205, 86, 1.0)",
                "rgba(51,105,232, 1.0)",
                "rgba(244,67,54, 1.0)",
                "rgba(34,198,246, 1.0)",
                "rgba(153, 102, 255, 1.0)",
                "rgba(255, 159, 64, 1.0)",
                "rgba(233,30,99, 1.0)",
                "rgba(205,220,57, 1.0)"
            ];
            $fillColors = [
                "rgba(255, 99, 132, 0.2)",
                "rgba(22,160,133, 0.2)",
                "rgba(255, 205, 86, 0.2)",
                "rgba(51,105,232, 0.2)",
                "rgba(244,67,54, 0.2)",
                "rgba(34,198,246, 0.2)",
                "rgba(153, 102, 255, 0.2)",
                "rgba(255, 159, 64, 0.2)",
                "rgba(233,30,99, 0.2)",
                "rgba(205,220,57, 0.2)"

            ];

            $fay_actual = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {

                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->where('trip_plan.finality', $fin)
                    ->whereYear('trip_plan.origin_date', date('Y'))
                    ->sum('trip_agency_budget.total');
                $total = $total == null ? 0 : $total;
                $fay_actual->push(number_format($total, 2, '.', ''));
            }


            $fay_last = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {
                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::leftJoin('trip_agency_budget', 'trip_plan.id', '=', 'trip_agency_budget.trip_plan_id')
                    ->where('trip_plan.is_completed', 1)
                    ->where('trip_plan.is_cancelled', 0)
                    ->where('trip_plan.finality', $fin)
                    ->whereYear('trip_plan.origin_date', $last_year)
                    ->sum('trip_agency_budget.total');
                $total = $total == null ? 0 : $total;
                $fay_last->push(number_format($total, 2, '.', ''));
            }

            $finalityAmountYear = new SacChart;
            $finalityAmountYear->minimalist(true);
            $finalityAmountYear->labels([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);
            $finalityAmountYear->dataset('2019', 'bar', $fay_last)
                ->color($borderColors)
                ->backgroundcolor($fillColors);
            $finalityAmountYear->dataset('2020', 'bar', $fay_actual)
                ->color($borderColors)
                ->backgroundcolor($fillColors);


            $fac_actual = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {

                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::where('is_completed', 1)
                    ->where('is_cancelled', 0)
                    ->where('finality', $fin)
                    ->whereYear('origin_date', date('Y'))
                    ->count('finality');
                $total = $total == null ? 0 : $total;
                $fac_actual->push($total);
            }

            $finalityAmountCount = new SacChart;
            $finalityAmountCount->minimalist(true);
            $finalityAmountCount->labels([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);
            $finalityAmountCount->dataset('Status', 'doughnut', $fac_actual)
                ->color($borderColors)
                ->backgroundcolor($fillColors);


            $fac_last = collect([]);
            for ($fin=1; $fin <= 10; $fin++) {

                $fin = $fin == 10 ? 99 : $fin;
                $total = TripPlan::where('is_completed', 1)
                    ->where('is_cancelled', 0)
                    ->where('finality', $fin)
                    ->whereYear('origin_date', $last_year)
                    ->count('finality');
                $total = $total == null ? 0 : $total;
                $fac_last->push($total);
            }

            $finalityAmountCountL = new SacChart;
            $finalityAmountCountL->minimalist(true);
            $finalityAmountCountL->labels([__('trip_i.finality_1'), __('trip_i.finality_2'), __('trip_i.finality_3'), __('trip_i.finality_4'), __('trip_i.finality_5'), __('trip_i.finality_6'), __('trip_i.finality_7'), __('trip_i.finality_8'), __('trip_i.finality_9'), __('trip_i.finality_99')]);
            $finalityAmountCountL->dataset('Status', 'doughnut', $fac_last)
                ->color($borderColors)
                ->backgroundcolor($fillColors);
        }

        $userall = Users::all();

        return view('gree_i.trip.trip_dashboard', [
            'compareFlightYear' => $compareFlightYear,
            'finalityAmountYear' => $finalityAmountYear,
            'finalityAmountCount' => $finalityAmountCount,
            'finalityAmountCountL' => $finalityAmountCountL,
            'amount_actual' => $amount_actual,
            'amount_last' => $amount_last,
            'userall' => $userall
        ]);
    }

    public function tripNew(Request $request) {
        $error = $request->input('error');
        $success = $request->input('success');

        $country = Countries::orderBy('name')->get();

        return view('gree_i.trip.trip_new', ['error' => $error, 'success' => $success, 'country' => $country]);
    }

    public function tripEdit(Request $request, $id) {
        $error = $request->input('error');
        $success = $request->input('success');

        $trip = Trips::where('id', $id)->first();

        if ($trip) {

            $country = Countries::orderBy('name')->get();
            $trips = TripPlan::where('trip_id', $id)->get();

            return view('gree_i.trip.trip_edit', ['error' => $error, 'success' => $success, 'country' => $country, 'trips' => $trips, 'id' => $id]);

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }
    }

    public function tripUpdate(Request $request, $id) {

        // JSON
        $raw_payload = $request->input('data_input');
        $payload = json_decode($raw_payload, true);

        if (!empty($payload)) {

            $trip = Trips::where('id', $id)->first();

            TripPlan::where('trip_id', $id)->where('is_approv', 0)->delete();
            if ($trip) {

                if (count($payload) > 0) {
                    LogSystem("Colaborador atualizou seu planejamento de viagem, identificado por: ". $id, $request->session()->get('r_code'));
                    foreach ($payload as $key) {

                        if ($key['is_approv'] == 1) {
                            $trip_plan = TripPlan::find($key['id']);
                        } else {

                            $trip_plan = new TripPlan;
                        }

                        $trip_plan->trip_id = $trip->id;
                        $trip_plan->finality = $key['finality'];
                        $trip_plan->other = $key['other'];
                        $trip_plan->goal = $key['goal'];
                        $trip_plan->origin_date = $key['flight']['origin_date'];
                        $trip_plan->origin_period = $key['flight']['origin_period'];
                        $trip_plan->origin_country = $key['flight']['origin_country'];
                        $trip_plan->origin_state = $key['flight']['origin_state'];
                        $trip_plan->origin_city = $key['flight']['origin_city'];
                        $trip_plan->destiny_date = $key['flight']['destiny_date'];
                        $trip_plan->destiny_period = $key['flight']['destiny_period'];
                        $trip_plan->destiny_country = $key['flight']['destiny_country'];
                        $trip_plan->destiny_state = $key['flight']['destiny_state'];
                        $trip_plan->destiny_city = $key['flight']['destiny_city'];
                        $trip_plan->dispatch = $key['flight']['dispatch'];
                        $trip_plan->dispatch_reason = $key['flight']['dispatch_info'];
                        $trip_plan->has_hotel = $key['has_hotel'];
                        if ($key['has_hotel'] == 1) {
                            $trip_plan->hotel_date = $key['hotel']['enter_date'];
                            $trip_plan->hotel_checkout = $key['hotel']['checkout'];
                            $trip_plan->hotel_country = $key['hotel']['enter_country'];
                            $trip_plan->hotel_state = $key['hotel']['enter_state'];
                            $trip_plan->hotel_city = $key['hotel']['enter_city'];
                            $trip_plan->hotel_exit = $key['hotel']['exit_date'];
                            $trip_plan->hotel_address = $key['hotel']['address'];
                        } else {
                            $trip_plan->hotel_date = date('Y-m-d H:i:s');
                            $trip_plan->hotel_exit = date('Y-m-d H:i:s');
                        }
                        $trip_plan->is_approv = $key['is_approv'];
                        $trip_plan->is_reprov = $key['is_reprov'];
                        $trip_plan->has_analyze = $key['has_analyze'];
                        $trip_plan->save();

                        TripPeoples::where('trip_plan_id', $trip_plan->id)->delete();
                        foreach ($key['peoples'] as $people) {

                            $trip_peoples = new TripPeoples;

                            $trip_peoples->trip_plan_id = $trip_plan->id;
                            $trip_peoples->name = $people['name'];
                            $trip_peoples->identity = $people['r_code'];
                            $trip_peoples->save();
                        }

                    }
                } else {

                    $request->session()->put('error', 'Não foi possível atualizar sua viagem, atualize a página.');
                    return redirect()->back();
                }

                return redirect('/trip/detail/'. $trip->id);

            }  else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return \Redirect::route('news');
            }
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }

    }

    public function tripExportView(Request $request) {

        $userid = $request->session()->get('r_code');
        $country = Countries::orderBy('name')->get();
        $manager = UserOnPermissions::where('perm_id', 1)
            ->where('user_r_code', $userid)
            ->where('grade', 99)
            ->orWhere(function ($query) use ($userid) {
                $query->where('user_r_code', $userid)
                    ->where('perm_id', 1)
                    ->where('can_approv', 1);
            })
            ->first();

        $colab = Users::all();

        return view('gree_i.trip.trip_export', [
            'country' => $country,
            'manager' => $manager,
            'colab' => $colab
        ]);
    }

    public function tripExport(Request $request) {

        $userid = $request->session()->get('r_code');
        $manager = UserOnPermissions::where('perm_id', 1)
            ->where('user_r_code', $userid)
            ->where('grade', 99)
            ->orWhere(function ($query) use ($userid) {
                $query->where('user_r_code', $userid)
                    ->where('perm_id', 1)
                    ->where('can_approv', 1);
            })
            ->first();

        if (isset($manager)) {
            $r_code = $request->Input('r_code');
        } else {
            $r_code = $request->session()->get('r_code');
        }
        $finality = $request->Input('finality');
        $start_d = $request->Input('start_date');
        $end_d = $request->Input('end_date');
        $start_country = $request->Input('start_country');
        $start_state = $request->Input('start_state');
        $end_country = $request->Input('end_country');
        $end_state = $request->Input('end_state');
        $hotel = $request->Input('hotel');
        $status = $request->Input('status');

        LogSystem("Colaborador exportou dados da tabela viagem: ", $request->session()->get('r_code'));

        $name = $r_code != "" ? $r_code : date('Y-m-d H:i:s');

        return Excel::download(new TripPlanExport($r_code, $finality, $start_country, $start_state, $end_country, $end_state, $hotel, $status, $start_d, $end_d), 'Plan_'. $name .'.xlsx');

    }

    public function tripMy(Request $request) {
        $error = $request->input('error');
        $success = $request->input('success');

        $tp_fly_ct = DB::table('trips')->leftJoin('trip_plan', 'trips.id', '=', 'trip_plan.trip_id')
            ->where('trips.r_code', $request->session()->get('r_code'))
            ->where('trip_plan.is_completed', 1)
            ->where('trip_plan.origin_date', '<', date('Y-m-d'))
            ->GroupBy('trip_plan.id')
            ->count();

        $tp_nfly_ct = DB::table('trips')->leftJoin('trip_plan', 'trips.id', '=', 'trip_plan.trip_id')
            ->where('trips.r_code', $request->session()->get('r_code'))
            ->where('trip_plan.is_completed', 0)
            ->where('trip_plan.origin_date', '>', date('Y-m-d'))
            ->GroupBy('trip_plan.id')
            ->count();

        $trips = DB::table('trips')
            ->leftJoin('trip_plan', 'trips.id', '=', 'trip_plan.trip_id')
            ->select(DB::raw('count(trip_plan.id) as routes, SUM(trip_plan.dispatch) as dispatch, trips.id, trips.created_at, trips.is_completed'))
            ->where('trips.r_code', $request->session()->get('r_code'))
            ->GroupBy('trips.id')
            ->orderBy('trips.id', 'DESC')
            ->paginate(10);

        return view('gree_i.trip.trip_my_view', [
            'error' => $error,
            'success' => $success,
            'trips' => $trips,
            'tp_fly_ct' => $tp_fly_ct,
            'tp_nfly_ct' => $tp_nfly_ct
        ]);
    }

    public function tripAll(Request $request) {

        $trips = DB::table('trips')
            ->leftJoin('trip_plan', 'trips.id', '=', 'trip_plan.trip_id')
            ->leftJoin('users', 'trips.r_code', '=', 'users.r_code')
            ->select(DB::raw('count(trip_plan.id) as routes, SUM(trip_plan.dispatch) as dispatch, trips.id, trips.created_at, trips.is_completed, users.picture, trips.r_code'))
            ->GroupBy('trips.id')
            ->orderBy('trips.id', 'DESC')
            ->paginate(10);

        // SAVE FILTERS
        if (!empty($request->input('r_code'))) {
            $request->session()->put('tripf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('tripf_r_code');
        }
        if (!empty($request->input('start_date'))) {
            $request->session()->put('tripf_start_date', $request->input('start_date'));
        } else {
            $request->session()->forget('tripf_start_date');
        }
        if (!empty($request->input('end_date'))) {
            $request->session()->put('tripf_end_date', $request->input('end_date'));
        } else {
            $request->session()->forget('tripf_end_date');
        }

        $userall = Users::all();

        $trips = DB::table('trips')
            ->leftJoin('trip_plan', 'trips.id', '=', 'trip_plan.trip_id')
            ->leftJoin('users', 'trips.r_code', '=', 'users.r_code')
            ->select(DB::raw('count(trip_plan.id) as routes, SUM(trip_plan.dispatch) as dispatch, trips.id, trips.created_at, trips.is_completed, users.picture, trips.r_code'))
            ->GroupBy('trips.id')
            ->orderBy('trips.id', 'DESC');

        if (!empty($request->session()->get('tripf_r_code'))) {
            $trips->where('trips.r_code', $request->session()->get('tripf_r_code'));
        }
        if (!empty($request->session()->get('tripf_start_date'))) {
            $trips->where('trip_plan.origin_date', '>=', $request->session()->get('tripf_start_date'));
        }
        if (!empty($request->session()->get('tripf_end_date'))) {
            $trips->where('trip_plan.origin_date', '<=', $request->session()->get('tripf_end_date'));
        }

        return view('gree_i.trip.trip_all_view', [
            'trips' => $trips->paginate(10),
            'userall' => $userall
        ]);
    }

    public function tripDetail(Request $request, $id) {
        $error = $request->input('error');
        $success = $request->input('success');

        $trip = Trips::where('id', $id)->first();

        if ($trip) {

            $trips = DB::table('trip_plan')
                ->leftJoin('trip_peoples', 'trip_plan.id', '=', 'trip_peoples.trip_plan_id')
                ->select(DB::raw('SUM(trip_plan.dispatch) as dispatch, count(trip_peoples.id) as peoples, trip_plan.*'))
                ->where('trip_plan.trip_id', $id)
                ->GroupBy('trip_plan.id')
                ->paginate(10);

            return view('gree_i.trip.trip_detail', ['error' => $error, 'success' => $success, 'trips' => $trips, 'planid' => $id]);

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }
    }
	
	public function tripAnalyze_do(Request $request) {

        try {

            foreach($request->check as $key) {

                $trip = TripPlan::with('rtd_analyze.users')->find($key);
                if(!$trip)
                    return redirect()->back()->with('error', 'Solicitação não encontrada!');
                
                $solicitation = new TripPlanAnalyze($trip, $request);
                $do_analyze = new ProcessAnalyze($solicitation);

                $actions = [
                    1 => 'eventApprov',
                    2 => 'eventReprov',
                    3 => 'eventSuspended',
                    4 => 'eventRevert'
                ];
        
                $method = $actions[$solicitation->request->type];
                $result = $do_analyze->$method();
            }    

            return redirect()->back()->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
	
	public function tripAnalyzeSingle_do(Request $request) {

        try {

            $trip = TripPlan::with('rtd_analyze.users')->find($request->id);
            if(!$trip)
                return redirect()->back()->with('error', 'Solicitação não encontrada!');
            
            $solicitation = new TripPlanAnalyze($trip, $request);
            $do_analyze = new ProcessAnalyze($solicitation);

            $actions = [
                1 => 'eventApprov',
                2 => 'eventReprov',
                3 => 'eventSuspended',
                4 => 'eventRevert'
            ];
    
            $method = $actions[$solicitation->request->type];
            $result = $do_analyze->$method();

            return redirect('/trip/view')->with('success', $result);

        } catch (\Exception $e) {
            return redirect('/trip/view')->with('error', $e->getMessage());
        }
    }  

    public function tripAnalyze_do_(Request $request) {
        $reason = $request->input('reason');
        $password = $request->input('password');
        $is_approv = $request->input('is_approv');
        $check = $request->input('check');

        if (!empty($check)) {
            $is_approv = 1;
            for ($i=0; $i < count($check); $i++) {
                $trip_plan = TripPlan::find($check[$i]);

                if ($trip_plan) {

                    $t_r = Trips::where('id', $trip_plan->trip_id)->first();
                    $user = Users::where('r_code', $request->session()->get('r_code'))->first();

                    $trip_plan->has_analyze = 0;
                    $trip_plan->is_approv = 1;
                    $trip_plan->is_reprov = 0;
                    $trip_plan->save();

                    $analyze = new TripAnalyze;
                    $analyze->description = $reason;
                    $analyze->trip_plan_id = $check[$i];
                    $analyze->r_code = $request->session()->get('r_code');

                    $peoples = TripPeoples::where('trip_plan_id', $check[$i])->count();

                    $subject_analyze = "";
                    $title_analyze = "";
                    $desc_analyze = "";

                    if ($is_approv == 1) {

                        $managers = UserOnPermissions::where('perm_id', 1)->where('grade', 99)->get();

                        if (count($managers) > 0) {

                            LogSystem("Colaborador aprovou solicitação de voo: ". $check[$i], $request->session()->get('r_code'));

                            $pattern = array(
                                'name' => getENameFull($t_r->r_code),
                                'id' => $trip_plan->id,
                                'r_code' => $t_r->r_code,
                                'finality' => finalityName($trip_plan->finality),
                                'goal' => $trip_plan->goal,
                                'origin_country' => GetCountryName($trip_plan->origin_country),
                                'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                                'origin_city' => $trip_plan->origin_city,
                                'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                                'destiny_country' => GetCountryName($trip_plan->destiny_country),
                                'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                                'destiny_city' => $trip_plan->destiny_city,
                                'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                                'has_hotel' => $trip_plan->has_hotel,
                                'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                                'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                                'dispatch' => $trip_plan->dispatch,
                                'dispatch_reason' => $trip_plan->dispatch_reason,
                                'peoples' => $peoples,
                                'title' => 'PEDIDO FOI APROVADO',
                                'description' => 'Realize a cotação desse pedido de viagem através do seu painel! Utilize suas credenciais de acesso para poder realizar o procedimento.',
                                'template' => 'trip.RequestHasApprov',
                                'subject' => 'Realize a cotação da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                            );

                            foreach ($managers as $key) {
                                $mng = Users::where('r_code', $key->user_r_code)->first();

                                SendMailJob::dispatch($pattern, $mng->email);

                                App::setLocale($mng->lang);
                                NotifyUser(__('layout_i.n_trip_005_title'), $mng->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_005'), $request->root() .'/trip/review/'. $trip_plan->id);
                                App::setLocale($request->session()->get('lang'));
                            }
                        } else {

                            LogSystem("Colaborador aprovou solicitação de voo: ". $check[$i], $request->session()->get('r_code'));

                            $pattern = array(
                                'name' => getENameFull($t_r->r_code),
                                'id' => $trip_plan->id,
                                'r_code' => $t_r->r_code,
                                'finality' => finalityName($trip_plan->finality),
                                'goal' => $trip_plan->goal,
                                'origin_country' => GetCountryName($trip_plan->origin_country),
                                'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                                'origin_city' => $trip_plan->origin_city,
                                'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                                'destiny_country' => GetCountryName($trip_plan->destiny_country),
                                'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                                'destiny_city' => $trip_plan->destiny_city,
                                'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                                'has_hotel' => $trip_plan->has_hotel,
                                'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                                'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                                'dispatch' => $trip_plan->dispatch,
                                'dispatch_reason' => $trip_plan->dispatch_reason,
                                'peoples' => $peoples,
                                'title' => 'PEDIDO FOI APROVADO',
                                'description' => 'Realize a cotação desse pedido de viagem através do seu painel! Utilize suas credenciais de acesso para poder realizar o procedimento.',
                                'template' => 'trip.RequestHasApprov',
                                'subject' => 'Realize a cotação da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                            );

                            // GET EMAIL ADM GERAL
                            SendMailJob::dispatch($pattern, getConfig("email_adm"));

                        }


                        $title_analyze = "PEDIDO FOI APROVADO";
                        $desc_analyze = "Parabéns! Sua viagem foi aprovada, agora aguarde a cotação da viagem, assim que tivermos o bilhete no sistema, iremos te avisar.";


                    } else {

                        $title_analyze = "PEDIDO REPROVADO";
                        $desc_analyze = "Infelizmente sua viagem foi reprovada, acesse o sistema para ver o motivo da reprovação.";
                    }

                    $pattern = array(
                        'name' => getENameFull($t_r->r_code),
                        'id' => $trip_plan->id,
                        'r_code' => $t_r->r_code,
                        'finality' => finalityName($trip_plan->finality),
                        'goal' => $trip_plan->goal,
                        'origin_country' => GetCountryName($trip_plan->origin_country),
                        'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                        'origin_city' => $trip_plan->origin_city,
                        'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                        'destiny_country' => GetCountryName($trip_plan->destiny_country),
                        'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                        'destiny_city' => $trip_plan->destiny_city,
                        'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                        'has_hotel' => $trip_plan->has_hotel,
                        'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                        'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                        'dispatch' => $trip_plan->dispatch,
                        'dispatch_reason' => $trip_plan->dispatch_reason,
                        'peoples' => $peoples,
                        'title' => $title_analyze,
                        'description' => $desc_analyze,
                        'template' => 'trip.RequestHasApprov',
                        'subject' => $is_approv == 1 ? 'Sua viagem foi aprovada: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"' : 'Sua viagem foi reprovada: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                    );

                    $user = Users::where('r_code', $t_r->r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);
                    App::setLocale($user->lang);
                    if ($is_approv == 1) {
                        NotifyUser(__('layout_i.n_trip_001_title'), $user->r_code, 'fa-check', 'text-success', __('layout_i.n_trip_001'), $request->root() .'/trip/review/'. $trip_plan->id);
                    } else {
                        NotifyUser(__('layout_i.n_trip_002_title'), $user->r_code, 'fa-times', 'text-danger', __('layout_i.n_trip_002'), $request->root() .'/trip/review/'. $trip_plan->id);
                    }
                    App::setLocale($request->session()->get('lang'));


                    $analyze->is_approv = $is_approv == 1 ? 1 : 0;
                    $analyze->is_reprov = $is_approv == 0 ? 1 : 0;
                    $analyze->save();

                    $txt = $is_approv == 1 ? "aprovou" : "Reprovou";

                    LogSystem("Colaborador análisou a viagem e a ". $txt .", identificador da viagem: ". $check[$i], $request->session()->get('r_code'));



                } else {
                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return redirect('/news');
                }


            }

            $request->session()->put('success', "Aprovação realizada com sucesso!");
            return redirect('/trip/view');

        } else {
            $trip_plan = TripPlan::where('id', $request->session()->get('temp_id'))->first();

            if ($trip_plan) {

                $t_r = Trips::where('id', $trip_plan->trip_id)->first();
                $user = Users::where('r_code', $request->session()->get('temp_rcode'))->first();

                if (Hash::check($request->password, $user->password)) {

                    $trip_plan->has_analyze = 0;
                    $trip_plan->is_approv = $is_approv == 1 ? 1 : 0;
                    $trip_plan->is_reprov = $is_approv == 0 ? 1 : 0;
                    $trip_plan->save();

                    $analyze = new TripAnalyze;
                    $analyze->description = $reason;
                    $analyze->trip_plan_id = $request->session()->get('temp_id');
                    $analyze->r_code = $request->session()->get('temp_rcode');

                    $peoples = TripPeoples::where('trip_plan_id', $request->session()->get('temp_id'))->count();

                    $subject_analyze = "";
                    $title_analyze = "";
                    $desc_analyze = "";

                    if ($is_approv == 1) {

                        $managers = UserOnPermissions::where('perm_id', 1)->where('grade', 99)->get();

                        if ($managers) {

                            LogSystem("Colaborador aprovou solicitação de voo: ". $request->session()->get('temp_id'), $request->session()->get('temp_rcode'));

                            $pattern = array(
                                'name' => getENameFull($t_r->r_code),
                                'id' => $trip_plan->id,
                                'r_code' => $t_r->r_code,
                                'finality' => finalityName($trip_plan->finality),
                                'goal' => $trip_plan->goal,
                                'origin_country' => GetCountryName($trip_plan->origin_country),
                                'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                                'origin_city' => $trip_plan->origin_city,
                                'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                                'destiny_country' => GetCountryName($trip_plan->destiny_country),
                                'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                                'destiny_city' => $trip_plan->destiny_city,
                                'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                                'has_hotel' => $trip_plan->has_hotel,
                                'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                                'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                                'dispatch' => $trip_plan->dispatch,
                                'dispatch_reason' => $trip_plan->dispatch_reason,
                                'peoples' => $peoples,
                                'title' => 'PEDIDO FOI APROVADO',
                                'description' => 'Realize a cotação desse pedido de viagem através do seu painel! Utilize suas credenciais de acesso para poder realizar o procedimento.',
                                'template' => 'trip.RequestHasApprov',
                                'subject' => 'Realize a cotação da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                            );

                            foreach ($managers as $key) {
                                $mng = Users::where('r_code', $key->user_r_code)->first();

                                SendMailJob::dispatch($pattern, $mng->email);
                                App::setLocale($mng->lang);
                                NotifyUser(__('layout_i.n_trip_005_title'), $mng->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_005'), $request->root() .'/trip/review/'. $trip_plan->id);
                                App::setLocale($request->session()->get('lang'));
                            }
                        } else {

                            LogSystem("Colaborador aprovou solicitação de voo: ". $request->session()->get('temp_id'), $request->session()->get('temp_rcode'));

                            $pattern = array(
                                'name' => getENameFull($t_r->r_code),
                                'id' => $trip_plan->id,
                                'r_code' => $t_r->r_code,
                                'finality' => finalityName($trip_plan->finality),
                                'goal' => $trip_plan->goal,
                                'origin_country' => GetCountryName($trip_plan->origin_country),
                                'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                                'origin_city' => $trip_plan->origin_city,
                                'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                                'destiny_country' => GetCountryName($trip_plan->destiny_country),
                                'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                                'destiny_city' => $trip_plan->destiny_city,
                                'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                                'has_hotel' => $trip_plan->has_hotel,
                                'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                                'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                                'dispatch' => $trip_plan->dispatch,
                                'dispatch_reason' => $trip_plan->dispatch_reason,
                                'peoples' => $peoples,
                                'title' => 'PEDIDO FOI APROVADO',
                                'description' => 'Realize a cotação desse pedido de viagem através do seu painel! Utilize suas credenciais de acesso para poder realizar o procedimento.',
                                'template' => 'trip.RequestHasApprov',
                                'subject' => 'Realize a cotação da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                            );

                            // GET EMAIL ADM GERAL
                            SendMailJob::dispatch($pattern, getConfig("email_adm"));

                        }


                        $title_analyze = "PEDIDO FOI APROVADO";
                        $desc_analyze = "Parabéns! Sua viagem foi aprovada, agora aguarde a cotação da viagem, assim que tivermos o bilhete no sistema, iremos te avisar.";


                    } else {

                        $title_analyze = "PEDIDO REPROVADO";
                        $desc_analyze = "Infelizmente sua viagem foi reprovada, acesse o sistema para ver o motivo da reprovação.";
                    }

                    $pattern = array(
                        'name' => getENameFull($t_r->r_code),
                        'id' => $trip_plan->id,
                        'r_code' => $t_r->r_code,
                        'finality' => finalityName($trip_plan->finality),
                        'goal' => $trip_plan->goal,
                        'origin_country' => GetCountryName($trip_plan->origin_country),
                        'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                        'origin_city' => $trip_plan->origin_city,
                        'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                        'destiny_country' => GetCountryName($trip_plan->destiny_country),
                        'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                        'destiny_city' => $trip_plan->destiny_city,
                        'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                        'has_hotel' => $trip_plan->has_hotel,
                        'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                        'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                        'dispatch' => $trip_plan->dispatch,
                        'dispatch_reason' => $trip_plan->dispatch_reason,
                        'peoples' => $peoples,
                        'title' => $title_analyze,
                        'description' => $desc_analyze,
                        'template' => 'trip.RequestHasApprov',
                        'subject' => $is_approv == 1 ? 'Sua viagem foi aprovada: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"' : 'Sua viagem foi reprovada: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                    );

                    $user = Users::where('r_code', $t_r->r_code)->first();
                    SendMailJob::dispatch($pattern, $user->email);
                    App::setLocale($user->lang);
                    if ($is_approv == 1) {
                        NotifyUser(__('layout_i.n_trip_001_title'), $user->r_code, 'fa-check', 'text-success', __('layout_i.n_trip_001'), $request->root() .'/trip/review/'. $trip_plan->id);
                    } else {
                        NotifyUser(__('layout_i.n_trip_002_title'), $user->r_code, 'fa-times', 'text-danger', __('layout_i.n_trip_002'), $request->root() .'/trip/review/'. $trip_plan->id);
                    }
                    App::setLocale($request->session()->get('lang'));


                    $analyze->is_approv = $is_approv == 1 ? 1 : 0;
                    $analyze->is_reprov = $is_approv == 0 ? 1 : 0;
                    $analyze->save();

                    $txt = $is_approv == 1 ? "aprovou" : "Reprovou";

                    LogSystem("Colaborador análisou a viagem e a ". $txt .", identificador da viagem: ". $request->session()->get('temp_id'), $request->session()->get('temp_rcode'));

                    return redirect('/trip/analyze/'. $request->session()->get('temp_rcode') .'/'. $request->session()->get('temp_id') .'');

                } else {
                    if ($user->retry > 0) {
                        $user->retry = $user->retry - 1;

                        if ($user->retry == 0) {

                            $user->retry_time = date('Y-m-d H:i:s');
                            $user->is_active = 0;
                            $user->save();

                            $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                            // Write Log
                            LogSystem("Colaborador errou sua senha secreta para aprovar (Viagem) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                            return redirect('/logout');
                        } else {

                            $user->retry_time = date('Y-m-d H:i:s');
                            $user->save();

                            $request->session()->put('error', "You missed your secret password, only ". $user->retry ." attempt(s) left.");
                            // Write Log
                            LogSystem("Colaborador errou sua senha secreta para aprovar (Viagem). Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                            return redirect('/trip/analyze/'. $request->session()->get('temp_rcode') .'/'. $request->session()->get('temp_id') .'');
                        }
                    } else {

                        // Write Log
                        LogSystem("Colaborador está tentando aprovar (Viagem) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                        return redirect('/trip/analyze/'. $request->session()->get('temp_rcode') .'/'. $request->session()->get('temp_id') .'');
                    }
                }

            } else {
                return \Redirect::route('login');
            }

        }

    }

    public function tripAnalyzeInternal_do(Request $request) {
        $reason = $request->input('reason');
        $password = $request->input('password');
        $is_approv = $request->input('is_approv');

        $trip_plan = TripPlan::where('id', $request->session()->get('temp_id'))->first();

        if ($trip_plan) {

            $t_r = Trips::where('id', $trip_plan->trip_id)->first();
            $user = Users::where('r_code', $request->session()->get('r_code'))->first();

            if (Hash::check($password, $user->password)) {

                $trip_plan->has_analyze = 0;
                $trip_plan->is_approv = $is_approv == 1 ? 1 : 0;
                $trip_plan->is_reprov = $is_approv == 0 ? 1 : 0;
                $trip_plan->save();

                $analyze = new TripAnalyze;
                $analyze->description = $reason;
                $analyze->trip_plan_id = $request->session()->get('temp_id');
                $analyze->r_code = $request->session()->get('r_code');

                $peoples = TripPeoples::where('trip_plan_id', $request->session()->get('temp_id'))->count();

                $subject_analyze = "";
                $title_analyze = "";
                $desc_analyze = "";

                if ($is_approv == 1) {

                    $managers = UserOnPermissions::where('perm_id', 1)->where('grade', 99)->get();

                    if ($managers) {

                        LogSystem("Colaborador aprovou solicitação de voo: ". $request->session()->get('temp_id'), $request->session()->get('r_code'));

                        $pattern = array(
                            'name' => getENameFull($t_r->r_code),
                            'id' => $trip_plan->id,
                            'r_code' => $t_r->r_code,
                            'finality' => finalityName($trip_plan->finality),
                            'goal' => $trip_plan->goal,
                            'origin_country' => GetCountryName($trip_plan->origin_country),
                            'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                            'origin_city' => $trip_plan->origin_city,
                            'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                            'destiny_country' => GetCountryName($trip_plan->destiny_country),
                            'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                            'destiny_city' => $trip_plan->destiny_city,
                            'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                            'has_hotel' => $trip_plan->has_hotel,
                            'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                            'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                            'dispatch' => $trip_plan->dispatch,
                            'dispatch_reason' => $trip_plan->dispatch_reason,
                            'peoples' => $peoples,
                            'title' => 'PEDIDO FOI APROVADO',
                            'description' => 'Realize a cotação desse pedido de viagem através do seu painel! Utilize suas credenciais de acesso para poder realizar o procedimento.',
                            'template' => 'trip.RequestHasApprov',
                            'subject' => 'Realize a cotação da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                        );

                        foreach ($managers as $key) {
                            $mng = Users::where('r_code', $key->user_r_code)->first();

                            SendMailJob::dispatch($pattern, $mng->email);
                            App::setLocale($mng->lang);
                            NotifyUser(__('layout_i.n_trip_005_title'), $mng->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_005'), $request->root() .'/trip/review/'. $trip_plan->id);
                            App::setLocale($request->session()->get('lang'));
                        }
                    } else {

                        LogSystem("Colaborador aprovou solicitação de voo: ". $request->session()->get('temp_id'), $request->session()->get('r_code'));

                        $pattern = array(
                            'name' => getENameFull($t_r->r_code),
                            'id' => $trip_plan->id,
                            'r_code' => $t_r->r_code,
                            'finality' => finalityName($trip_plan->finality),
                            'goal' => $trip_plan->goal,
                            'origin_country' => GetCountryName($trip_plan->origin_country),
                            'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                            'origin_city' => $trip_plan->origin_city,
                            'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                            'destiny_country' => GetCountryName($trip_plan->destiny_country),
                            'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                            'destiny_city' => $trip_plan->destiny_city,
                            'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                            'has_hotel' => $trip_plan->has_hotel,
                            'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                            'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                            'dispatch' => $trip_plan->dispatch,
                            'dispatch_reason' => $trip_plan->dispatch_reason,
                            'peoples' => $peoples,
                            'title' => 'PEDIDO FOI APROVADO',
                            'description' => 'Realize a cotação desse pedido de viagem através do seu painel! Utilize suas credenciais de acesso para poder realizar o procedimento.',
                            'template' => 'trip.RequestHasApprov',
                            'subject' => 'Realize a cotação da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                        );

                        // GET EMAIL ADM GERAL
                        SendMailJob::dispatch($pattern, getConfig("email_adm"));

                    }


                    $title_analyze = "PEDIDO FOI APROVADO";
                    $desc_analyze = "Parabéns! Sua viagem foi aprovada, agora aguarde a cotação da viagem, assim que tivermos o bilhete no sistema, iremos te avisar.";


                } else {

                    $title_analyze = "PEDIDO REPROVADO";
                    $desc_analyze = "Infelizmente sua viagem foi reprovada, acesse o sistema para ver o motivo da reprovação.";
                }

                $pattern = array(
                    'name' => getENameFull($t_r->r_code),
                    'id' => $trip_plan->id,
                    'r_code' => $t_r->r_code,
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'peoples' => $peoples,
                    'title' => $title_analyze,
                    'description' => $desc_analyze,
                    'template' => 'trip.RequestHasApprov',
                    'subject' => $is_approv == 1 ? 'Sua viagem foi aprovada: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"' : 'Sua viagem foi reprovada: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                );

                $user = Users::where('r_code', $t_r->r_code)->first();
                SendMailJob::dispatch($pattern, $user->email);
                App::setLocale($user->lang);
                if ($is_approv == 1) {
                    $request->session()->put('success', "Você aprovou a rota, ela será enviada para cotação.");
                    NotifyUser(__('layout_i.n_trip_001_title'), $user->r_code, 'fa-check', 'text-success', __('layout_i.n_trip_001'), $request->root() .'/trip/review/'. $trip_plan->id);
                } else {
                    $request->session()->put('error', "Você reprovou a rota, o colaborador será notificado!");
                    NotifyUser(__('layout_i.n_trip_002_title'), $user->r_code, 'fa-times', 'text-danger', __('layout_i.n_trip_002'), $request->root() .'/trip/review/'. $trip_plan->id);
                }
                App::setLocale($request->session()->get('lang'));

                $analyze->is_approv = $is_approv == 1 ? 1 : 0;
                $analyze->is_reprov = $is_approv == 0 ? 1 : 0;
                $analyze->save();

                $txt = $is_approv == 1 ? "aprovou" : "Reprovou";

                LogSystem("Colaborador análisou a viagem e a ". $txt .", identificador da viagem: ". $request->session()->get('temp_id'), $request->session()->get('r_code'));
                return redirect('/trip/review/'. $request->session()->get('temp_id'));

            } else {
                if ($user->retry > 0) {
                    $user->retry = $user->retry - 1;

                    if ($user->retry == 0) {

                        $user->retry_time = date('Y-m-d H:i:s');
                        $user->is_active = 0;
                        $user->save();

                        $request->session()->put('error', "Você errou muitas vezes sua senha secreta e foi bloqueado, fale com a administração.");
                        // Write Log
                        LogSystem("Colaborador errou sua senha secreta para aprovar (Viagem) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                        return redirect('/logout');
                    } else {

                        $user->retry_time = date('Y-m-d H:i:s');
                        $user->save();

                        $request->session()->put('error', "Você errou sua senha secreta, resta apenas ". $user->retry ." tentativa.");
                        // Write Log
                        LogSystem("Colaborador errou sua senha secreta para aprovar (Viagem). Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                        return redirect('/trip/review/'. $request->session()->get('temp_id'));
                    }
                } else {

                    // Write Log
                    LogSystem("Colaborador está tentando aprovar (Viagem) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                    return redirect('/trip/review/'. $request->session()->get('temp_id'));
                }
            }

        } else {
            return \Redirect::route('login');
        }

    }

    public function tripReview(Request $request, $id) {
        $error = $request->input('error');
        $success = $request->input('success');

        $request->session()->put('temp_id', $id);
		
		$trip = TripPlan::where('id', $id)->first();

		if (!$trip)
			return redirect()->back()->with('error', 'Não foi possível encontrar a viagem, por favor, tente atualizar a página.');			

        $grade = UserOnPermissions::where('perm_id', 1)->where('user_r_code', $request->session()->get('r_code'))->first();
        
        $peoples = TripPeoples::where('trip_plan_id', $id)->get();
        $t_user = Trips::where('id', $trip->trip_id)->first();
        $user = Users::where('r_code', $t_user->r_code)->first();
        $user_grade = UserOnPermissions::where('perm_id', 1)->where('user_r_code', $t_user->r_code)->first();
        $agency = TripAgency::all();
        $budget = TripAgencyBudget::where('trip_plan_id', $id)->where('is_approv', 1)->first();

        $analyze = TripAnalyze::leftJoin('users','trip_analyze.r_code','=','users.r_code')
            ->where('trip_plan_id', $id)
            ->select('trip_analyze.*', 'users.first_name', 'users.last_name')
            ->get();

        $msgs = TripAgencyChat::where('trip_plan_id', $id)->get();
        $agency_name = "";

        $request->session()->put('trip_id', $trip->id);
        $is_approv = 0;
        $has_approv_agency = 0;

        if ($grade) {

            return view('gree_i.trip.trip_review', [
                'agency_name' => $agency_name,
                'msgs' => $msgs,
                'error' => $error,
                'success' => $success,
                'trip' => $trip,
                'user' => $user,
                't_user' => $t_user,
                'user_grade' => $user_grade,
                'peoples' => $peoples,
                'analyze' => $analyze,
                'agency' => $agency,
                'budget' => $budget,
                'planid' => $id,
                'grade' => $grade,
                'is_approv' => $is_approv,
                'has_approv_agency' => $has_approv_agency,
				'id' => $id,
                'url' => '/trip/analyze/update/single'
            ]);
        }
    }

    public function tripSendMsg(Request $request) {
        $type = $request->Input('type');
        $msg = $request->Input('msg');
        $trip_id = $request->Input('trip_id');
        if (!$trip_id) {
            $request->session()->put('error', "Envie novamente, aconteceu um erro inesperado.");
        }
        $gen = $request->Input('gen');

        $message = new TripAgencyChat;
        $message->type = $type;
        $message->message = str_replace('\n','',$msg);
        $message->trip_plan_id = $trip_id;
        $message->save();

        $trip_plan = TripPlan::find($trip_id);
        $t_r = Trips::where('id', $trip_plan->trip_id)->first();
        $user = Users::where('r_code', $t_r->r_code)->first();
        $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();
        $trip_budget = TripAgencyBudget::where('budget_gen', $gen)->where('trip_plan_id', $trip_id)->first();
        $agency = TripAgency::find($trip_budget->agency_id);

        if ($type == 2) {
            $managers = UserOnPermissions::where('perm_id', 1)->where('grade', 99)->get();

            if ($managers) {

                foreach ($managers as $key) {

                    $pattern = array(
                        'name' => getENameFull($t_r->r_code),
                        'id' => $trip_plan->id,
                        'r_code' => $t_r->r_code,
                        'finality' => finalityName($trip_plan->finality),
                        'goal' => $trip_plan->goal,
                        'origin_country' => GetCountryName($trip_plan->origin_country),
                        'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                        'origin_city' => $trip_plan->origin_city,
                        'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                        'destiny_country' => GetCountryName($trip_plan->destiny_country),
                        'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                        'destiny_city' => $trip_plan->destiny_city,
                        'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                        'has_hotel' => $trip_plan->has_hotel,
                        'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                        'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                        'dispatch' => $trip_plan->dispatch,
                        'dispatch_reason' => $trip_plan->dispatch_reason,
                        'peoples' => $peoples,
                        'title' => 'RESPOSTA DA AGÊNCIA: '. $agency->name,
                        'description' => 'Agência enviou uma nova mensagem, veja nos detalhes da viagem. Se necessário atualize a página.',
                        'template' => 'trip.RequestHasApprov',
                        'subject' => 'Resposta da agência: '. $agency->name,
                    );

                    $mng = Users::where('r_code', $key->user_r_code)->first();
                    SendMailJob::dispatch($pattern, $mng->email);
                    App::setLocale($mng->lang);
                    NotifyUser(__('layout_i.n_trip_007_title'), $mng->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_007', ['id' => '#'. $trip_plan->id, 'Agency' => $agency->name]), $request->root() .'/trip/review/'. $trip_plan->id);
                    App::setLocale($request->session()->get('lang'));
                }
            }
        } else {

            $pattern = array(
                'name' => $user->first_name ." ". $user->last_name,
                'id' => $trip_plan->id,
                'gen' => $trip_budget->budget_gen,
                'r_code' => $t_r->r_code,
                'finality' => finalityName($trip_plan->finality),
                'goal' => $trip_plan->goal,
                'origin_country' => GetCountryName($trip_plan->origin_country),
                'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                'origin_city' => $trip_plan->origin_city,
                'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                'destiny_country' => GetCountryName($trip_plan->destiny_country),
                'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                'destiny_city' => $trip_plan->destiny_city,
                'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                'has_hotel' => $trip_plan->has_hotel,
                'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                'dispatch' => $trip_plan->dispatch,
                'dispatch_reason' => $trip_plan->dispatch_reason,
                'peoples' => $peoples,
                'title' => 'RESPOSTA DA GREE',
                'description' => 'Acesse o link abaixo e verifique atualização da resposta da GREE. Atualize a página se necessário.',
                'template' => 'trip.RequestAgency',
                'subject' => 'Resposta da GREE',
            );

            SendMailJob::dispatch($pattern, $agency->email);
        }

        return redirect()->back();

    }


    public function tripPlanBackStatus(Request $request, $id) {
        $trip_plan = TripPlan::find($id);
        if ($trip_plan) {

            if ($trip_plan->is_credit == 1) {
                TripPlanCredit::where('trip_plan_id', $id)->delete();
            }

            $trip_plan->is_approv = 1;
            $trip_plan->is_completed = 0;
            $trip_plan->is_credit = 0;
            $trip_plan->save();

            $request->session()->put('success', "O pedido de viagem foi reaberto com sucesso!");
            return Redirect('/trip/review/'. $id);

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function tripPlanComplete(Request $request, $id) {
        $trip_plan = TripPlan::find($id);
        $total = $request->Input('total');
        $iscredit = $request->Input('iscredit');

        if ($trip_plan) {
            $trip_plan->is_approv = 1;
            $trip_plan->is_completed = 1;
            $trip_plan->is_credit = $iscredit == 1 ? 1 : 0;
            $trip_plan->save();

            $budget = TripAgencyBudget::where('trip_plan_id', $trip_plan->id)->where('is_approv', 1)->first();
            $budget->total = $total;
            $budget->save();

            if ($iscredit == 1) {
                $credit = new TripPlanCredit;
                $credit->trip_plan_id = $trip_plan->id;
                $credit->agency_id = $budget->agency_id;
                $credit->total = $total;
                $credit->save();
            } else {

                $t_r = Trips::where('id', $trip_plan->trip_id)->first();

                $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->get();

                $pattern = array(
                    'attach' => $budget->ticket_url,
                    'attach_hotel' => $budget->ticket_hotel,
                    'name' => getENameFull($t_r->r_code),
                    'id' => $trip_plan->id,
                    'r_code' => $t_r->r_code,
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'peoples' => count($peoples),
                    'peoples_ticket' => $peoples,
                    'title' => 'BILHETE DE VIAGEM',
                    'description' => 'Seu bilhete de viagem está no link acima, use-o para ir, caso precise alterar o bilhete, entre em contato com administração e aguarde o próximo email com o novo bilhete!',
                    'template' => 'trip.RequestApprovAttach',
                    'subject' => 'Bilhete da viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                );

                $user = Users::where('r_code', $t_r->r_code)->first();
                SendMailJob::dispatch($pattern, $user->email);
                App::setLocale($user->lang);
                NotifyUser(__('layout_i.n_trip_004_title'), $user->r_code, 'fa-cloud-download-alt', 'text-success', __('layout_i.n_trip_004'), $request->root() .'/trip/review/'. $trip_plan->id);
                App::setLocale($request->session()->get('lang'));
            }

            return Redirect('/trip/review/'. $id);
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function tripPlanReopen(Request $request, $id) {
        $trip_plan = TripPlan::find($id);

        if ($trip_plan) {

            $budget = TripAgencyBudget::where('trip_plan_id', $trip_plan->id)->where('is_approv', 1)->first();
            $budget->is_approv = 0;
            $budget->save();

            $all_agency_budget = TripAgencyBudget::where('trip_plan_id', $trip_plan->id)->get();
            $t_r = Trips::where('id', $trip_plan->trip_id)->first();
            $user = Users::where('r_code', $t_r->r_code)->first();
            $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();

            foreach ($all_agency_budget as $key) {

                $pattern = array(
                    'name' => $user->first_name ." ". $user->last_name,
                    'id' => $trip_plan->id,
                    'gen' => $key->budget_gen,
                    'r_code' => $t_r->r_code,
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'peoples' => $peoples,
                    'title' => 'PEDIDO DE COTAÇAO REABERTO',
                    'description' => 'O pedido de cotação foi reaberto, aproveite para enviar novas informações ou aguarde aprovação da mesma. Siga o procedimento abaixo para conclusão do mesmo.',
                    'template' => 'trip.RequestAgency',
                    'subject' => 'Reaberto cotação da viagem: #'. $trip_plan->id ." - GREE",
                );

                $agency = TripAgency::where('id', $key->agency_id)->first();
                SendMailJob::dispatch($pattern, $agency->email);
            }

            return Redirect('/trip/review/'. $id);
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function tripBudget(Request $request, $gen, $id) {

        $has_request = 0;
        $has_approv = 0;
        $other_agency_approv = 0;
        $trip_budget = TripAgencyBudget::where('budget_gen', $gen)->where('trip_plan_id', $id)->first();
        $request->session()->put('trip_id', $id);

        $grade = UserOnPermissions::where('perm_id', 1)->where('user_r_code', $request->session()->get('r_code'))->where('grade', 99)->first();
        if ($trip_budget) {
            $has_request = 1;
        }
        if (TripAgencyBudget::where('budget_gen', $gen)->where('trip_plan_id', $id)->where('is_approv', 1)->count() > 0) {
            $has_approv = 1;
        }
        if (TripAgencyBudget::where('budget_gen', '!=', $gen)->where('trip_plan_id', $id)->where('is_approv', 1)->count() > 0) {
            $other_agency_approv = 1;
        }

        $trip = TripPlan::where('id', $id)->first();
        $peoples = TripPeoples::where('trip_plan_id', $id)->get();
        $msgs = TripAgencyChat::where('trip_plan_id', $id)->get();

        return view('gree_i.trip.trip_agency', [
            'trip' => $trip,
            'msgs' => $msgs,
            'trip_budget' => $trip_budget,
            'peoples' => $peoples,
            'planid' => $id,
            'gen' => $gen,
            'grade' => $grade,
            'other_agency_approv' => $other_agency_approv,
            'has_request' => $has_request,
            'has_approv' => $has_approv,
        ]);

    }

    public function tripApprovBudget(Request $request, $id, $plan) {
        $message = $request->input('chat');
        $budget = TripAgencyBudget::where('agency_id', $id)->where('trip_plan_id', $plan)->first();
        if ($budget) {
            $budget->is_approv = 1;
            $budget->save();

            $chat = new TripAgencyChat;
            $chat->message = $message;
            $chat->type = 1;
            $chat->trip_plan_id = $plan;
            $chat->save();

            $trip_plan = TripPlan::find($budget->trip_plan_id);
            $t_r = Trips::where('id', $trip_plan->trip_id)->first();
            $user = Users::where('r_code', $t_r->r_code)->first();
            $peoples = TripPeoples::where('trip_plan_id', $budget->trip_plan_id)->count();

            $pattern = array(
                'name' => $user->first_name ." ". $user->last_name,
                'id' => $trip_plan->id,
                'gen' => $budget->budget_gen,
                'r_code' => $t_r->r_code,
                'finality' => finalityName($trip_plan->finality),
                'goal' => $trip_plan->goal,
                'origin_country' => GetCountryName($trip_plan->origin_country),
                'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                'origin_city' => $trip_plan->origin_city,
                'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                'destiny_country' => GetCountryName($trip_plan->destiny_country),
                'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                'destiny_city' => $trip_plan->destiny_city,
                'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                'has_hotel' => $trip_plan->has_hotel,
                'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                'dispatch' => $trip_plan->dispatch,
                'dispatch_reason' => $trip_plan->dispatch_reason,
                'peoples' => $peoples,
                'title' => 'COTAÇÃO APROVADA',
                'description' => nl2br("Sua cotação foi aprovada! Agora será necessário você entrar novamente para enviar o bilhete da passagem para conclusão da viagem. Abaixo mais detalhes: \n ". $message),
                'template' => 'trip.RequestAgency',
                'subject' => 'Cotação aprovada! Viagem: #'. $trip_plan->id ." - GREE",
            );

            $request->session()->put('success', "Você aprovou a cotação da agência!");
            $user = TripAgency::where('id', $id)->first();
            SendMailJob::dispatch($pattern, $user->email);
        }

        return Redirect('/trip/review/'. $plan);
    }

    public function tripSendBudget(Request $request) {
        $agency = $request->input('agency');
        $id = $request->input('id');

        $trip_plan = TripPlan::find($id);
        $t_r = Trips::where('id', $trip_plan->trip_id)->first();
        $user = Users::where('r_code', $t_r->r_code)->first();
        $peoples = TripPeoples::where('trip_plan_id', $id)->count();

        if (isset($agency)) {
            foreach ($agency as $key) {

                $gen = rand(date('YmdHis'), 10);

                $budget = new TripAgencyBudget;
                $budget->agency_id = $agency[$key];
                $budget->trip_plan_id = $id;
                $budget->budget_gen = $gen;
                $budget->ticket_url = "";
                $budget->is_approv = 0;
                $budget->total = 0.00;
                $budget->save();

                LogSystem("Colaborador enviou solicitação de cotação para agência: ". $agency[$key], $request->session()->get('r_code'));

                $pattern = array(
                    'name' => $user->first_name ." ". $user->last_name,
                    'id' => $trip_plan->id,
                    'gen' => $gen,
                    'r_code' => $t_r->r_code,
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'peoples' => $peoples,
                    'title' => 'PEDIDO DE COTAÇAO',
                    'description' => 'Sua agência foi escolhida para realizar a cotação da viagem para um de nossos colaboradores, siga o procedimento abaixo para conclusão do mesmo.',
                    'template' => 'trip.RequestAgency',
                    'subject' => 'Cotação da viagem: #'. $trip_plan->id ." - GREE",
                );

                $request->session()->put('success', "Você enviou a rota para cotação da(s) agência(s) selecionada(s).");
                $user = TripAgency::where('id', $agency[$key])->first();
                SendMailJob::dispatch($pattern, $user->email);
            }
        }

        return Redirect('/trip/review/'. $id);


    }

    public function tripABPeoples(Request $request, $gen, $id) {

        $people = $request->people;
        $identity = $request->identity;

        $p_plan = TripPeoples::where('trip_plan_id', $id)->where('identity', $identity)->first();

        if ($p_plan) {

            if ($request->hasFile('people')) {
                $extension = $request->people->extension();
                if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                    $validator = Validator::make(
                        [
                            'people' => $people,
                        ],
                        [
                            'people' => 'required|max:1000',
                        ]
                    );

                    if ($validator->fails()) {

                        $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                        return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                    } else {

                        $img_ticket = '4-'. date('YmdHis') .'-'. $id .'.'. $extension;

                        $request->people->storeAs('/', $img_ticket, 's3');
                        $url = Storage::disk('s3')->url($img_ticket);
                        $p_plan->ticket_url = $url;
                        $p_plan->save();
                    }

                } else {

                    $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                    return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                }

                $request->session()->put('success', "Bilhete de viagem do passageiro atualizado.");
            }

            return Redirect('/trip/agency/budget/'. $gen .'/'. $id);

        } else {

            $request->session()->put('error', "Usuário não encontrado!");
            return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
        }

    }

    public function tripAgencyBudget_do(Request $request, $gen, $id) {

        $ticket = $request->ticket;
        $ticket_hotel = $request->ticket_hotel;
        $budget = $request->budget;
        $hotel = $request->hotel;
        $description = $request->Input('description');
        $total = $request->input('total');
        $people = $request->people;

        $b_file = "";
        $h_file = "";

        $trip_budget = TripAgencyBudget::where('budget_gen', $gen)->where('trip_plan_id', $id)->first();

        if ($trip_budget) {
            if (TripAgencyBudget::where('budget_gen', $gen)->where('trip_plan_id', $id)->where('is_approv', 1)->count() > 0) {

                if ($request->hasFile('ticket')) {
                    $extension = $request->ticket->extension();
                    if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                        $validator = Validator::make(
                            [
                                'ticket' => $ticket,
                            ],
                            [
                                'ticket' => 'required|max:1000',
                            ]
                        );

                        if ($validator->fails()) {

                            $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                            return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                        } else {

                            $img_ticket = '2-'. date('YmdHis') .'-'. $id .'.'. $extension;

                            $request->ticket->storeAs('/', $img_ticket, 's3');
                            $url = Storage::disk('s3')->url($img_ticket);
                            $trip_budget->ticket_url = $url;
                        }

                    } else {

                        $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                        return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                    }
                }

                if ($request->hasFile('ticket_hotel')) {
                    $extension = $request->ticket_hotel->extension();
                    if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                        $validator = Validator::make(
                            [
                                'ticket_hotel' => $ticket_hotel,
                            ],
                            [
                                'ticket_hotel' => 'required|max:1000',
                            ]
                        );

                        if ($validator->fails()) {

                            $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                            return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                        } else {

                            $img_ticket = '4-'. date('YmdHis') .'-'. $id .'.'. $extension;

                            $request->ticket_hotel->storeAs('/', $img_ticket, 's3');
                            $url = Storage::disk('s3')->url($img_ticket);
                            $trip_budget->ticket_hotel = $url;
                        }

                    } else {

                        $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                        return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                    }
                }
            }

            if ($request->hasFile('budget')) {
                $extension = $request->budget->extension();
                if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                    $validator = Validator::make(
                        [
                            'budget' => $budget,
                        ],
                        [
                            'budget' => 'required|max:1000',
                        ]
                    );

                    if ($validator->fails()) {

                        $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                        return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                    } else {

                        $img_budget = '1-'. date('YmdHis') .'-'. $id .'.'. $extension;
                        $request->budget->storeAs('/', $img_budget, 's3');
                        $url = Storage::disk('s3')->url($img_budget);

                        if ($trip_budget->budget_url != null) {
                            $b_file = $trip_budget->budget_url;
                        }

                        $trip_budget->budget_url = $url;

                    }

                } else {

                    $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                    return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                }
            }

            if ($request->hasFile('hotel')) {
                $extension = $request->hotel->extension();
                if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                    $validator = Validator::make(
                        [
                            'hotel' => $hotel,
                        ],
                        [
                            'hotel' => 'required|max:1000',
                        ]
                    );

                    if ($validator->fails()) {

                        $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                        return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                    } else {

                        $img_hotel = '3-'. date('YmdHis') .'-'. $id .'.'. $extension;
                        $request->hotel->storeAs('/', $img_hotel, 's3');
                        $url = Storage::disk('s3')->url($img_hotel);

                        if ($trip_budget->budget_hotel != null) {
                            $h_file = $trip_budget->budget_hotel;
                        }

                        $trip_budget->budget_hotel = $url;

                    }

                } else {

                    $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                    return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
                }
            }

            if (!empty($b_file) or !empty($h_file)) {
                $oldfile = new TripBudgetFiles;
                $oldfile->trip_plan_id = $id;
                $oldfile->agency_id = $trip_budget->agency_id;
                $oldfile->old_file = $b_file;
                $oldfile->old_file_hotel = $h_file;
                $oldfile->save();
            }


            if (!empty($description)) {
                $trip_budget->description = str_replace('\n','',$description);
            }
            $trip_budget->save();

            // EMAIL
            $managers = UserOnPermissions::where('perm_id', 1)->where('grade', 99)->get();

            if ($managers) {

                $trip_plan = TripPlan::where('id', $id)->first();
                $t_r = Trips::where('id', $trip_plan->trip_id)->first();

                $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();

                $agency = TripAgency::find($trip_budget->agency_id);
                $pattern = array(
                    'name' => getENameFull($t_r->r_code),
                    'id' => $trip_plan->id,
                    'r_code' => $t_r->r_code,
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'peoples' => $peoples,
                    'title' => 'AGÊNCIA ATUALIZOU COTAÇÃO',
                    'description' => 'Agência: '. $agency->name .' fez uma nova cotação, acess o link e tenha mais detalhes sobre a cotação.',
                    'template' => 'trip.RequestHasApprov',
                    'subject' => 'Cotação atualizada, agência: '. $agency->name,
                );

                foreach ($managers as $key) {
                    $mng = Users::where('r_code', $key->user_r_code)->first();
                    SendMailJob::dispatch($pattern, $mng->email);
                    App::setLocale($mng->lang);
                    NotifyUser(__('layout_i.n_trip_003_title'), $mng->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_003', ['id' => '#'. $trip_plan->id, 'Agency' => $agency->name]), $request->root() .'/trip/review/'. $trip_plan->id);
                    App::setLocale($request->session()->get('lang'));
                }
            } else {

                $trip_plan = TripPlan::where('id', $id)->first();
                $t_r = Trips::where('id', $trip_plan->trip_id)->first();

                $peoples = TripPeoples::where('trip_plan_id', $trip_plan->id)->count();

                $agency = TripAgency::find($trip_budget->agency_id);
                $pattern = array(
                    'name' => getENameFull($t_r->r_code),
                    'id' => $trip_plan->id,
                    'r_code' => $t_r->r_code,
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'peoples' => $peoples,
                    'title' => 'AGÊNCIA ATUALIZOU COTAÇÃO',
                    'description' => 'Agência: '. $agency->name .' fez uma nova cotação, acess o link e tenha mais detalhes sobre a cotação.',
                    'template' => 'trip.RequestHasApprov',
                    'subject' => 'Cotação atualizada, agência: '. $agency->name,
                );

                // GET EMAIL ADM GERAL
                SendMailJob::dispatch($pattern, getConfig("email_adm"));
            }

            $request->session()->put('success', "Orçamento foi atualizado com sucesso! Aguarde uma resposta da Gree.");
            return Redirect('/trip/agency/budget/'. $gen .'/'. $id);

        } else {

            return Redirect('/trip/agency/budget/'. $gen .'/'. $id);
        }


    }

    public function tripAgencyList(Request $request) {

        $agency = TripAgency::paginate(10);

        return view('gree_i.trip.trip_agency_list', [
            'agency' => $agency,
        ]);

    }

    public function tripCredits(Request $request) {

        $credit = DB::table('trip_plan_credit')
            ->leftJoin('trip_agency','trip_plan_credit.agency_id','=','trip_agency.id')
            ->select('trip_plan_credit.*', 'trip_agency.name')
            ->paginate(10);

        return view('gree_i.trip.trip_credit_list', [
            'credit' => $credit,
        ]);

    }

    public function tripCredits_do(Request $request, $id) {

        $credit = TripPlanCredit::find($id);

        if ($credit) {
            $credit->has_used = 1;
            $credit->save();

        }

        $request->session()->put('success', "Credito foi usado com sucesso!");
        return Redirect('/trip/credits');

    }

    public function tripAgencyDelete(Request $request, $id) {

        $agency = TripAgency::find($id);


        LogSystem("Colaborador deletou agência: ". $agency->name, $request->session()->get('r_code'));
        if ($agency) {

            TripAgency::where('id', $id)->delete();

        }

        $request->session()->put('success', "Agência deletada com sucesso!");
        return Redirect('/trip/agency');

    }

    public function tripAgencyUpdate(Request $request) {
        $id = $request->Input('id');
        $name = $request->Input('name');
        $email = $request->Input('email');

        $agency = TripAgency::find($id);

        if ($agency) {

            $agency->name = $name;
            $agency->email = $email;
            $agency->save();

            LogSystem("Colaborador atualizou agência com identificação: ". $id, $request->session()->get('r_code'));
        } else {

            $agency = new TripAgency;
            $agency->name = $name;
            $agency->email = $email;
            $agency->save();

            LogSystem("Colaborador criou uma nova agência com identificação: ". $id, $request->session()->get('r_code'));
        }

        $request->session()->put('success', "Lista de agências atualizadas com sucesso!");
        return Redirect('/trip/agency');
    }

    public function userView(Request $request, $rcode) {

        $user = Users::where('r_code', $rcode)->first();

        if ($user) {

            $sector = Sector::find($user->sector_id);

            return view('gree_i.user.user_view', [
                'r_code' => $user->r_code,
                'name' => getENameF($user->r_code),
                'office' => $user->office,
                'email' => $user->email,
                'gree_id' => $user->gree_id,
                'sector' => $sector,
                'name_full' => $user->first_name ." ". $user->last_name,
                'phone' => $user->phone,
                'picture' => $user->picture,
                'birthday' => $user->birthday,
            ]);

        } else {

            return \Redirect::route('news');
        }
    }

    public function userEdit(Request $request, $rcode) {

        $sector = Sector::all();
        $usersall = Users::where('r_code', '!=', $request->session()->get('r_code'))->get();
        if ($rcode > 0) {

            $user = Users::where('r_code', $rcode)->first();
            if ($user) {

                $rcode = $user->r_code;
                $picture = $user->picture;
                $first_name = $user->first_name;
                $last_name = $user->last_name;
                $email = $user->email;
                $phone = $user->phone;
                $birthday = $user->birthday;
                $sectorid = $user->sector_id;
                $greeid = $user->gree_id;
                $office = $user->office;
                $is_active = $user->is_active;
                $filter_line = $user->filter_line;
                $otpauth = $user->otpauth;
                $is_holiday = $user->is_holiday;
                $holiday_date_end = $user->holiday_date_end;
                if ($is_holiday == 1)
                    $user_holiday =  \App\Model\UserHoliday::where('user_r_code', $request->session()->get('r_code'))->get()->pluck('receiver_json_view')->toArray();
                else
                    $user_holiday = [];

                $perm = Permissions::all();

                if ($request->session()->get('r_code') != $rcode) {

                    LogSystem("Colaborador entrou na edição do usuário da matricula: ". $rcode, $request->session()->get('r_code'));

                    $verify_perm =  UserOnPermissions::where('perm_id', 2)->where('user_r_code', $request->session()->get('r_code'))->where('grade', 99)->first();
                    if (!$verify_perm) {
                        LogSystem("Colaborador não tem permissão para editar: ". $rcode, $request->session()->get('r_code'));
                        App::setLocale($request->session()->get('lang'));
                        $request->session()->put('error', __('layout_i.not_permissions'));
                        return \Redirect::route('news');
                    }
                } else {
                    LogSystem("Colaborador está editando seu próprio perfil", $request->session()->get('r_code'));
                }

                $immediates = DB::table('user_immediate')
                    ->leftJoin('users','user_immediate.immediate_r_code','=','users.r_code')
                    ->select('users.r_code', 'users.picture')
                    ->where('user_immediate.user_r_code', $rcode)
                    ->get();

                $request->session()->put('temp_rcode', $rcode);
                return view('gree_i.user.user_edit', [
                    'usersall' => $usersall,
                    'rcode' => $rcode,
                    'perm' => $perm,
                    'greeid' => $greeid,
                    'sector' => $sector,
                    'phone' => $phone,
                    'birthday' => $birthday,
                    'sectorid' => $sectorid,
                    'office' => $office,
                    'picture' => $picture,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'is_active' => $is_active,
                    'immediates' => $immediates,
                    'filter_line' => $filter_line,
                    'otpauth' => $otpauth,
                    'is_holiday' => $is_holiday,
                    'holiday_date_end' => $holiday_date_end,
                    'user_holiday' => $user_holiday,
                ]);

            } else {
                return Redirect('/user/list');
            }

        } else {

            $verify_perm =  UserOnPermissions::where('perm_id', 2)->where('user_r_code', $request->session()->get('r_code'))->where('grade', 99)->first();
            $perm = Permissions::all();

            if ($verify_perm) {

                LogSystem("Colaborador está criando um novo usuário.", $request->session()->get('r_code'));

                $rcode = 0;
                $picture = "";
                $first_name = "";
                $last_name = "";
                $email = "";
                $phone = "";
                $birthday = date('Y-m-d');
                $sectorid = "";
                $greeid = "";
                $office = "";
                $is_active = "";

                $immediates = "";
                $filter_line = null;

                $request->session()->put('temp_rcode', $rcode);
                return view('gree_i.user.user_edit', [
                    'usersall' => $usersall,
                    'perm' => $perm,
                    'greeid' => $greeid,
                    'sector' => $sector,
                    'phone' => $phone,
                    'birthday' => $birthday,
                    'sectorid' => $sectorid,
                    'office' => $office,
                    'rcode' => $rcode,
                    'picture' => $picture,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'immediates' => $immediates,
                    'is_active' => $is_active,
                    'filter_line' => $filter_line,
                    'user_holiday' => [],
                    'is_holiday' => 0,
                ]);

            } else {
                LogSystem("Colaborador não tem permissão para criar um novo usuário: ", $request->session()->get('r_code'));
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return \Redirect::route('news');
            }

        }

    }

    public function userEdit_do(Request $request) {

        $picture = $request->file('picture');
        $picture = $request->picture;
        $isnew = $request->input('is_new');
        $rcode = $request->input('registration');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('reg_email');
        $password = $request->input('password');
        $active = $request->input('user_active');
        $phone = $request->input('phone');
        $birthday = $request->input('birthday');
        $office = $request->input('office');
        $gree = $request->input('gree');
        $sector = $request->input('sector');
        $filter_line = $request->input('filter_line');
        $has_img = 0;

        $temp_code = $request->session()->get('temp_rcode');

        if ($request->session()->get('r_code') != $temp_code) {

            $verify_perm =  UserOnPermissions::where('perm_id', 2)->where('user_r_code', $request->session()->get('r_code'))->where('grade', 99)->first();
            if (!$verify_perm) {
                LogSystem("Colaborador não tem permissão para atualizar: ". $temp_code, $request->session()->get('r_code'));
                return Redirect('/user/edit/'. $temp_code);
            }
        } else {
            LogSystem("Colaborador está atualizando seu próprio perfil.", $request->session()->get('r_code'));
        }

        // Perm
        $perm_active = $request->input('perm_active');
        $perm_manager = $request->input('perm_manager');
        $perm_approv = $request->input('perm_approv');
        $perm_grade = $request->input('perm_grade');
        $perm_id = $request->input('perm_id');

        if ($isnew == 1) {
            $user = new Users;
        } else {
            $user = Users::where('r_code', $temp_code)->first();
        }

        if (Users::where('r_code', $rcode)->count() > 0 and $isnew == 1) {

            $request->session()->put('error', "Esse número: (". $rcode .") de matricula já está sendo usado.");
            $code = $isnew == 1 ? 0 : $temp_code;
            return Redirect('/user/edit/'. $code);
        } else if ($user->r_code != $rcode and $isnew == 0) {
            if (Users::where('r_code', $rcode)->count() > 0) {

                $request->session()->put('error', "Esse número: (". $rcode .") de matricula já está sendo usado.");
                $code = $isnew == 1 ? 0 : $temp_code;
                return Redirect('/user/edit/'. $code);
            }
        } else if (Users::where('email', $email)->count() > 0 and $isnew == 1) {

            $request->session()->put('error', "Esse email: (". $email .") já está sendo usado.");
            $code = $isnew == 1 ? 0 : $temp_code;
            return Redirect('/user/edit/'. $code);
        } else if ($user->email != $email and $isnew == 0) {
            if (Users::where('email', $email)->count() > 0) {

                $request->session()->put('error', "Esse email: (". $email .") já está sendo usado.");
                $code = $isnew == 1 ? 0 : $temp_code;
                return Redirect('/user/edit/'. $code);
            }
        }

        if ($request->hasFile('picture')) {
            $response = $this->uploadS3(1, $request->picture, $request);
            if ($response['success']) {
                if ($temp_code == $request->session()->get('r_code')) {

                    $request->session()->put('picture', $response['url']);
                }
                $user->picture = $response['url'];
            } else {
                return redirect()->back()->with('error', 'Não foi possível fazer upload do arquivo!');
            }
        }

        $user->r_code = $rcode;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->is_active = $active != '' ? $active : $user->is_active;
		$user->retry = 3;
        $user->email = $email;
        $user->phone = $phone;
        $user->birthday = $birthday;
        $user->office = $office;
        $user->gree_id = $gree;
        $user->sector_id = $sector;

        if ($filter_line)
            $user->filter_line = $filter_line;

        if ($password) {
			$gPassword = Hash::make($password);
			$has_account_commercial = \App\Model\Commercial\Salesman::where('r_code', $user->r_code)
				->where('is_direction', '>=', 2)
				->first();
			
			if ($has_account_commercial) {
				$has_account_commercial->password = $gPassword;
				$has_account_commercial->save();
			}
				
			
            $user->password = $gPassword;
        }

        if ($request->session()->get('r_code') != $temp_code) {

            $count = Permissions::count();

            for ($i=0; $i < $count; $i++) {
                if (isset($perm_active[$i])) {
                    if ($perm_active[$i] == 1) {
                        $perm = UserOnPermissions::where('perm_id', $perm_id[$i])->where('user_r_code', $rcode)->first();
                        if (!$perm) {
                            $perm = new UserOnPermissions;
                        }
                        $perm->perm_id = $perm_id[$i];
                        $perm->user_r_code = $rcode;
                        if (isset($perm_manager[$i])) {
                            $perm->grade = $perm_manager[$i] == 1 ? 99 : $perm_grade[$i];
                        } else {
                            $perm->grade = $perm_grade[$i];

                        }
                        if (isset($perm_approv[$i])) {
                            $perm->can_approv = $perm_approv[$i] == 1 ? 1 : 0;
                        } else {
                            $perm->can_approv = 0;
                        }
                        $perm->save();
                    }

                } else {
                    UserOnPermissions::where('perm_id', $perm_id[$i])->where('user_r_code', $rcode)->delete();
                }
            }
        }

        $user->save();

        // JSON IMMEDIATE
        $raw_payload = $request->input('data_input');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {

            LogSystem("Colaborador atualizou os chefes imediatos do usuário matriculado: ". $user->r_code, $request->session()->get('r_code'));
            UserImmediate::where('user_r_code', $rcode)->delete();
            foreach ($payload as $key) {
                $immediate = new UserImmediate;
                $immediate->user_r_code = $user->r_code;
                $immediate->immediate_r_code = $key['r_code'];
                $immediate->save();

            }

        }


        $request->session()->put('success', "Cadastro de usuário atualizado com sucesso!");
        LogSystem("Colaborador atualizou o perfil com sucesso!", $request->session()->get('r_code'));
        $code = $isnew == 1 ? 0 : $rcode;

        if ($isnew == 1) {
            ListTransmission::where('email', $email)->delete();

            $pattern = array(
                'title' => 'BEM-VINDO AO SISTEMA GREE',
                'description' => '',
                'template' => 'user.NewUser',
                'subject' => 'Bem-vindo ao sistema Gree',
            );

            SendMailJob::dispatch($pattern, $email);
        }

        return Redirect('/user/edit/'. $code);

    }
	
	public function userResetAuth(Request $request, $rcode) {

        $user = Users::where('r_code', $rcode)->first();

        if ($user) {

            $user->otpauth = null;
            $user->save();

            $request->session()->put('success', 'Autenticação de 2 fatores do usuário foi resetada com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Usuário não encontrado no sistema');
            return redirect()->back();
        }
    }

    public function userHoliday(Request $request) {

        \App\Model\UserHoliday::where('user_r_code', $request->session()->get('r_code'))->delete();

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if ($user) {

            if ($request->holiday_status == 2) {

                $user->is_holiday = 0;
                $user->holiday_date_end = null;
                $user->save();

                $request->session()->put('is_holiday', 0);
                return redirect()->back()->with('error', 'O modo férias foi desativado com sucesso!');

            } else {

                $all_perms = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->get();

                $usrdata = Users::with('subordinates')->where('r_code', $request->session()->get('r_code'))->first();

                $user->is_holiday = 1;
                $user->holiday_date_end = $request->holiday_date;
                $user->save();

                $perms = json_decode($request->hd_data, true);

                foreach($perms as $key) {

                    $arr = [];
                    foreach ($key['perms'] as $value) {

                        $gperm = $all_perms->where('perm_id', $value['id'])->first();
                        if ($gperm) {
                            $arr[] = [
                                'perm_id' => $gperm->perm_id,
                                'grade' => $gperm->grade,
                                'can_approv' => $gperm->can_approv,
                            ];
                        }
                    }

                    $add = new \App\Model\UserHoliday;
                    $add->user_r_code = $request->session()->get('r_code');
                    $add->receiver_r_code = $key['r_code'];
                    $add->receiver_immediate = implode(',', $usrdata->subordinates->pluck('r_code')->toArray());
                    $add->receiver_perm = json_encode($arr);
                    $add->receiver_json_view = json_encode($key);
                    $add->date_end = $request->holiday_date;
                    $add->save();
                }

                if ($request->holiday_notify == 1) {

                    $responsible = \App\Model\UserHoliday::where('user_r_code', $request->session()->get('r_code'))->get();

                    $pattern = array(
                        'title' => 'AVISO DE FÉRIAS',
                        'description' => $request->holiday_msg,
                        'user' => $user,
                        'responsible' => $responsible,
                        'template' => 'user.Holiday',
                        'subject' => 'Aviso de férias de '. $user->first_name .' '. $user->last_name,
                    );

                    DB::table('users')->where('is_active', 1)->orderBy('id', 'DESC')->chunk(100, function($users) use ($pattern, $user, $request)
                    {
                        foreach ($users as $usr)
                        {
                            NotifyUser(
                                'Aviso de férias de '. $user->first_name .' '. $user->last_name,
                                $usr->r_code,
                                'fa-exclamation',
                                'text-info',
                                strWordCut($request->holiday_msg, 80),
                                '#'
                            );
                            delayQueueEmail($pattern, $usr->email);

                        }
                    });

                    DB::table('list_transmission')->orderBy('id', 'DESC')->chunk(100, function($transmissions) use ($pattern, $user)
                    {
                        foreach ($transmissions as $transmission)
                        {
                            delayQueueEmail($pattern, $transmission->email);

                        }
                    });
                }

                $request->session()->put('is_holiday', 1);
                return redirect()->back()->with('success', 'Modo férias foi atualizado com sucesso!');
            }
        } else {

            return redirect()->back()->with('error', 'Não foi possível encontrar o usuário.');
        }
    }

    public function userUnlock(Request $request, $id) {

        LogSystem("Colaborador desbloqueou usuário da matricula: ". $id, $request->session()->get('r_code'));
        $user = Users::where('r_code', $id)->first();
        $user->retry = 3;
        $user->is_active = 1;
        $user->save();

        $request->session()->put('success', "Usuário desbloqueado com sucesso!");
        return Redirect('/user/list/');
    }

    public function userList(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('userf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('userf_r_code');
        }

        $users = Users::leftJoin('sector','users.sector_id','=','sector.id')
            ->select('users.*', 'sector.name')
            ->where('users.r_code', '!=', $request->session()->get('r_code'))
            ->orderBy('users.id', 'DESC')
            ->groupBy('users.id');

        if (!empty($request->session()->get('userf_r_code'))) {
            $users->where('users.r_code', $request->session()->get('userf_r_code'));
        }

        $userall = Users::all();

        return view('gree_i.user.user_list', [
            'userall' => $userall,
            'users' => $users->paginate(10),
        ]);
    }

    public function userLog(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('userf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('userf_r_code');
        }

        $logs = \App\Model\LogAccess::with('users')
            ->orderBy('id', 'DESC');

        if (!empty($request->session()->get('userf_r_code'))) {
            $logs->where('users.r_code', $request->session()->get('userf_r_code'));
        }

        $userall = Users::all();

        return view('gree_i.user.user_log', [
            'userall' => $userall,
            'logs' => $logs->paginate(10),
        ]);
    }

    public function userNotify(Request $request) {

        $notify = Notifications::where('r_code', $request->session()->get('r_code'))
            ->orderBy('id', 'DESC');

        $array_input = collect([
            'text',
            'status',
        ]);

        $array_input = putSession($request, $array_input, 'flt_');
        $filtros_sessao = getSessionFilters('flt_');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."text"){

                    $notify->where(function($q) use ($valor_filtro){
                        $q->where('code', 'like', '%'.$valor_filtro.'%')->orWhere('title', 'like', '%'.$valor_filtro.'%');
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1) {

                        $notify->where('has_read', 1);
                    } else if ($valor_filtro == 2) {

                        $notify->where('has_read', 0);
                    } else if ($valor_filtro == 3) {

                        $notify->where('has_read', 2);
                    }
                }

            }
        }

        return view('gree_i.notifications', [
            'notify' => $notify->paginate(10),
        ]);
    }

    public function verifyImd(Request $request) {

        if ($request->input('c_rcode') == $request->input('registration')) {

            return response()->json([
                'success' => false,
                'error' => "Você não pode cadastrar a sí mesmo como imediato.",
            ]);

        } else if (Users::where('r_code', $request->input('c_rcode'))->count() == 0) {

            return response()->json([
                'success' => false,
                'error' => "O usuário informado não está cadastrado no sistema.",
            ]);
        } else {

            $imdt = Users::where('r_code', $request->input('c_rcode'))->first();
            if ($imdt->picture) {
                $img = $imdt->picture;
            } else {
                $img = '/media/avatars/avatar10.jpg';
            }


            return response()->json([
                'success' => true,
                'r_code' => $request->input('c_rcode'),
                'picture' => $img,
                'name' => getENameF($request->input('c_rcode')),
            ]);
        }
    }

    public function tripDelete(Request $request, $id) {
        $trip = Trips::where('id', $id)->where('r_code', $request->session()->get('r_code'))->first();

        if ($trip) {

            $trips = DB::table('trip_plan')
                ->where('trip_plan.trip_id', $id)
                ->get();

            foreach ($trips as $key) {

                $peoples = DB::table('trip_peoples')
                    ->where('trip_plan_id', $key->id)
                    ->get();

                foreach ($peoples as $people) {
                    DB::table('trip_peoples')->where('id', $people->id)->delete();
                }

                DB::table('trip_plan')->where('id', $key->id)->delete();
            }

            DB::table('trips')->where('id', $id)->where('r_code', $request->session()->get('r_code'))->delete();
            LogSystem("Colaborador excluiu todo planejamento de voo", $request->session()->get('r_code'));

            return \Redirect::route('trip/my');

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }

    }
	
	 public function tripRequestApprov(Request $request, $id, $plan) {
        try {
            $trip = TripPlan::with('trips.user.immediates')->find($plan);
            if(!$trip)
				return redirect()->back()->with('error', 'Não foi possível encontrar o planejamento de viagem.'); 
            if($trip) {
                $solicitation = new TripPlanAnalyze($trip, $request);
                $do_analyze = new ProcessAnalyze($solicitation);
                $do_analyze->eventStart();
            }
            return redirect()->back()->with('success', 'Planejamento de voo enviada para aprovação');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function tripRequestApprov_(Request $request, $id, $plan) {

        $trip = Trips::where('id', $id)->where('r_code', $request->session()->get('r_code'))->first();

        if ($trip) {

            $trip_plan = TripPlan::where('id', $plan)->first();
            $peoples = TripPeoples::where('trip_plan_id', $plan)->count();
            $trip_plan->has_analyze = 1;
            $t_r = Trips::where('id', $trip_plan->trip_id)->first();

            $trip_plan->save();

            LogSystem("Colaborador enviou planejamento para análise de aprovação", $request->session()->get('r_code'));

            if (date('Y-m-d', strtotime($trip_plan->origin_date)) <= date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 day'))) {

                $pattern = array(
                    'name' => getNameFormated(),
                    'imd' => '0004',
                    'id' => $trip_plan->id,
                    'r_code' => $request->session()->get('r_code'),
                    'finality' => finalityName($trip_plan->finality),
                    'goal' => $trip_plan->goal,
                    'origin_country' => GetCountryName($trip_plan->origin_country),
                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                    'origin_city' => $trip_plan->origin_city,
                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                    'destiny_city' => $trip_plan->destiny_city,
                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                    'has_hotel' => $trip_plan->has_hotel,
                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                    'dispatch' => $trip_plan->dispatch,
                    'dispatch_reason' => $trip_plan->dispatch_reason,
                    'title' => 'PEDIDO REQUER APROVAÇÃO',
                    'description' => '',
                    'peoples' => $peoples,
                    'template' => 'trip.RequestApprov',
                    'subject' => 'Pedido de aprovação de viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                );

                $imdt = Users::where('r_code', '0004')->first();
                SendMailJob::dispatch($pattern, $imdt->email);
                App::setLocale($imdt->lang);
                NotifyUser(__('layout_i.n_trip_006_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_006'), $request->root() .'/trip/review/'. $trip_plan->id);
                App::setLocale($request->session()->get('lang'));
                $immediate = Users::where('r_code', '0004')->get();

            } else {
                // GET BOSS IMMEDIATE
                $immediate = UserImmediate::where('user_r_code', $request->session()->get('r_code'))->get();

                if (count($immediate) > 0) {

                    foreach ($immediate as $key) {

                        $imdt = Users::where('r_code', $key->immediate_r_code)->first();


                        if ($imdt->is_holiday == 1) {
                            $usrhd = \App\Model\UserHoliday::where('user_r_code', $imdt->r_code)->get();
                            foreach($usrhd as $usr) {

                                $imdt = Users::where('r_code', $usr->receiver_r_code)->first();

                                $pattern = array(
                                    'name' => getNameFormated(),
                                    'imd' => $imdt->r_code,
                                    'id' => $trip_plan->id,
                                    'r_code' => $request->session()->get('r_code'),
                                    'finality' => finalityName($trip_plan->finality),
                                    'goal' => $trip_plan->goal,
                                    'origin_country' => GetCountryName($trip_plan->origin_country),
                                    'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                                    'origin_city' => $trip_plan->origin_city,
                                    'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                                    'destiny_country' => GetCountryName($trip_plan->destiny_country),
                                    'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                                    'destiny_city' => $trip_plan->destiny_city,
                                    'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                                    'has_hotel' => $trip_plan->has_hotel,
                                    'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                                    'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                                    'dispatch' => $trip_plan->dispatch,
                                    'dispatch_reason' => $trip_plan->dispatch_reason,
                                    'title' => 'PEDIDO REQUER APROVAÇÃO',
                                    'description' => '',
                                    'peoples' => $peoples,
                                    'template' => 'trip.RequestApprov',
                                    'subject' => 'Pedido de aprovação de viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                                );

                                // send email
                                SendMailJob::dispatch($pattern, $imdt->email);
                                App::setLocale($imdt->lang);
                                NotifyUser(__('layout_i.n_trip_006_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_006'), $request->root() .'/trip/review/'. $trip_plan->id);
                                App::setLocale($request->session()->get('lang'));
                            }
                        } else {

                            $pattern = array(
                                'name' => getNameFormated(),
                                'imd' => $imdt->r_code,
                                'id' => $trip_plan->id,
                                'r_code' => $request->session()->get('r_code'),
                                'finality' => finalityName($trip_plan->finality),
                                'goal' => $trip_plan->goal,
                                'origin_country' => GetCountryName($trip_plan->origin_country),
                                'origin_state' => GetStateName($trip_plan->origin_country, $trip_plan->origin_state),
                                'origin_city' => $trip_plan->origin_city,
                                'origin_date' => date('Y-m-d', strtotime($trip_plan->origin_date)),
                                'destiny_country' => GetCountryName($trip_plan->destiny_country),
                                'destiny_state' => GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state),
                                'destiny_city' => $trip_plan->destiny_city,
                                'destiny_date' => date('Y-m-d', strtotime($trip_plan->destiny_date)),
                                'has_hotel' => $trip_plan->has_hotel,
                                'hotel_exit' => date('Y-m-d', strtotime($trip_plan->hotel_exit)),
                                'hotel_date' => date('Y-m-d', strtotime($trip_plan->hotel_date)),
                                'dispatch' => $trip_plan->dispatch,
                                'dispatch_reason' => $trip_plan->dispatch_reason,
                                'title' => 'PEDIDO REQUER APROVAÇÃO',
                                'description' => '',
                                'peoples' => $peoples,
                                'template' => 'trip.RequestApprov',
                                'subject' => 'Pedido de aprovação de viagem: #'. $trip_plan->id .' "'. getENameF($t_r->r_code) .'" '.', "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
                            );

                            // send email
                            SendMailJob::dispatch($pattern, $imdt->email);
                            App::setLocale($imdt->lang);
                            NotifyUser(__('layout_i.n_trip_006_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_trip_006'), $request->root() .'/trip/review/'. $trip_plan->id);
                            App::setLocale($request->session()->get('lang'));
                        }

                    }
                }

                $immediate = UserImmediate::leftJoin('user_on_permissions', 'user_immediate.immediate_r_code', '=', 'user_on_permissions.user_r_code')
                    ->leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                    ->select('users.*')
                    ->where('user_immediate.user_r_code', $request->session()->get('r_code'))
                    ->where('user_on_permissions.can_approv', 1)
                    ->where('user_on_permissions.perm_id', 1)
                    ->groupBy('user_immediate.immediate_r_code')
                    ->get();
            }

            $pattern = array(
                'id' => $plan,
                'immediates' => $immediate,
                'title' => 'PEDIDO FOI REALIZADO',
                'description' => '',
                'template' => 'trip.RequestSuccess',
                'subject' => 'Pedido da viagem: #'. $plan .' "'. GetStateName($trip_plan->origin_country, $trip_plan->origin_state) .' -> '. GetStateName($trip_plan->destiny_country, $trip_plan->destiny_state) .'"',
            );

            $user = Users::where('r_code', $request->session()->get('r_code'))->first();
            SendMailJob::dispatch($pattern, $user->email);

            $request->session()->put('success', __('trip_i.tn_send_request'));
            return redirect('trip/detail/'. $id .'');

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }
    }

    public function tripNew_do(Request $request) {

        // JSON
        $raw_payload = $request->input('data_input');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {

            LogSystem("Colaborador criou um novo planejamento de viagem.", $request->session()->get('r_code'));

            $trip = new Trips;
            $trip->r_code = $request->session()->get('r_code');
            $trip->is_completed = 0;
            $trip->save();

            foreach ($payload as $key) {

                $trip_plan = new TripPlan;
                $trip_plan->trip_id = $trip->id;
                $trip_plan->finality = $key['finality'];
                $trip_plan->other = $key['other'];
                $trip_plan->goal = $key['goal'];
                $trip_plan->origin_date = $key['flight']['origin_date'];
                $trip_plan->origin_period = $key['flight']['origin_period'];
                $trip_plan->origin_country = $key['flight']['origin_country'];
                $trip_plan->origin_state = $key['flight']['origin_state'];
                $trip_plan->origin_city = $key['flight']['origin_city'];
                $trip_plan->destiny_date = $key['flight']['destiny_date'];
                $trip_plan->destiny_period = $key['flight']['destiny_period'];
                $trip_plan->destiny_country = $key['flight']['destiny_country'];
                $trip_plan->destiny_state = $key['flight']['destiny_state'];
                $trip_plan->destiny_city = $key['flight']['destiny_city'];
                $trip_plan->dispatch = $key['flight']['dispatch'];
                $trip_plan->dispatch_reason = $key['flight']['dispatch_info'];
                $trip_plan->has_hotel = $key['has_hotel'];
                if ($key['has_hotel'] == 1) {
                    $trip_plan->hotel_date = $key['hotel']['enter_date'];
                    $trip_plan->hotel_checkout = $key['hotel']['checkout'];
                    $trip_plan->hotel_country = $key['hotel']['enter_country'];
                    $trip_plan->hotel_state = $key['hotel']['enter_state'];
                    $trip_plan->hotel_city = $key['hotel']['enter_city'];
                    $trip_plan->hotel_exit = $key['hotel']['exit_date'];
                    $trip_plan->hotel_address = $key['hotel']['address'];
                } else {
                    $trip_plan->hotel_date = date('Y-m-d H:i:s');
                    $trip_plan->hotel_exit = date('Y-m-d H:i:s');
                }
                $trip_plan->is_approv = 0;
                $trip_plan->is_reprov = 0;
                $trip_plan->has_analyze = 0;
                $trip_plan->save();

                foreach ($key['peoples'] as $people) {
                    $trip_peoples = new TripPeoples;
                    $trip_peoples->trip_plan_id = $trip_plan->id;
                    $trip_peoples->name = $people['name'];
                    $trip_peoples->identity = $people['r_code'];
                    $trip_peoples->save();
                }

            }

            return \Redirect::route('trip/my');
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');
        }

    }

    public function states(Request $request) {
        $country = $request->input('country');

        $state = '<option value=""></option>';

        $data_state = Regions::where('country_id', $country)->get();

        foreach ($data_state as $key) {
            $state .= '<option value="'. $key->id .'">'. $key->name .'</option>';
        }

        return $state;
    }

    public function logout(Request $request) {

        $request->session()->flush();
        return \Redirect::route('login');
    }

    public function userRegister(Request $request) {

        $sector = Sector::all();
        $usersall = Users::all();

        return view('gree_i.register', [
            'sector' => $sector,
            'usersall' => $usersall,
        ]);
    }

    public function userRegister_do(Request $request) {

        $picture = $request->file('picture');
        $picture = $request->picture;
        $isnew = $request->input('is_new');
        $rcode = $request->input('registration');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('reg_email');
        $password = $request->input('password');
        $active = $request->input('user_active');
        $phone = $request->input('phone');
        $birthday = $request->input('birthday');
        $office = $request->input('office');
        $gree = $request->input('gree');
        $sector = $request->input('sector');
        $active = $request->input('user_active');
        $has_img = 0;

        if (!googleRecaptchaV3($request->token, $request->action)) {

            $request->session()->put('error', 'Não foi possível realizar o cadastro, pois o google detectou que você é um robo.');
            return redirect()->back();
        }

        $user = new Users;

        if (Users::where('r_code', $rcode)->count() > 0) {

            $request->session()->put('error', "Esse número: (". $rcode .") de matricula já está sendo usado.");
            return Redirect('/register');
        }

        if ($request->hasFile('picture')) {
            $extension = $request->picture->extension();
            if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg') {

                $validator = Validator::make(
                    [
                        'picture' => $picture,
                    ],
                    [
                        'picture' => 'required|max:500',
                    ]
                );

                if ($validator->fails()) {

                    $request->session()->put('error', "Tamanho máximo da imagem é de 500kb, diminua a resolução/tamanho da mesma.");
                    return Redirect('/register');
                } else {

                    $has_img = 1;
                }

            } else {

                $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                return Redirect('/register');
            }
        }

        $user->r_code = $rcode;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->is_active = 0;
        $user->email = $email;
        $user->phone = $phone;
        $user->birthday = $birthday;
        $user->office = $office;
        $user->gree_id = $gree;
        $user->sector_id = $sector;
        $user->password = Hash::make($password);
        $user->save();

        if ($has_img == 1) {
            $user = Users::find($user->id);
            $img_name = date('YmdHis') .'.'. $extension;
            $request->picture->storeAs('/', $img_name, 's3');

            $url = Storage::disk('s3')->url($img_name);

            $user->picture = $url;
            $user->save();
        }

        // JSON IMMEDIATE
        $raw_payload = $request->input('data_input');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {

            foreach ($payload as $key) {
                $immediate = new UserImmediate;
                $immediate->user_r_code = $user->r_code;
                $immediate->immediate_r_code = $key['r_code'];
                $immediate->save();

            }

        }

        // ADD PERMISSIONS
        $perm_add = new UserOnPermissions;
        $perm_add->perm_id = 1;
        $perm_add->user_r_code  = $user->r_code;
        $perm_add->save();

        $perm_add = new UserOnPermissions;
        $perm_add->perm_id = 3;
        $perm_add->user_r_code  = $user->r_code;
        $perm_add->save();

        $perm_add = new UserOnPermissions;
        $perm_add->perm_id = 9;
        $perm_add->user_r_code  = $user->r_code;
        $perm_add->save();

        $perm_add = new UserOnPermissions;
        $perm_add->perm_id = 11;
        $perm_add->user_r_code  = $user->r_code;
        $perm_add->save();

        $perm_add = new UserOnPermissions;
        $perm_add->perm_id = 12;
        $perm_add->user_r_code  = $user->r_code;
        $perm_add->save();

        ListTransmission::where('email', $email)->delete();
        $pattern = array(
            'title' => 'BEM-VINDO AO SISTEMA GREE',
            'description' => '',
            'template' => 'user.NewUser',
            'subject' => 'Bem-vindo ao sistema Gree',
        );

        SendMailJob::dispatch($pattern, $email);
        $request->session()->put('success', 'Sua conta foi criada com sucesso! Aguarde ativação da mesma.');
        return Redirect('/login');

    }

    public function userPicture(Request $request) {

        $picture = $request->file('picture');
        $picture = $request->picture;

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if ($request->hasFile('picture')) {
            $extension = $request->picture->extension();
            if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg') {

                $validator = Validator::make(
                    [
                        'picture' => $picture,
                    ],
                    [
                        'picture' => 'required|max:500',
                    ]
                );

                if ($validator->fails()) {

                    $request->session()->put('error', "Tamanho máximo da imagem é de 500kb, diminua a resolução/tamanho da mesma.");
                    return Redirect('/news');
                } else {

                    $has_img = 1;
                }

            } else {

                $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                return Redirect('/news');
            }
        }

        $img_name = date('YmdHis') .'.'. $extension;
        $request->picture->storeAs('/', $img_name, 's3');

        $url = Storage::disk('s3')->url($img_name);

        $user->picture = $url;
        $user->save();

        $request->session()->put('picture', $url);

        $request->session()->put('success', 'Sua foto de perfil foi atualizada com sucesso!');
        return Redirect('/news');

    }

    public function updateEmail(Request $request) {
        $email = $request->input('email');
        $r_code = $request->session()->get('r_code');
        if ($email and $r_code) {
            $email = $email ."@gree-am.com.br";
            if (Users::where('email', $email)->count() == 0) {

                $user = Users::where('r_code', $r_code)->first();
                $user->email = $email;
                $user->save();

                $request->session()->put('email', $email);

                // Write Log
                LogSystem("Colaborador atualizou seu email na plataforma.", $request->session()->get('r_code'));
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return \Redirect::route('news');
            } else {
                // Error, email exists
                // Write Log
                LogSystem("Colaborador tentou atualizar o email, mas já está sendo usado o email: ". $email, $request->session()->get('r_code'));
                App::setLocale($request->session()->get('lang'));
                return \Redirect::route('news', ['error' => __('layout_i.email_update_error')]);
            }
        } else {
            // Error, Try update without the fields
            // Write Log
            LogSystem("Tentativa de envio de atualização de email, fora do padrão da plataforma. Isso pode ser um ataque! ", 0);
            return \Redirect::route('login');
        }
    }

    public function taskEdit(Request $request, $id) {

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();
        $request->session()->put('temp_id', $id);
        if ($id == 0) {

            $start_date = "";
            $end_date = "";
            $title = "";
            $description = "";
            $attach = "";
            $res = "";
            $copy = "";
            $colab = Users::where('r_code', '!=', $request->session()->get('r_code'))->get();

            LogSystem("Colaborador criando uma nova tarefa, identificador da tarefa: ", $request->session()->get('r_code'));

        } else {

            $task = Task::find($id);

            $start_date = $task->start_date;
            $end_date = $task->end_date;
            $sectorid = $task->sector_id;
            $title = $task->title;
            $description = $task->description;
            $attach = $task->attach;
            $copy = TaskCopyContact::where('task_id', $id)->get();
            $res = TaskResponsible::where('task_id', $id)->get();
            $colab = Users::where('r_code', '!=', $request->session()->get('r_code'))->get();

            LogSystem("Colaborador atualizando tarefa, identificador da tarefa: ". $id, $request->session()->get('r_code'));

        }

        return view('gree_i.task.task_edit', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'title' => $title,
            'description' => $description,
            'attach' => $attach,
            'id' => $id,
            'res' => $res,
            'colab' => $colab,
            'copy' => $copy,
        ]);
    }

    public function taskStatus(Request $request, $id) {

        $task = Task::find($id);

        if ($task) {
            $task->is_cancelled = $task->is_cancelled == 0 ? 1 : 0;
            $task->save();

            if ($task->is_cancelled == 1) {
                $request->session()->put('success', __('project_i.msg_12'));
                LogSystem("Colaborador desativou a tarefa, identificador da tarefa: ". $id, $request->session()->get('r_code'));

                $history = new TaskHistory;
                $history->task_id = $task->id;
                $history->icon = "fa-lock";
                $history->color = "danger";
                $history->description = "Tarefa foi fechada por enquanto...";
                $history->date = date('Y-m-d');
                if ($task->attach) {
                    $history->attach = $task->attach;
                }
                if ($task->is_file) {
                    $history->is_file = $task->is_file;
                }
                $history->save();

            } else {

                $request->session()->put('success', __('project_i.msg_11'));
                LogSystem("Colaborador ativou a tarefa, identificador da tarefa: ". $id, $request->session()->get('r_code'));

                $history = new TaskHistory;
                $history->task_id = $task->id;
                $history->icon = "fa-unlock-alt";
                $history->color = "success";
                $history->description = "Tarefa foi reaberta para continuação...";
                $history->date = date('Y-m-d');
                if ($task->attach) {
                    $history->attach = $task->attach;
                }
                if ($task->is_file) {
                    $history->is_file = $task->is_file;
                }
                $history->save();

            }

            return Redirect('/task/view/my');

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return redirect('/news');
        }
    }

    public function taskEdit_do(Request $request) {

        $id = $request->session()->get('temp_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $title = $request->input('title');
        $description = $request->input('description');
        $attach = $request->file('attach');
        $attach = $request->attach;

        if ($id == 0) {

            $task = new Task;

        } else {

            $task = Task::find($id);

            if ($task->is_approv == 1) {

                return Redirect('/task/my');
            }
        }

        $mUser = Users::where('r_code', $request->session()->get('r_code'))->first();
        $task->sector_id = $mUser->sector_id;
        $task->title = $title;
        $task->description = $description;
        $task->has_analyze = 1;
        $task->start_date = $start_date;
        if ($id == 0) {
            $task->r_code = $request->session()->get('r_code');
        }
        $task->end_date = $end_date;

        if ($request->hasFile('attach')) {

            $extension = $request->attach->extension();
            $img_name = date('YmdHis') .'.'. $extension;
            $request->attach->storeAs('/', $img_name, 's3');
            $url = Storage::disk('s3')->url($img_name);
            $task->attach = $url;
        }

        $task->save();
        $raw_payload = $request->input('data_res');
        $payload = json_decode($raw_payload, true);

        TaskResponsible::where('task_id', $task->id)->delete();
        foreach ($payload as $key) {
            $resp = new TaskResponsible;
            $resp->r_code = $key['id'];
            $resp->task_id = $task->id;
            $resp->save();

        }

        if ($request->input('data_copy')) {
            $raw_payload = $request->input('data_copy');
            $payload = json_decode($raw_payload, true);

            TaskCopyContact::where('task_id', $task->id)->delete();
            foreach ($payload as $key) {
                if (!empty($key['email'])) {
                    $contact = new TaskCopyContact;
                    $contact->email = $key['email'];
                    $contact->task_id = $task->id;
                    $contact->save();
                }
            }
        }

        if ($id == 0) {
            $request->session()->put('success', __('project_i.msg_3'));

            $history = new TaskHistory;
            $history->task_id = $task->id;
            $history->icon = "fa-clock-o";
            $history->color = "warning";
            $history->description = "Aguardando aceitamento da tarefa...";
            $history->date = date('Y-m-d');
            if ($task->attach) {
                $history->attach = $task->attach;
            }
            if ($task->is_file) {
                $history->is_file = $task->is_file;
            }
            $history->save();

            $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                ->where('task_id', $task->id)
                ->select('users.*')
                ->first();
            $emails_cc = TaskCopyContact::where('task_id', $task->id)->get();

            if ($responsable) {

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => 'NOVA TAREFA',
                    'description' => 'Você foi designado a realizar uma nova tarefa, veja abaixo as informações e não esqueça de acessar o link de mais detalhes para responder!',
                    'template' => 'task.RequestAccept',
                    'subject' => 'Nova tarefa pendente: #'. $task->id .' "'. $task->title .'"',
                );

                $user = Users::where('r_code', $responsable->r_code)->first();
                SendMailCopyJob::dispatch($pattern, $user->email);
                App::setLocale($user->lang);
                NotifyUser(__('layout_i.n_proj_001_title'), $user->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_proj_001'), $request->root() .'/task/view/history/'. $task->id);
                App::setLocale($request->session()->get('lang'));
                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $task->id)
                    ->select('users.*')
                    ->get();

                $pattern = array(
                    'id' => $task->id,
                    'responsable' => $responsable,
                    'title' => 'TAREFA FOI ENVIADA',
                    'description' => '',
                    'template' => 'task.RequestSuccess',
                    'subject' => 'Pedido de tarefa: #'. $task->id .' "'. $task->title .'"',
                );

                $user = Users::where('r_code', $request->session()->get('r_code'))->first();
                SendMailJob::dispatch($pattern, $user->email);


            } else {


                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $task->id)
                    ->select('users.*')
                    ->get();

                $pattern = array(
                    'id' => $task->id,
                    'responsable' => $responsable,
                    'title' => 'TAREFA FOI ENVIADA',
                    'description' => '',
                    'template' => 'task.RequestSuccess',
                    'subject' => 'Pedido de tarefa: #'. $task->id .' "'. $task->title .'"',
                );

                $user = Users::where('r_code', $request->session()->get('r_code'))->first();
                SendMailJob::dispatch($pattern, $user->email);

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => 'NOVA TAREFA',
                    'description' => 'Você foi designado a realizar uma nova tarefa, veja abaixo as informações e não esqueça de acessar o link de mais detalhes para responder!',
                    'template' => 'task.RequestAccept',
                    'subject' => 'Nova tarefa pendente: #'. $task->id .' "'. $task->title .'"',
                );

                // GET EMAIL ADM GERAL
                SendMailCopyJob::dispatch($pattern, getConfig("email_adm"));
            }

        } else if ($task->is_recuse == 1) {
            $task = Task::find($task->id);
            $task->is_recuse = 0;
            $task->save();
            $history = new TaskHistory;
            $history->task_id = $task->id;
            $history->icon = "fa-pencil";
            $history->color = "info";
            $history->description = "Tarefa envianda novamente para aceitamento...";
            $history->date = date('Y-m-d');
            if ($task->attach) {
                $history->attach = $task->attach;
            }
            if ($task->is_file) {
                $history->is_file = $task->is_file;
            }
            $history->save();

            $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                ->where('task_id', $task->id)
                ->select('users.*')
                ->first();
            $emails_cc = TaskCopyContact::where('task_id', $task->id)->get();

            if ($responsable) {

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => 'TAREFA ATUALIZADA',
                    'description' => 'Gestor atualizou as informações da tarefa, veja abaixo as informações e não esqueça de acessar o link de mais detalhes para aceitar ou recusar!',
                    'template' => 'task.RequestAccept',
                    'subject' => 'Tarefa atualizada: #'. $task->id .' "'. $task->title .'"',
                );

                $user = Users::where('r_code', $responsable->r_code)->first();
                SendMailCopyJob::dispatch($pattern, $user->email);
                App::setLocale($user->lang);
                NotifyUser(__('layout_i.n_proj_001_title'), $user->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_proj_001'), $request->root() .'/task/view/history/'. $task->id);
                App::setLocale($request->session()->get('lang'));
                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $task->id)
                    ->select('users.*')
                    ->get();

                $pattern = array(
                    'id' => $task->id,
                    'responsable' => $responsable,
                    'title' => 'ATUALIZAÇÃO DE TAREFA ENVIADA',
                    'description' => '',
                    'template' => 'task.RequestSuccess',
                    'subject' => 'Atualização de tarefa: #'. $task->id .' "'. $task->title .'"',
                );

                $user = Users::where('r_code', $request->session()->get('r_code'))->first();
                SendMailJob::dispatch($pattern, $user->email);

            } else {

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => 'TAREFA ATUALIZADA',
                    'description' => 'Gestor atualizou as informações da tarefa, veja abaixo as informações e não esqueça de acessar o link de mais detalhes para aceitar ou recusar!',
                    'template' => 'task.RequestAccept',
                    'subject' => 'Tarefa atualizada: #'. $task->id .' "'. $task->title .'"',
                );

                // GET EMAIL ADM GERAL
                SendMailCopyJob::dispatch($pattern, getConfig("email_adm"));

                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $task->id)
                    ->select('users.*')
                    ->get();

                $pattern = array(
                    'id' => $task->id,
                    'responsable' => $responsable,
                    'title' => 'ATUALIZAÇÃO DE TAREFA ENVIADA',
                    'description' => '',
                    'template' => 'task.RequestSuccess',
                    'subject' => 'Atualização de tarefa: #'. $task->id .' "'. $task->title .'"',
                );

                $user = Users::where('r_code', $request->session()->get('r_code'))->first();
                SendMailJob::dispatch($pattern, $user->email);
            }

            LogSystem("Gestor re-enviou sua tarefa para colaborador aceitar, identificador da tarefa: ". $id, $request->session()->get('r_code'));
            $request->session()->put('success', __('project_i.msg_10'));
        } else {
            $request->session()->put('success', __('project_i.msg_4'));
            LogSystem("Colaborador atualizou a lista de tarefas, identificador da tarefa: ". $task->id, $request->session()->get('r_code'));
        }

        return Redirect('/task/view/my');
    }

    public function taskReSend(Request $request, $id) {

        $task = Task::find($id);

        if ($task) {
            $task->has_analyze = 1;
            $task->is_recuse = 0;
            $task->save();

            $request->session()->put('success', __('project_i.msg_10'));

            $history = new TaskHistory;
            $history->task_id = $task->id;
            $history->icon = "fa-pencil";
            $history->color = "info";
            $history->description = "Tarefa envianda novamente para aceitamento...";
            $history->date = date('Y-m-d');
            if ($task->attach) {
                $history->attach = $task->attach;
            }
            if ($task->is_file) {
                $history->is_file = $task->is_file;
            }
            $history->save();

            $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                ->where('task_id', $task->id)
                ->select('users.*')
                ->first();
            $emails_cc = TaskCopyContact::where('task_id', $task->id)->get();

            if ($responsable) {

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => 'TAREFA ATUALIZADA',
                    'description' => 'Gestor atualizou as informações da tarefa, veja abaixo as informações e não esqueça de acessar o link de mais detalhes para aceitar ou recusar!',
                    'template' => 'task.RequestAccept',
                    'subject' => 'Tarefa atualizada: #'. $task->id .' "'. $task->title .'"',
                );

                $user = Users::where('r_code', $responsable->r_code)->first();
                SendMailCopyJob::dispatch($pattern, $user->email);
                App::setLocale($user->lang);
                NotifyUser(__('layout_i.n_proj_001_title'), $user->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_proj_001'), $request->root() .'/task/view/history/'. $task->id);
                App::setLocale($request->session()->get('lang'));

            } else {

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => 'TAREFA ATUALIZADA',
                    'description' => 'Gestor atualizou as informações da tarefa, veja abaixo as informações e não esqueça de acessar o link de mais detalhes para aceitar ou recusar!',
                    'template' => 'task.RequestAccept',
                    'subject' => 'Tarefa atualizada: #'. $task->id .' "'. $task->title .'"',
                );

                // GET EMAIL ADM GERAL
                SendMailCopyJob::dispatch($pattern, getConfig("email_adm"));
            }
        }

        LogSystem("Gestor re-enviou sua tarefa para colaborador aceitar, identificador da tarefa: ". $id, $request->session()->get('r_code'));

        return Redirect('/task/view/history/'. $task->id);
    }

    public function taskMy(Request $request) {

        // SAVE FILTERS
        if (!empty($request->input('sector'))) {
            $request->session()->put('taskf_sector', $request->input('sector'));
        } else {
            $request->session()->forget('taskf_sector');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('taskf_status', $request->input('status'));
        } else {
            $request->session()->forget('taskf_status');
        }
        if (!empty($request->input('rcodes'))) {
            $request->session()->put('taskf_rcodes', $request->input('rcodes'));
        } else {
            $request->session()->forget('taskf_rcodes');
        }

        $colab = UserImmediate::where('immediate_r_code', $request->session()->get('r_code'))->get();
        $sector = Sector::all();

        if (hasPermApprov(3)) {
            $task = Task::leftJoin('sector', 'task.sector_id', '=', 'sector.id')
                ->leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')
                ->leftJoin('users', 'task_responsible.r_code', '=', 'users.r_code')
                ->select('task.*', 'sector.name as sector_name', 'task_responsible.r_code as tsk_res_rcode', 'users.r_code as users_r_code', 'users.picture as users_picture')
                ->groupBy('task.id');

            $completed = Task::where('is_completed', 1)->where('is_recuse', 0)->where('is_cancelled', 0)->count();
            $progress = Task::where('is_completed', 0)->where('is_recuse', 0)->where('is_cancelled', 0)->where('is_accept', 1)->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>', date('Y-m-d'))->count();
            $late = Task::where('is_completed', 0)->where('is_recuse', 0)->where('is_cancelled', 0)->where('is_accept', 1)->where('end_date', '<=', date('Y-m-d'))->where('start_date', '<', date('Y-m-d'))->count();
            $total = Task::where('is_recuse', 0)->where('is_cancelled', 0)->count();


        } else {
            $task = Task::leftJoin('sector', 'task.sector_id', '=', 'sector.id')
                ->leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')
                ->leftJoin('users', 'task_responsible.r_code', '=', 'users.r_code')
                ->select('task.*', 'sector.name as sector_name', 'task_responsible.r_code as tsk_res_rcode', 'users.r_code as users_r_code', 'users.picture as users_picture')
                ->where('task_responsible.r_code', $request->session()->get('r_code'))
                ->groupBy('task.id');

            $completed = Task::leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')->where('task_responsible.r_code', $request->session()->get('r_code'))->where('task.is_completed', 1)->where('task.is_recuse', 0)->where('task.is_cancelled', 0)->count();
            $progress = Task::leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')->where('task_responsible.r_code', $request->session()->get('r_code'))->where('task.is_completed', 0)->where('task.is_recuse', 0)->where('task.is_cancelled', 0)->where('task.is_accept', 1)->where('task.start_date', '<=', date('Y-m-d'))->where('task.end_date', '>', date('Y-m-d'))->count();
            $late = Task::leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')->where('task_responsible.r_code', $request->session()->get('r_code'))->where('task.is_completed', 0)->where('task.is_recuse', 0)->where('task.is_cancelled', 0)->where('task.is_accept', 1)->where('task.end_date', '<=', date('Y-m-d'))->where('task.start_date', '<', date('Y-m-d'))->count();
            $total = Task::leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')->where('task_responsible.r_code', $request->session()->get('r_code'))->where('task.is_recuse', 0)->where('task.is_cancelled', 0)->count();
        }

        if (!empty($request->session()->get('taskf_sector'))) {
            $task->where('task.sector_id', $request->input('sector'));
        }
        if (!empty($request->session()->get('taskf_rcodes'))) {
            $task->where('task_responsible.r_code', $request->input('rcodes'));
        }
        if (!empty($request->session()->get('taskf_status'))) {
            if ($request->input('status') == 1) {
                $task->where('task.is_completed', 1);
            } else if ($request->input('status') == 2) {
                $task->where('task.start_date', '<=', date('Y-m-d'))
                    ->where('task.is_accept', 1);
            } else if ($request->input('status') == 3) {
                $task->where('task.end_date', '<=', date('Y-m-d'))
                    ->where('task.is_accept', 1);
            } else if ($request->input('status') == 4) {
                $task->where('task.is_accept', 1);
            } else if ($request->input('status') == 5) {
                $task->where('task.is_recuse', 1);
            } else if ($request->input('status') == 6) {
                $task->where('task.is_cancelled', 1);
            } else if ($request->input('status') == 7) {
                $task->where('task.has_analyze', 1);
            } else if ($request->input('status') == 8) {
                $task->where('task.has_analyze', 1)
                    ->where('task.is_accept', 1)
                    ->where('task.is_completed', 0);
            }
        }

        return view('gree_i.task.task_my_list', [
            'task' => $task->paginate(10),
            'colab' => $colab,
            'sector' => $sector,
            'completed' => $completed,
            'progress' => $progress,
            'late' => $late,
            'total' => $total,
        ]);
    }

    public function taskAnalyze(Request $request, $id) {

        $task = Task::find($id);

        if ($task) {
            if ($request->input('type') == 1) {
                $task->is_accept = 1;
                $task->has_analyze = 0;
                $task->save();

                $analyze = new TaskAnalyze;
                $analyze->is_accept = 1;
                $analyze->task_id = $id;
                $analyze->description = $request->input('description');
                $analyze->r_code = $request->session()->get('r_code');
                $analyze->save();

                $history = new TaskHistory;
                $history->task_id = $task->id;
                $history->icon = "fa-check";
                $history->color = "success";
                $history->description = "Aceitou a tarefa!";
                $history->date = date('Y-m-d');
                $history->save();

                LogSystem("Responsável da tarefa do colaborador, aceitou a tarefa. Identificador da tarefa: ". $id, $request->session()->get('r_code'));

            } else {
                $task->is_recuse = 1;
                $task->has_analyze = 0;
                $task->save();

                $analyze = new TaskAnalyze;
                $analyze->task_id = $id;
                $analyze->description = $request->input('description');
                $analyze->is_recuse = 1;
                $analyze->r_code = $request->session()->get('r_code');
                $analyze->save();

                $history = new TaskHistory;
                $history->task_id = $task->id;
                $history->icon = "fa-times";
                $history->color = "danger";
                $history->description = $request->input('description');
                $history->date = date('Y-m-d');
                $history->save();

                LogSystem("Responsável da tarefa do colaborador, negou a tarefa. Identificador da tarefa: ". $id, $request->session()->get('r_code'));
            }

            $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                ->where('task_id', $task->id)
                ->select('users.*')
                ->first();

            $emails_cc = TaskCopyContact::where('task_id', $task->id)->get();

            $title = $request->input('type') == 1 ? "TAREFA ACEITA" : "TAREFA RECUSADA";
            $subject = $request->input('type') == 1 ? 'Tarefa #'. $task->id .' "'. $task->title .'" foi aceita' : 'Tarefa #'. $task->id .' "'. $task->title .'" foi recusado';
            $body = $request->input('type') == 1 ? "Responsável pela tarefa, aceitou, agora mantenha-se atendo nas notificações do painel ou no email para novas informações sobre atualização da tarefa." : "O responsável da tarefa recusou a tarefa com a seguinte justificativa: ". $request->input('description');

            if ($responsable) {

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => $title,
                    'description' => $body,
                    'template' => 'task.RequestAccept',
                    'subject' => $subject,
                );

                $user = Users::where('r_code', $task->r_code)->first();
                SendMailCopyJob::dispatch($pattern, $user->email);
                App::setLocale($user->lang);
                if ($request->input('type') == 1) {
                    NotifyUser(__('layout_i.n_proj_002_title'), $user->r_code, 'fa-check', 'text-success', __('layout_i.n_proj_002'), $request->root() .'/task/view/history/'. $task->id);
                } else {
                    NotifyUser(__('layout_i.n_proj_003_title'), $user->r_code, 'fa-times', 'text-danger', __('layout_i.n_proj_003'), $request->root() .'/task/view/history/'. $task->id);
                }
                App::setLocale($request->session()->get('lang'));

            } else {

                $pattern = array(
                    'name' => getENameFull($task->r_code),
                    'id' => $task->id,
                    'copys' => $emails_cc,
                    'rcode' => $task->r_code,
                    'responsable' => $responsable,
                    'proj_sector' => sectorName($task->sector_id),
                    'proj_title' => $task->title,
                    'proj_description' => $task->description,
                    'proj_attach' => $task->attach,
                    'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                    'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                    'title' => $title,
                    'description' => $body,
                    'template' => 'task.RequestAccept',
                    'subject' => $subject,
                );

                // GET EMAIL ADM GERAL
                SendMailCopyJob::dispatch($pattern, getConfig("email_adm"));
            }

            return Redirect('/task/view/history/'. $id);
        } else {

            return Redirect('/task/view/my');
        }
    }

    public function taskCompleted(Request $request, $id, $type) {
        $password = $request->input('password');

        $task = Task::find($id);
        if ($task) {
            $user = Users::where('r_code', $task->r_code)->first();
            if (Hash::check($password, $user->password)) {

                if ($type == 1) {
                    $task->is_completed = 1;
                    $task->has_analyze = 0;
                    $task->save();

                    $analyze = new TaskAnalyzeCompleted;
                    $analyze->is_accept = 1;
                    $analyze->task_id = $task->id;
                    $analyze->r_code = $request->session()->get('r_code');
                    $analyze->save();

                    $history = new TaskHistory;
                    $history->task_id = $task->id;
                    $history->title = "Concluído";
                    $history->icon = "fa-flag-checkered";
                    $history->color = "success";
                    $history->description = $request->input('description');
                    $history->date = date('Y-m-d');
                    $history->save();

                    LogSystem("colaborador aceitou a finalização da tarefa. Identificador da tarefa: ". $id, $request->session()->get('r_code'));

                } else {
                    $task->has_analyze = 0;
                    $task->save();

                    $analyze = new TaskAnalyzeCompleted;
                    $analyze->description = $request->input('description');
                    $analyze->is_recuse = 1;
                    $analyze->task_id = $task->id;
                    $analyze->r_code = $request->session()->get('r_code');
                    $analyze->save();

                    $history = new TaskHistory;
                    $history->task_id = $task->id;
                    $history->title = "Finalização recusada";
                    $history->icon = "fa-times";
                    $history->color = "danger";
                    $history->description = $request->input('description');
                    $history->date = date('Y-m-d');
                    $history->save();

                    LogSystem("colaborador recusou a finalização da tarefa. Identificador da tarefa: ". $id, $request->session()->get('r_code'));
                }

                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $task->id)
                    ->select('users.*')
                    ->first();

                $emails_cc = TaskCopyContact::where('task_id', $task->id)->get();

                $title = $type == 1 ? "TAREFA FINALIZADA" : "FINALIZAÇÃO DA TAREFA FOI RECUSADA";
                $subject = $type == 1 ? 'Tarefa #'. $task->id .' "'. $task->title .'" foi finalizada!' : 'Tarefa #'. $task->id .' "'. $task->title .'" recusada a finalização!';
                $body = $type == 1 ? "Gestor da tarefa aceitou a finalização da mesma, para mais detalhes sobre possível informações adicionais deixada pelo gestor, acesse os detalhes da tarefa." : "O gestor da tarefa recusou a finalização com a seguinte justificativa: ". $request->input('description');

                if ($responsable) {

                    $pattern = array(
                        'name' => getENameFull($task->r_code),
                        'id' => $task->id,
                        'copys' => $emails_cc,
                        'rcode' => $task->r_code,
                        'responsable' => $responsable,
                        'proj_sector' => sectorName($task->sector_id),
                        'proj_title' => $task->title,
                        'proj_description' => $task->description,
                        'proj_attach' => $task->attach,
                        'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                        'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                        'title' => $title,
                        'description' => $body,
                        'template' => 'task.RequestAccept',
                        'subject' => $subject,
                    );

                    $user = Users::where('r_code', $responsable->r_code)->first();
                    SendMailCopyJob::dispatch($pattern, $user->email);
                    App::setLocale($user->lang);
                    if ($type == 1) {
                        NotifyUser(__('layout_i.n_proj_006_title'), $user->r_code, 'fa-check', 'text-success', __('layout_i.n_proj_006'), $request->root() .'/task/view/history/'. $task->id);
                    } else {
                        NotifyUser(__('layout_i.n_proj_007_title'), $user->r_code, 'fa-times', 'text-danger', __('layout_i.n_proj_007'), $request->root() .'/task/view/history/'. $task->id);
                    }
                    App::setLocale($request->session()->get('lang'));

                } else {

                    $pattern = array(
                        'name' => getENameFull($task->r_code),
                        'id' => $task->id,
                        'copys' => $emails_cc,
                        'rcode' => $task->r_code,
                        'responsable' => $responsable,
                        'proj_sector' => sectorName($task->sector_id),
                        'proj_title' => $task->title,
                        'proj_description' => $task->description,
                        'proj_attach' => $task->attach,
                        'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                        'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                        'title' => $title,
                        'description' => $body,
                        'template' => 'task.RequestAccept',
                        'subject' => $subject,
                    );

                    // GET EMAIL ADM GERAL
                    SendMailCopyJob::dispatch($pattern, getConfig("email_adm"));
                }

                return Redirect('/task/view/history/'. $id);

            } else if ($user->retry > 0) {
                $user->retry = $user->retry - 1;

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar a finalização da (Tarefa) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                    return Redirect('/logout');
                } else {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->save();

                    $request->session()->put('error', "You missed your secret password, only ". $user->retry ." attempt(s) left.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar a finalização (Tarefa). Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return Redirect('/task/view/history/'. $id);
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar a finalização da (Tarefa) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                return Redirect('/task/view/history/'. $id);
            }
        } else {

            return Redirect('/task/view/my');
        }
    }

    public function taskUpdateSubTask(Request $request) {
        $subattach = $request->file('subattach');
        $subattach = $request->subattach;
        $id = $request->input('id_subtask');

        if ($id == 0) {
            $subtask = new TaskSub;
        } else {
            $subtask = TaskSub::find($id);
        }

        $subtask->task_id = $request->session()->get('temp_id');
        $subtask->r_code = $request->input('rcodes');
        $subtask->resp_r_code = $request->session()->get('r_code');
        $subtask->title = $request->input('title');
        $subtask->is_completed = $request->input('subiscompleted') == 1 ? 1 : 0;
        $subtask->description = $request->input('description');
        $subtask->start_date = $request->input('start_date');
        $subtask->end_date = $request->input('end_date');

        if ($request->hasFile('subattach')) {
            $extension = $request->subattach->extension();
            if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                $validator = Validator::make(
                    [
                        'subattach' => $subattach,
                    ],
                    [
                        'subattach' => 'required|max:10000',
                    ]
                );

                if ($validator->fails()) {

                    $request->session()->put('error', __('project_i.msg_1'));
                    return Redirect('/task/view/history/'. $request->session()->get('temp_id'));
                } else {

                    $img_name = date('YmdHis') .'.'. $extension;
                    $request->subattach->storeAs('/', $img_name, 's3');
                    $url = Storage::disk('s3')->url($img_name);

                    $subtask->attach = $url;
                }

            } else {

                $request->session()->put('error', __('project_i.msg_2'));
                return Redirect('/task/view/history/'. $request->session()->get('temp_id'));
            }
        }

        if  ($id == 0) {

            $task = Task::find($request->session()->get('temp_id'));
            $emails_cc = TaskCopyContact::where('task_id', $task->id)->get();

            $mailcc = array();
            $mng = Users::where('r_code', $task->r_code)->first();
            array_push ($mailcc, $mng->email);
            foreach ($emails_cc as $key) {
                array_push ($mailcc, $key->email);
            }

            $pattern = array(
                'name' => getENameFull($subtask->resp_r_code),
                'id' => $task->id,
                'copys' => $mailcc,
                'rcode' => $subtask->resp_r_code,
                'proj_sector' => sectorName($task->sector_id),
                'proj_title' => $subtask->title,
                'proj_description' => $subtask->description,
                'proj_attach' => $subtask->attach,
                'proj_start_date' => date('Y-m-d', strtotime($subtask->start_date)),
                'proj_end_date' => date('Y-m-d', strtotime($subtask->end_date)),
                'title' => 'NOVA SUB TAREFA',
                'description' => 'Você foi designado a realizar uma sub tarefa, veja abaixo as informações e não deixe de manter o gestor informado!',
                'template' => 'task.RequestSubTask',
                'subject' => 'Nova sub tarefa pendente: #'. $task->id .' "'. $task->title .'"',
            );

            $user = Users::where('r_code', $subtask->r_code)->first();
            SendMailCopyJob::dispatch($pattern, $user->email);
        }

        // Write Log
        LogSystem("Colaborador atualizou sua nova pessoa em sua tarefa para lhe ajudar, matricula da pessoa: ". $subtask->r_code, $request->session()->get('r_code'));

        $subtask->save();
        $request->session()->put('success', 'Sub tarefa foi atualizada com sucesso!');
        return Redirect('/task/view/history/'. $request->session()->get('temp_id'));


    }

    public function taskDeleteSubTask(Request $request, $id) {

        $subtask = TaskSub::where('resp_r_code', $request->session()->get('r_code'))->where('id', $id)->first();

        if ($subtask) {

            // Write Log
            LogSystem("Colaborador excluiu pessoa vinculada a tarefa, matricula da pessoa: ". $subtask->r_code, $request->session()->get('r_code'));

            TaskSub::where('id', $id)->delete();

            $request->session()->put('success', 'Sub tarefa foi removida com sucesso!');
            return Redirect('/task/view/history/'. $request->session()->get('temp_id'));
        } else {
            return Redirect('/task/my/list/');
        }

    }

    public function TaskExportView(Request $request) {

        $colab = UserImmediate::where('immediate_r_code', $request->session()->get('r_code'))->get();
        $manager = UserOnPermissions::where('perm_id', 3)
            ->where('user_r_code', $request->session()->get('r_code'))
            ->where('grade', 99)
            ->orWhere(function ($query) use ($request) {
                $query->where('user_r_code', $request->session()->get('r_code'))
                    ->where('perm_id', 3)
                    ->where('can_approv', 1);
            })
            ->first();

        return view('gree_i.task.task_export', [
            'colab' => $colab,
            'manager' => $manager,
        ]);
    }

    public function taskExport(Request $request) {

        $manager = UserOnPermissions::where('perm_id', 3)
            ->where('user_r_code', $request->session()->get('r_code'))
            ->where('grade', 99)
            ->orWhere(function ($query) use ($request) {
                $query->where('user_r_code', $request->session()->get('r_code'))
                    ->where('perm_id', 3)
                    ->where('can_approv', 1);
            })
            ->first();

        if (isset($manager)) {
            $r_code = $request->Input('r_code');
        } else {
            $r_code = $request->session()->get('r_code');
        }
        $status = $request->Input('status');

        LogSystem("Colaborador exportou dados da tabela tarefas", $request->session()->get('r_code'));

        return Excel::download(new ProjectExport($r_code, $status, $request), 'Tasks'. date('Y-m-d H.s') .'.xlsx');

    }

    public function taskListHistory(Request $request, $id) {

        $request->session()->put('temp_id', $id);

        $task = Task::where('id', $id)->first();

        if ($task) {

            $history = TaskHistory::where('task_id', $id)->orderBy('id', 'DESC')->get();
            $resp = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                ->where('task_id', $id)
                ->select('users.*')
                ->get();
            $me_resp = TaskResponsible::where('r_code', $request->session()->get('r_code'))
                ->where('task_id', $id)
                ->first();

            $sector = Sector::find($task->sector_id);
            $analyze = TaskAnalyze::leftJoin('users','task_analyze.r_code','=','users.r_code')
                ->where('task_id', $id)
                ->select('task_analyze.*', 'users.first_name', 'users.last_name')
                ->get();

            $mng_task = Users::where('r_code', $task->r_code)->first();

            $task_sub = TaskSub::leftJoin('users','task_sub.r_code','=','users.r_code')
                ->leftJoin('sector','users.sector_id','=','sector.id')
                ->select('task_sub.*', 'users.first_name', 'users.last_name', 'sector.name')
                ->where('task_id', $task->id)
                ->get();

            $usersall = Users::where('r_code', '!=', $request->session()->get('r_code'))->get();

            return view('gree_i.task.task_history_list', [
                'usersall' => $usersall,
                'analyze' => $analyze,
                'task_sub' => $task_sub,
                'me_resp' => $me_resp,
                'mng_task' => $mng_task,
                'sector' => $sector,
                'resp' => $resp,
                'task' => $task,
                'history' => $history,
                'id' => $id,
            ]);

        } else {
            return Redirect('/task/view/my');
        }

    }

    public function taskUpdateHistory(Request $request, $id) {

        $attach = $request->file('attach');
        $attach = $request->attach;

        $task = Task::find($id);

        if ($task) {

            if ($task->is_cancelled == 0) {
                $history = new TaskHistory;
                $history->task_id = $id;
                $history->icon = $request->input('iscompleted') == 1 ? "fa-exclamation" : "fa-long-arrow-down";
                $history->color = "warning";
                $history->date = date('Y-m-d');
                $history->description = $request->input('description');

                if ($request->hasFile('attach')) {
                    $extension = $request->attach->extension();
                    if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                        $validator = Validator::make(
                            [
                                'attach' => $attach,
                            ],
                            [
                                'attach' => 'required|max:10000',
                            ]
                        );

                        if ($validator->fails()) {

                            $request->session()->put('error', __('project_i.msg_1'));
                            return Redirect('/task/view/history/'. $id);
                        } else {

                            $img_name = date('YmdHis') .'.'. $extension;
                            $request->attach->storeAs('/', $img_name, 's3');

                            $url = Storage::disk('s3')->url($img_name);

                            $history->attach = $url;
                            $history->is_file = $extension == 'pdf' ? 1 : 0;
                        }

                    } else {

                        $request->session()->put('error', __('project_i.msg_2'));
                        return Redirect('/task/view/history/'. $id);
                    }
                }

                $history->save();

                LogSystem("Colaborador atualizou o histórico da sua tarefa, identificador da tarefa: ". $id, $request->session()->get('r_code'));

                if ($request->input('iscompleted') == 1) {
                    $task->has_analyze = 1;
                    $task->save();

                    LogSystem("Colaborador pediu para finaliar sua tarefa, identificador da tarefa: ". $id, $request->session()->get('r_code'));

                }

                $responsable = TaskResponsible::leftJoin('users','task_responsible.r_code','=','users.r_code')
                    ->where('task_id', $task->id)
                    ->select('users.*')
                    ->first();
                $emails_cc = TaskCopyContact::where('task_id', $task->id)->get();

                $title = $request->input('iscompleted') == 1 ? "PEDIDO DE CONCLUSÃO" : "TAREFA FOI ATUALIZADA";
                $subject = $request->input('iscompleted') == 1 ? 'Tarefa #'. $task->id .' "'. $task->title .'" deseja concluir' : 'Tarefa #'. $task->id  .' "'. $task->title .'" foi atualizada!';
                $body = $request->input('iscompleted') == 1 ? "Responsável pela tarefa, sinalizou que sua tarefa foi concluída, por favor, aceite ou recuse a conclusão da tarefa no painel." : "O responsável da tarefa atualizou informações sobre a tarefa, acesse o painel para ter mais detalhes.";

                if ($responsable) {

                    $pattern = array(
                        'name' => getENameFull($task->r_code),
                        'id' => $task->id,
                        'copys' => $emails_cc,
                        'rcode' => $task->r_code,
                        'responsable' => $responsable,
                        'proj_sector' => sectorName($task->sector_id),
                        'proj_title' => $task->title,
                        'proj_description' => $task->description,
                        'proj_attach' => $task->attach,
                        'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                        'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                        'title' => $title,
                        'description' => $body,
                        'template' => 'task.RequestAccept',
                        'subject' => $subject,
                    );

                    $user = Users::where('r_code', $task->r_code)->first();
                    SendMailCopyJob::dispatch($pattern, $user->email);
                    App::setLocale($user->lang);
                    if ($request->input('iscompleted') == 1) {
                        NotifyUser(__('layout_i.n_proj_004_title'), $user->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_proj_004'), $request->root() .'/project/view/history/'. $task->id);
                    } else {
                        NotifyUser(__('layout_i.n_proj_005_title'), $user->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_proj_005'), $request->root() .'/project/view/history/'. $task->id);
                    }
                    App::setLocale($request->session()->get('lang'));

                } else {

                    $pattern = array(
                        'name' => getENameFull($task->r_code),
                        'id' => $task->id,
                        'copys' => $emails_cc,
                        'rcode' => $task->r_code,
                        'responsable' => $responsable,
                        'proj_sector' => sectorName($task->sector_id),
                        'proj_title' => $task->title,
                        'proj_description' => $task->description,
                        'proj_attach' => $task->attach,
                        'proj_start_date' => date('Y-m-d', strtotime($task->start_date)),
                        'proj_end_date' => date('Y-m-d', strtotime($task->end_date)),
                        'title' => $title,
                        'description' => $body,
                        'template' => 'task.RequestAccept',
                        'subject' => $subject,
                    );

                    // GET EMAIL ADM GERAL
                    SendMailCopyJob::dispatch($pattern, getConfig("email_adm"));
                }

                $request->session()->put('success', __('project_i.msg_9'));
                return Redirect('/task/view/history/'. $id);
            } else {
                $request->session()->put('success', __('project_i.msg_15'));
                return Redirect('/task/view/history/'. $id);
            }

        } else {
            return Redirect('/task/view/my');
        }

    }

    public function tiDevMonitor(Request $request) {

        $todo = TiBacklog::where('mng_approv', 1)->where('dir_approv', 1)->where('is_cancelled', 0)->count();
        $done = TiBacklog::where('mng_approv', 1)->where('dir_approv', 1)->where('is_cancelled', 0)->where('is_completed', 1)->count();

        if ($todo == 0) {
            $pct_done = 100;
        } else {
            $pct_done = ($done * 100) / $todo;
        }
        return view('gree_i.ti.monitor', [
            'pct_done' => round($pct_done, 2),
        ]);
    }

    public function tiDevEdit(Request $request, $id) {

        $userall = Users::all();

        if ($id == 0) {

            $type = 1;
            $subject = "";
            $description = "";
            $attach = "";
            $has_analyze = 0;
            $has_suspended = 0;
        } else {

            $backlog = TiBacklog::find($id);
            if ($backlog) {
                $type = $backlog->type;
                $subject = $backlog->subject;
                $description = $backlog->description;
                $attach = $backlog->attach;
                $has_analyze = $backlog->has_analyze;
                $has_suspended = $backlog->has_suspended;
            } else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return redirect('/news');
            }
        }



        return view('gree_i.ti.ti_developer_edit', [
            'type' => $type,
            'subject' => $subject,
            'description' => $description,
            'attach' => $attach,
            'id' => $id,
            'has_analyze' => $has_analyze,
            'has_suspended' => $has_suspended,
            'userall' => $userall,
        ]);
    }

    public function tiDevAnalyze(Request $request, $id, $type) {

        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 4)->first();
        $backlog = TiBackLog::find($id);
        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {
            if ($backlog) {
                if ($type == 1) {

                    if ($permission->grade >= 9) {
                        if (TiBacklogDirAnalyze::where('ti_backlog_id', $id)->count() > 0) {
                            $request->session()->put('error', "Essa solicitação já foi aprovada pelo diretor.");
                            return redirect('/ti/developer/approv');
                        }
                        $backlog->dir_approv = 1;
                        $backlog->has_analyze = 0;

                        // Send request for jira and register issue
                        $backlog->jira_key = registerTaskinJira($backlog->subject, $backlog->description, $backlog->type);

                        $analyze = new TiBacklogDirAnalyze;
                        $analyze->ti_backlog_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_approv = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $user = Users::where('r_code', $backlog->request_r_code)->first();

                        if ($user) {

                            $pattern = array(
                                'title' => 'GDB BACKLOG: APROVADO',
                                'description' => nl2br($backlog->subject .' <p>'. $request->input('description')) .'</p>',
                                'template' => 'misc.Default',
                                'subject' => 'GDB BACKLOG: APROVADO',
                            );

                            NotifyUser('GDB BACKLOG: Tarefa aprovada!', $user->r_code, 'fa-check', 'text-success', 'Sua tarefa foi aprovada, em breve você será atualizado. Clique aqui para ver mais detalhes.', $request->root() .'/ti/developer/track/'. $backlog->id);
                            SendMailJob::dispatch($pattern, $user->email);
                        }

                    } else {
                        if (TiBacklogMngAnalyze::where('ti_backlog_id', $id)->count() > 0) {
                            $request->session()->put('error', "Essa solicitação já foi aprovada pelo gestor.");
                            return redirect('/ti/developer/approv');
                        }
                        $backlog->mng_approv = 1;

                        $analyze = new TiBacklogMngAnalyze;
                        $analyze->ti_backlog_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_approv = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 9)
                            ->where('user_on_permissions.perm_id', 4)
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'title' => 'GDB BACKLOG: '. $backlog->subject,
                                'description' => nl2br($request->input('description')),
                                'template' => 'misc.Default',
                                'subject' => 'GDB BACKLOG: '. $backlog->subject,
                            );

                            NotifyUser('GDB BACKLOG: Pedido de aprovação', $key->r_code, 'fa-exclamation', 'text-info', 'Foi criado uma nova tarefa para TI Desenvolvimento, acesse essa página para aprovar ou reprovar a solicitação: ', $request->root() .'/ti/developer/approv/');
                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    }

                    $backlog->has_suspended = 0;
                    $backlog->save();
                    LogSystem("Colaborador aprovou o tarefa de TI Desenvolvimento identificado por ". $backlog->id, $request->session()->get('r_code'));
                } else if ($type == 2) {
                    $type_name = "";
                    if ($permission->grade == 9) {
                        $backlog->dir_repprov = 1;

                        $analyze = new TiBacklogDirAnalyze;
                        $analyze->ti_backlog_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_reprov = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $type_name = "DIRETOR";

                    } else {
                        $backlog->mng_repprov = 1;

                        $analyze = new TiBacklogMngAnalyze;
                        $analyze->ti_backlog_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_reprov = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $type_name = "GESTOR";
                    }
                    $backlog->has_suspended = 0;
                    $backlog->has_analyze = 0;
                    $backlog->save();

                    $user = Users::where('r_code', $backlog->request_r_code)->first();

                    if ($user) {

                        $pattern = array(
                            'title' => 'GDB BACKLOG: '. $type_name .' REPROVOU',
                            'description' => nl2br($backlog->subject .' <p>'.$request->input('description') .'</p>'),
                            'template' => 'misc.Default',
                            'subject' => 'GDB BACKLOG: '. $type_name .' REPROVOU',
                        );

                        NotifyUser('GDB BACKLOG: Tarefa reprovada!', $user->r_code, 'fa-times', 'text-danger', 'Sua tarefa foi reprovada, veja mais informações sobre o motivo da reprovação em histórico de análises. ', $request->root() .'/ti/developer/all');
                        SendMailJob::dispatch($pattern, $user->email);
                    }

                    LogSystem("Colaborador reprovou a tarefa TI Desenvolvimento identificado por ". $backlog->id, $request->session()->get('r_code'));
                }


                $txt_analyze = $type == 1 ? "aprovou" : "reprovou";
                $request->session()->put('success', "Você ". $txt_analyze ." o empréstimo com sucesso!");
                return redirect('/ti/developer/approv');
            } else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return redirect('/news');
            }
        } else {
            if ($user->retry > 0) {
                $user->retry = $user->retry - 1;

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Reembolso) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                    return redirect('/logout');
                } else {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->save();

                    $request->session()->put('error', "You missed your secret password, only ". $user->retry ." attempt(s) left.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Reembolso). Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return redirect('/ti/developer/edit/'. $id);
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar (Reembolso) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                return redirect('/ti/developer/edit/'. $id);
            }
        }

    }

    public function tiDevUpdate(Request $request) {

        if ($request->id == 0) {

            $backlog = new TiBacklog;
        } else {
            $backlog = TiBacklog::find($request->id);
            if(!$backlog) {
                $request->session()->put('error', 'Projeto não foi encontrado!');
                return redirect()->back();
            }
        }

        $backlog->type = $request->type;
        $backlog->subject = $request->subject;
        if ($request->id == 0) {
            $backlog->request_r_code = $request->session()->get('r_code');
        }
        $backlog->description = $request->description;
        if ($request->hasFile('attach')) {
            $response = $this->uploadS3(1, $request->attach, $request);
            if ($response['success']) {
                $backlog->attach = $response['url'];
            } else {
                return redirect()->back();
            }
        }
        if ($request->id == 0 and $request->type == 1) {
            $backlog->has_analyze = 1;
        }
        if ($request->type != 1) {
            $backlog->mng_approv = 1;
            $backlog->dir_approv = 1;
        }
        $backlog->save();

        if ($request->type == 1) {
            if ($request->id == 0) {

                $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                    ->select('users.*')
                    ->where('user_on_permissions.can_approv', 1)
                    ->where('user_on_permissions.grade', 8)
                    ->where('user_on_permissions.perm_id', 4)
                    ->get();

                foreach ($immediate as $key) {

                    $pattern = array(
                        'title' => 'GDB BACKLOG: '. $backlog->subject,
                        'description' => nl2br($request->input('description')),
                        'template' => 'misc.Default',
                        'subject' => 'GDB BACKLOG: '. $backlog->subject,
                    );

                    NotifyUser('GDB BACKLOG: Pedido de aprovação', $key->r_code, 'fa-exclamation', 'text-info', 'Foi criado uma nova tarefa para TI Desenvolvimento, acesse essa página para aprovar ou reprovar a solicitação: ', $request->root() .'/ti/developer/approv/');
                    SendMailJob::dispatch($pattern, $key->email);
                }
            }
        } else {



            $mng = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                ->select('users.*')
                ->where('user_on_permissions.can_approv', 1)
                ->where('user_on_permissions.grade', 8)
                ->where('user_on_permissions.perm_id', 4)
                ->first();
            if ($mng) {
                $analyze = new TiBacklogMngAnalyze;
                $analyze->ti_backlog_id = $backlog->id;
                $analyze->r_code = $mng->r_code;
                $analyze->is_approv = 1;
                $analyze->description = 'Aprovação automático para correção do erro.';
                $analyze->save();
            }

            $dir = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                ->select('users.*')
                ->where('user_on_permissions.can_approv', 1)
                ->where('user_on_permissions.grade', 9)
                ->where('user_on_permissions.perm_id', 4)
                ->first();
            if ($dir) {
                $analyze = new TiBacklogDirAnalyze;
                $analyze->ti_backlog_id = $backlog->id;
                $analyze->r_code = $dir->r_code;
                $analyze->is_approv = 1;
                $analyze->description = 'Aprovação automático para correção do erro.';
                $analyze->save();
            }

            registerTaskinJira($backlog->subject, $backlog->description, $backlog->type);

        }

        LogSystem("Colaborador criou novo tarefa para o setor de TI - Desenvolvimento: ID #". $backlog->id, $request->session()->get('r_code'));
        $request->session()->put('success', 'Tarefa criado com sucesso!');
        return redirect('/ti/developer/all');
    }

    public function tiDevTrack(Request $request, $id) {

        $backlog = TiBacklog::find($id);

        if ($backlog) {

            $history = TiBacklogHistory::where('ti_backlog_id', $id)->get();
            $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
                ->where('user_on_permissions.perm_id', 4)
                ->select('users.*')
                ->get();

            return view('gree_i.ti.ti_developer_track', [
                'id' => $id,
                'history' => $history,
                'backlog' => $backlog,
                'users' => $users,
            ]);

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function tiDevSendHistory(Request $request) {

        $id = $request->id;

        $backlog = TiBacklog::find($id);

        if ($backlog) {

            $history = new TiBacklogHistory;
            $history->ti_backlog_id = $id;
            if ($request->input('status') == 1) {
                $history->title = "Finalização do projeto";
                $history->color = "success";
            } elseif ($request->input('status') == 2) {
                $history->title = "Cancelamento do projeto";
                $history->color = "danger";
            } else {
                $history->title = "Atualização";
                $history->color = "warning";
            }

            $history->date = date('Y-m-d');
            $history->description = $request->input('description');
            if ($request->hasFile('attach')) {
                $response = $this->uploadS3(1, $request->attach, $request);
                if ($response['success']) {
                    $history->attach = $response['url'];
                } else {
                    return redirect()->back();
                }
            }

            $history->save();

            if ($backlog->jira_key) {
                // register comment in jira
                registerCommentJira($backlog->jira_key, $request->input('description'));
            }

            LogSystem("Colaborador atualizou o histórico da tarefa da TI desenvolvimento, identificador da tarefa da TI desenvolvimento: ". $id, $request->session()->get('r_code'));

            if ($request->input('status') == 1) {
                $backlog->is_completed = 1;
                $backlog->is_cancelled = 0;

                LogSystem("Colaborador finalizou a tarefa, identificador da TI desenvolvimento: ". $id, $request->session()->get('r_code'));

            } else if ($request->input('status') == 2) {
                $backlog->is_cancelled = 1;
                $backlog->is_completed = 0;

                LogSystem("Colaborador cancelou a tarefa, identificador da TI desenvolvimento: ". $id, $request->session()->get('r_code'));

            } else {
                $backlog->is_cancelled = 0;
                $backlog->is_completed = 0;
            }

            $backlog->ti_user_r_code = $request->responsible;
            $old_date = str_replace("/", "-", $request->date_end);
            $date_end = date('Y-m-d', strtotime($old_date));
            $backlog->date_end = $date_end;
            $backlog->save();

            $user = Users::where('r_code', $backlog->request_r_code)->first();

            if ($user) {

                $pattern = array(
                    'title' => 'GDB BACKLOG: '. $backlog->subject,
                    'description' => nl2br($request->input('description')),
                    'template' => 'misc.Default',
                    'subject' => 'GDB BACKLOG: '. $backlog->subject,
                );

                NotifyUser('GDB BACKLOG: Nova atualização', $user->r_code, 'fa-exclamation', 'text-info', 'Foi publicado uma nova atividade em sua tarefa, por favor, clique aqui para ver mais detalhes.', $request->root() .'/ti/developer/track/'. $backlog->id);
                SendMailJob::dispatch($pattern, $user->email);
            }

            $request->session()->put('success', 'Publicação da atividade foi realizada com sucesso!');
            return redirect()->back();

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function tiDevAll(Request $request) {

        $perm = UserOnPermissions::where('user_on_permissions.can_approv', 1)
            ->where('user_on_permissions.perm_id', 4)
            ->where('user_on_permissions.user_r_code', $request->session()->get('r_code'))
            ->orWhere(function ($query) use ($request) {
                $query->where('user_on_permissions.grade', 99)
                    ->where('user_on_permissions.perm_id', 4)
                    ->where('user_on_permissions.user_r_code', $request->session()->get('r_code'));
            })
            ->first();
        if ($perm) {
            $backlog = TiBacklog::leftjoin('users', 'ti_backlog.request_r_code', '=', 'users.r_code')
                ->select('ti_backlog.*', 'users.r_code', 'users.sector_id')
                ->orderBy('ti_backlog.id', 'DESC')
                ->paginate(10);
        } else {
            $backlog = TiBacklog::leftjoin('users', 'ti_backlog.request_r_code', '=', 'users.r_code')
                ->select('ti_backlog.*', 'users.r_code', 'users.sector_id')
                ->where('ti_backlog.request_r_code', $request->session()->get('r_code'))
                ->orderBy('ti_backlog.id', 'DESC')
                ->paginate(10);
        }


        return view('gree_i.ti.ti_developer_all', [
            'backlog' => $backlog,
        ]);
    }

    public function tiDevApprov(Request $request) {

        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 4)->first();

        if ($permission->grade == 9) {
            $backlog = TiBacklog::leftjoin('users', 'ti_backlog.request_r_code', '=', 'users.r_code')
                ->select('ti_backlog.*', 'users.r_code', 'users.sector_id')
                ->where('ti_backlog.mng_approv', 1)
                ->where('ti_backlog.mng_repprov', 0)
                ->where('ti_backlog.dir_approv', 0)
                ->where('ti_backlog.dir_repprov', 0)
                ->where('ti_backlog.has_analyze', 1)
                ->orderBy('ti_backlog.id', 'DESC')
                ->paginate(10);

        } else {
            $backlog = TiBacklog::leftjoin('users', 'ti_backlog.request_r_code', '=', 'users.r_code')
                ->select('ti_backlog.*', 'users.r_code', 'users.sector_id')
                ->where('ti_backlog.mng_approv', 0)
                ->where('ti_backlog.mng_repprov', 0)
                ->where('ti_backlog.dir_approv', 0)
                ->where('ti_backlog.dir_repprov', 0)
                ->where('ti_backlog.has_analyze', 1)
                ->orderBy('ti_backlog.id', 'DESC')
                ->paginate(10);

        }


        return view('gree_i.ti.ti_developer_approv', [
            'backlog' => $backlog,
        ]);
    }

    public function tiEdit(Request $request, $id) {

        $request->session()->put('temp_id', $id);
        if ($id == 0) {

            $gree = "";
            $necessity = "";
            $system = "";
            $description = "";
            $attach = "";
        } else {

            $ticket = ChangeLogs::find($id);

            $gree = $ticket->teste;
            $necessity = "";
            $system = "";
            $description = "";
            $attach = "";
        }

        $versions = ChangeLogs::paginate(10);

        return view('gree_i.ti.ti_ticket_new', [
            'id' => $id,
            'versions' => $versions,
        ]);

    }

    public function tiEdit_do(Request $request) {

        $id = $request->session()->get('temp_id');
        $version = Settings::where('command', 'version_number')->first();
        $version_name = Settings::where('command', 'version_name')->first();
        if ($id == 0) {
            $changelog = new ChangeLogs;
            $version->value = $version->value + 1;
        } else {
            $changelog = ChangeLogs::find($id);
        }

        $changelog->version = $version->value + 1;
        $changelog->version_name = $request->input('version_name');
        $changelog->text_pt = $request->input('text_pt');
        $changelog->text_en = $request->input('text_en');
        $changelog->save();

        $version_name->value = $request->input('version_name');
        $version_name->save();

        $version->save();

        return redirect()->back();

    }

    public function tiVersionEdit(Request $request, $id) {

        $request->session()->put('temp_id', $id);
        if ($id == 0) {

            $vname = "";
            $txt_pt = "";
            $txt_en = "";
        } else {

            $changelog = ChangeLogs::find($id);

            $vname = $changelog->version_name;
            $txt_pt = $changelog->text_pt;
            $txt_en = $changelog->text_en;
        }

        $versions = ChangeLogs::paginate(10);

        return view('gree_i.ti.ti_edit_version', [
            'id' => $id,
            'versions' => $versions,
            'vname' => $vname,
            'txt_pt' => $txt_pt,
            'txt_en' => $txt_en,
        ]);

    }

    public function tiVersionEdit_do(Request $request) {

        $id = $request->session()->get('temp_id');
        $version = Settings::where('command', 'version_number')->first();
        $version_name = Settings::where('command', 'version_name')->first();
        if ($id == 0) {
            $changelog = new ChangeLogs;
            $version->value = $version->value + 1;
        } else {
            $changelog = ChangeLogs::find($id);
        }

        $changelog->version = $version->value + 1;
        $changelog->version_name = $request->input('version_name');
        $changelog->text_pt = $request->input('text_pt');
        $changelog->text_en = $request->input('text_en');
        $changelog->save();

        $version_name->value = $request->input('version_name');
        $version_name->save();

        $version->save();

        return redirect()->back();

    }
	
	/**
     * Para monitoramento das assistencias tecnicas autorizadas
     */   
    public function sacAuthorizedMonitor(Request $request) {
        $quantiryToExibirRates = $request->quantity;
        $y = $request->year;
        
        if(is_null($quantiryToExibirRates)){
            $quantiryToExibirRates = 5;
        }
        if(is_null($y)){
            $y= date('Y');            
        }

        //Retorna todos as assistencias tecnicas autorizadas ativas com todas as OS realizadas durante o ano dado.                  
        $allOsOnTheYear = DB::table('sac_authorized')
            ->join('sac_os_protocol', function ($join) use($y) {
            $join->on('sac_authorized.id', '=', 'sac_os_protocol.authorized_id')
                    ->where('sac_authorized.is_active','=','1')                    
                    //->whereYear('sac_os_protocol.created_at','>', date("$y-00-00 00:00:00"));
                    ->whereYear('sac_os_protocol.updated_at',$y);
        })->get();
        
        //Retorna todas as assistencias mais bem avaliadas
        $classificationAuthorized = DB::table('sac_authorized')
            ->whereExists(function($q) use($y){
            $q->select(DB::raw(1))
            ->from('sac_os_protocol')
            ->whereRaw('sac_authorized.id = sac_os_protocol.authorized_id')            
            //->where('sac_os_protocol.created_at','>', date("$y-00-00 00:00:00"));
            ->whereYear('sac_os_protocol.created_at',$y);
            })
			->where('id', '!=', 1864)
			->orderByRaw('rate_count DESC, rate DESC')
            ->take($quantiryToExibirRates)->get(); 
        
        //Retorna todos as assistencias tecnicas autorizadas ativas com todas as OS que foram pagas.        
        $servAutIsAtivoIsPaid = $allOsOnTheYear->reject(function ($allServIsAtivo){            
            return $allServIsAtivo->is_paid <> 1;
        })->all();

        //Retorna o total das assistencias tecnicas autorizadas ativas.                  
        $total = App\Model\SacAuthorized::all();        
		
		//Retorna o total das assistencias tecnicas autorizadas base ativas.                  
        $total_base = App\Model\SacAuthorized::where('is_active', 1)->whereHas('sac_os_protocol', function($q) {
			$q->where('is_paid', 1);
		})->get();   
		
		//Retorna o total das assistencias tecnicas autorizadas base ativas.                  
        $total_base_last_3_month = App\Model\SacAuthorized::where('is_active', 1)->whereHas('sac_os_protocol', function($q) {
			$q->where('is_paid', 1)
				->whereDate('updated_at', '>=', date('Y-m-d', strtotime('- 3 month')));
		})->get();

        //Retorna o total das novas assistencias tecnicas autorizadas cadastradas no ano atual.
        $sacAuthorized =  DB::select
            ('select created_at 
                from sac_authorized	
                where created_at > ? and is_active = ?', ["$y-00-00 00:00:00", 1] );        
        
        $arrAllNewAuthorizedIsAtctive = array();
        $arrMes = array();
        $count = 0;        
        for ($i = 0; $i < 12; $i++) {                      
            $m=$i+1;
            $date2 = date("$y-0$m-31");                           
            $date3 = date("$y-0$m-00");                      

            foreach($sacAuthorized as $index => $a){          
                $date1 = $a->created_at;       
                 
                if ($date2>$date1 && $date3<$date1){                                                        
                    $arrMes[$index]=$a->created_at;                                        
                }                   
            }                        
            if(empty($arrMes)){
                $arrAllNewAuthorizedIsAtctive[$i] =  0;                
                
            }else{
                $arrAllNewAuthorizedIsAtctive[$i] = count(array_count_values($arrMes));
                }
                $arrMes = array();
        }  
        

        //Para popular o arrey dos bem avaliados.
        $arrBemAvaliadas = array();               
        foreach ($classificationAuthorized as $index => $p) {
            $arrBemAvaliadas[$index][0] = $p->name;
            $arrBemAvaliadas[$index][1] = $p->rate;
            $arrBemAvaliadas[$index][2] = $p->rate_count;
        }              

        //total de assistencias tecnicas autorizadas que realizaram O.S. no ano.
        $allAuthorizedWithServicesOnTheYear = $allOsOnTheYear->countBy('authorized_id');        
      
        //Empresas autorizadas que fizerem servicos por mes.
        $arrTotalAuthorizedByMonth = array();
        $arrMes = array();
        $count = 0;        
        for ($i = 0; $i < 12; $i++) {                      
            $m=$i+1;
            $date2 = date("$y-0$m-31");                           
            $date3 = date("$y-0$m-00");                      

            foreach($allOsOnTheYear as $index => $a){          
                $date1 = $a->created_at;                
                if ($date2>$date1 && $date3<$date1){                                                        
                    $arrMes[$index]=$a->authorized_id;                                        
                }                   
            }                        
            if(empty($arrMes)){
                $arrTotalAuthorizedByMonth[$i] =  0;                
                
            }else{
                $arrTotalAuthorizedByMonth[$i] = count(array_count_values($arrMes));
                }
                $arrMes = array();
        }  
        
        //Filtra as O.S por ano dado.
        $arrMesTrabalhado = array();
        $count = 0;                
        $date2 = date("$y-m-31");           
        $date3 = date("$y-m-00");           
        foreach($allOsOnTheYear as $index => $a){                        
            $date1 = $a->created_at;                         
            if ($date2>$date1 && $date3<$date1){                
                $count++;
                $arrMesTrabalhado[$index]=$a->created_at;
            }     
        }      
		
        $totalOsDoAno = $allOsOnTheYear->count(); 

        return view('gree_i.sac.sacMonitorAuthorized', [
            'total' => $total->count(),
			'total_active' => $total->where('is_active', 1)->count(),
			'total_base' => $total_base->count(),
			'total_base_last_3_month' => $total_base_last_3_month->count(),
            'totalOsDoAno' => $totalOsDoAno,
            'arrBemAvaliadas' => $arrBemAvaliadas,
            'arrMesTrabalhado' => array_reverse($arrMesTrabalhado),           
            'arrTotalAuthorizedByMonth' => $arrTotalAuthorizedByMonth, 
            'arrAllNewAuthorizedIsAtctive' => $arrAllNewAuthorizedIsAtctive,
            'year' => $y,
        ]);
    }

    public function sacMonitor(Request $request) {

        if (!empty($request->input('type_line'))) {
            $request->session()->put('sacf_type_line', $request->input('type_line'));
        } else {
            $request->session()->forget('sacf_type_line');
            if($request->session()->get('filter_line') == 2) {
                $request->session()->put('sacf_type_line', 2);
            }
        }
		
		$block_1 = SacProtocol::where('r_code', NULL)
			->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('is_entry_manual', 0)
			->whereDoesntHave('sacosprotocol')->count();
		
		$block_2 = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_refund_pending', 0)
			->where('is_entry_manual', 0)
			->where('r_code', '!=', NULL)
            ->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('authorized_id', NULL)
			->where('in_wait', 1)
			->where('in_wait_documents', 0)
			->whereDoesntHave('sacosprotocol')->count();
		
		$block_3 = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_refund_pending', 0)
			->where('is_entry_manual', 0)
			->where('r_code', '!=', NULL)
			->where('authorized_id', NULL)
            ->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('in_wait_documents', 1)
			->whereDoesntHave('sacosprotocol')->count();
		
		$block_4 = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_refund_pending', 0)
			->where('is_entry_manual', 0)
			->where('r_code', '!=', NULL)
			->where('authorized_id', NULL)
            ->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('in_wait_documents', 0)
			->where('is_warranty', 1)
			->whereDoesntHave('sacosprotocol')->count();
		
		$block_5 = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_refund_pending', 0)
			->where('is_entry_manual', 0)
			->where('r_code', '!=', NULL)
			->where('authorized_id', '!=', NULL)
            ->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('in_wait_documents', 0)
			->where('is_warranty', 1)
			->whereRaw("id IN(SELECT sac_protocol_id FROM sac_os_protocol
                      WHERE DATE(visit_date) >= CURDATE()
                      AND sac_os_protocol.is_cancelled = 0
                      AND sac_os_protocol.is_paid = 0
					  AND sac_os_protocol.has_pending_payment = 0
					  AND sac_os_protocol.visit_date != '0000-00-00 00:00:00'
                      GROUP BY sac_protocol_id)")->count();
		
		$block_6 = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_refund_pending', 0)
			->where('is_entry_manual', 0)
			->where('r_code', '!=', NULL)
			->where('authorized_id', '!=', NULL)
            ->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('in_wait_documents', 0)
			->where('is_warranty', 1)
			->whereRaw("id IN(SELECT sac_protocol_id FROM sac_os_protocol
                      WHERE NOT EXISTS (SELECT 1
                                    FROM sac_part_protocol
                                    WHERE sac_part_protocol.sac_protocol_id = sac_protocol.id 
									AND (is_approv = 1 OR is_approv = 0 AND is_repprov = 0))
					  AND DATE(visit_date) < CURDATE()
                      AND sac_os_protocol.is_cancelled = 0
                      AND sac_os_protocol.is_paid = 0
					  AND sac_os_protocol.has_pending_payment = 0
					  AND sac_os_protocol.visit_date != '0000-00-00 00:00:00'
                      GROUP BY sac_protocol_id)")->count();
		
		$block_7 = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_entry_manual', 0)
            ->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('is_refund_pending', 1)->count();
		
		$block_8 = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_refund_pending', 0)
			->where('is_entry_manual', 0)
            ->where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('origin', 3)->count();
		
		$block_9 = SacProtocol::where('is_completed', 0)
							->where('is_refund', 0)
							->where('is_refund_pending', 0)
							->where('is_entry_manual', 0)
							->where('is_cancelled', 0)
							->where('is_completed', 0)
							->where('type', 10)->count();

        $protocol_total = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
			->where('is_entry_manual', 0)
            ->where('is_cancelled', 0);

        $without_response = SacProtocol::where('is_completed', 0)
            ->where('is_refund', 0)
            ->where('is_cancelled', 0)
			->where('is_entry_manual', 0)
            ->OnlyMsgNotReadFilter();

        $visited_with_part = SacProtocol::whereRaw('id IN(SELECT sac_protocol_id FROM sac_os_protocol
                      WHERE EXISTS (SELECT 1
                                    FROM sac_part_protocol
                                    WHERE sac_part_protocol.sac_protocol_id = sac_protocol.id 
									AND (is_approv = 1 OR is_approv = 0 AND is_repprov = 0))
                      AND DATE(visit_date) < CURDATE()
                      AND sac_os_protocol.is_cancelled = 0
                      AND sac_os_protocol.is_paid = 0
                      GROUP BY sac_protocol_id)')
            ->where('is_completed', 0)
            ->where('is_refund', 0)
            ->where('is_cancelled', 0)
            ->where('pending_completed', 0)
			->where('is_entry_manual', 0)
            ->where('has_aswer', 1);

        $pending_completed = SacProtocol::where('pending_completed', 1);

        $total_protocol = collect([]);

        $protocol_w_u = collect();

        $protocol_w_u_tab = DB::table('sac_protocol')
            ->leftjoin('users', 'sac_protocol.r_code', '=', 'users.r_code')
            ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
            ->where('sac_protocol.is_completed', 0)
            ->where('sac_protocol.is_cancelled', 0)
			->where('is_entry_manual', 0)
            ->where('sac_protocol.r_code', '!=', null)
            ->groupBy('sac_protocol.r_code')
            ->orderBy('sac_protocol.r_code', 'DESC');

        $type_list = array(
            ['type' => 1,'name' => 'Reclamação','total' => 0],
            ['type' => 2,'name' => 'Em garantia','total' => 0],
            ['type' => 3,'name' => 'Dúvida técnica','total' => 0],
            ['type' => 4,'name' => 'Revenda','total' => 0],
            ['type' => 5,'name' => 'Credenciamento','total' => 0],
            ['type' => 6,'name' => 'Outros','total' => 0],
            ['type' => 7,'name' => 'Fora de garantia','total' => 0],
            ['type' => 8,'name' => 'Atend. negado','total' => 0],
            ['type' => 9,'name' => 'Autoriz. instalação', 'total' => 0],
            ['type' => 10,'name' => 'Atend. Tercerizado', 'total' => 0],
			['type' => 11,'name' => 'Atend. em cortesia', 'total' => 0]
        );

        $type_list_tab = DB::table('sac_protocol')
            ->select(DB::raw('count(sac_protocol.id) as total, type'))
			->where('is_entry_manual', 0)
            ->groupBy('sac_protocol.type')
            ->orderBy('sac_protocol.type', 'DESC');

        $origin_list = collect();

        $origin_list_tab = DB::table('sac_protocol')
            ->select(DB::raw('count(sac_protocol.id) as total, origin'))
            ->groupBy('sac_protocol.origin')
            ->orderBy('sac_protocol.origin', 'DESC');

        if (!empty($request->session()->get('sacf_type_line'))) {

            if($request->session()->get('sacf_type_line') == 1) {
                $type_desc = 'residential';
            }
            else {
                $type_desc = 'commercial';
            }

            $protocol_total->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1);

            $without_response->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1);

            $visited_with_part->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1);

            $pending_completed->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1);

            for ($i=1; $i <= 12; $i++) {
                $total = SacProtocol::leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                    ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                    ->where('product_air.'.$type_desc.'', 1)
                    ->whereMonth('sac_protocol.created_at', $i)
                    ->whereYear('sac_protocol.created_at', date('Y'))
                    ->groupBy('sac_protocol.id')
                    ->count();

                $total_protocol->push($total);
            }

            $protocol_w_u_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1)
                ->chunk(100, function($users) use ($protocol_w_u) {
                    foreach ($users as $user)
                    {
                        $protocol_w_u->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });

            $type_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1);

            foreach ($type_list_tab->get() as $key)
            {
                $index = $key->type - 1;
                $type_list[$index]['total'] = $key->total;
            }

            $origin_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1)
                ->chunk(100, function($origin) use ($origin_list) {
                    foreach ($origin as $key)
                    {
                        $name = "";
                        if ($key->origin == 1) {
                            $name = "Telefone";
                        } else if ($key->origin == 2) {
                            $name = "Email";
                        } else if ($key->origin == 3) {
                            $name = "Reclame aqui";
                        } else if ($key->origin == 4) {
                            $name = "Midias sociais";
                        } else if ($key->origin == 5) {
                            $name = "Site";
                        } else if ($key->origin == 6) {
                            $name = "Consumidor GOV";
                        } else if ($key->origin == 7) {
                            $name = "Procon";
                        }
                        $origin_list->push([
                            'name' => $name,
                            'total' => $key->total
                        ]);
                    }
                });
        }
        else {

            for ($i=1; $i <= 12; $i++) {
                $total = SacProtocol::whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y'))
                    ->count();

                $total_protocol->push($total);
            }

            $protocol_w_u_tab->chunk(100, function($users) use ($protocol_w_u) {
                foreach ($users as $user)
                {
                    $protocol_w_u->push([
                        'name' => $user->first_name .' #'. $user->r_code,
                        'total' => $user->total
                    ]);
                }
            });

            foreach ($type_list_tab->get() as $key)
            {
                $index = $key->type - 1;
                $type_list[$index]['total'] = $key->total;
            }

            $origin_list_tab->chunk(100, function($origin) use ($origin_list) {
                foreach ($origin as $key)
                {
                    $name = "";
                    if ($key->origin == 1) {
                        $name = "Telefone";
                    } else if ($key->origin == 2) {
                        $name = "Email";
                    } else if ($key->origin == 3) {
                        $name = "Reclame aqui";
                    } else if ($key->origin == 4) {
                        $name = "Midias sociais";
                    } else if ($key->origin == 5) {
                        $name = "Site";
                    } else if ($key->origin == 6) {
                        $name = "Consumidor GOV";
                    } else if ($key->origin == 7) {
                        $name = "Procon";
                    }
                    $origin_list->push([
                        'name' => $name,
                        'total' => $key->total
                    ]);
                }
            });

        }
		
		$sacProtocol = new SacProtocol;
        $protocol = $sacProtocol->protocolRelOrderfilter();
		
		$type_line = 0;
		if ($request->session()->get('filter_line') == 2) {
            $protocol->sacModelProtocolFilter(2);
            $type_line = 2;
        }

        return view('gree_i.sac.monitor', [
            'protocol_total' => $protocol_total->count(),
			'total_5' =>  $sacProtocol->sacProtocolLeftFilter(5, $type_line)->count(),
            'total_15' => $sacProtocol->sacProtocolLeftFilter(15, $type_line)->count(),
            'total_30' => $sacProtocol->sacProtocolLeftFilter(30, $type_line)->count(),
            'without_response' => $without_response->count(),
            'visited_with_part' => $visited_with_part->count(),
            'pending_completed' => $pending_completed->count(),
            'total_protocol' => $total_protocol,
            'protocol_w_u' => $protocol_w_u,
            'type_list' => array_reverse($type_list),
            'origin_list' => $origin_list,
			'block_1' => $block_1,
			'block_2' => $block_2,
			'block_3' => $block_3,
			'block_4' => $block_4,
			'block_5' => $block_5,
			'block_6' => $block_6,
			'block_7' => $block_7,
			'block_8' => $block_8,
			'block_9' => $block_9,
            'year_range' => range(2020, date('Y')),
        ]);
    }

    public function sacMonitor_ajax(Request $request) {

        $total_protocol = collect([]);

        $protocol_w_u = collect();

        $protocol_w_u_tab = DB::table('sac_protocol')
            ->leftjoin('users', 'sac_protocol.r_code', '=', 'users.r_code')
            ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
            ->where('sac_protocol.is_completed', 0)
            ->where('sac_protocol.is_cancelled', 0)
            ->where('sac_protocol.r_code', '!=', null)
			->where('is_entry_manual', 0)
            ->groupBy('sac_protocol.r_code')
            ->orderBy('sac_protocol.r_code', 'DESC');

        $type_list = array(
            ['type' => 1,'name' => 'Reclamação','total' => 0],
            ['type' => 2,'name' => 'Em garantia','total' => 0],
            ['type' => 3,'name' => 'Dúvida técnica','total' => 0],
            ['type' => 4,'name' => 'Revenda','total' => 0],
            ['type' => 5,'name' => 'Credenciamento','total' => 0],
            ['type' => 6,'name' => 'Outros','total' => 0],
            ['type' => 7,'name' => 'Fora de garantia','total' => 0],
            ['type' => 8,'name' => 'Atend. negado','total' => 0],
            ['type' => 9,'name' => 'Autoriz. instalação', 'total' => 0],
            ['type' => 10,'name' => 'Atend. Tercerizado', 'total' => 0],
			['type' => 11,'name' => 'Atend. em cortesia', 'total' => 0]
        );

        $type_list_tab = DB::table('sac_protocol')
            ->select(DB::raw('count(sac_protocol.id) as total, type'))
			->where('is_entry_manual', 0)
            ->groupBy('sac_protocol.type')
            ->orderBy('sac_protocol.origin', 'DESC');

        $origin_list = collect();

        $origin_list_tab = DB::table('sac_protocol')
            ->select(DB::raw('count(sac_protocol.id) as total, origin'))
			->where('is_entry_manual', 0)
            ->groupBy('sac_protocol.origin')
            ->orderBy('sac_protocol.origin', 'DESC');

        $field_type = $request->session()->get('filter_line') == 2 ? 2 : $request->input('type_line');
        if (!empty($field_type)) {

            if($field_type == 1) {
                $type_desc = 'residential';
            }
            else {
                $type_desc = 'commercial';
            }

            for ($i=1; $i <= 12; $i++) {
                $total = SacProtocol::leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                    ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                    ->where('product_air.'.$type_desc.'', 1)
                    ->whereMonth('sac_protocol.created_at', $i)
                    ->whereYear('sac_protocol.created_at', date('Y'))
                    ->count();

                $total_protocol->push($total);
            }

            $protocol_w_u_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1)
                ->chunk(100, function($users) use ($protocol_w_u) {
                    foreach ($users as $user)
                    {
                        $protocol_w_u->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });

            $type_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1);

            foreach ($type_list_tab->get() as $key)
            {
                $index = $key->type - 1;
                $type_list[$index]['total'] = $key->total;
            }

            $origin_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                ->where('product_air.'.$type_desc.'', 1)
                ->chunk(100, function($origin) use ($origin_list) {
                    foreach ($origin as $key)
                    {
                        $name = "";
                        if ($key->origin == 1) {
                            $name = "Telefone";
                        } else if ($key->origin == 2) {
                            $name = "Email";
                        } else if ($key->origin == 3) {
                            $name = "Reclame aqui";
                        } else if ($key->origin == 4) {
                            $name = "Midias sociais";
                        } else if ($key->origin == 5) {
                            $name = "Site";
                        } else if ($key->origin == 6) {
                            $name = "Consumidor GOV";
                        } else if ($key->origin == 7) {
                            $name = "Procon";
                        }
                        $origin_list->push([
                            'name' => $name,
                            'total' => $key->total
                        ]);
                    }
                });
        }
        else {

            for ($i=1; $i <= 12; $i++) {
                $total = SacProtocol::whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y'))
                    ->count();

                $total_protocol->push($total);
            }

            $protocol_w_u_tab->chunk(100, function($users) use ($protocol_w_u) {
                foreach ($users as $user)
                {
                    $protocol_w_u->push([
                        'name' => $user->first_name .' #'. $user->r_code,
                        'total' => $user->total
                    ]);
                }
            });

            foreach ($type_list_tab->get() as $key)
            {
                $index = $key->type - 1;
                $type_list[$index]['total'] = $key->total;
            }

            $origin_list_tab->chunk(100, function($origin) use ($origin_list) {
                foreach ($origin as $key)
                {
                    $name = "";
                    if ($key->origin == 1) {
                        $name = "Telefone";
                    } else if ($key->origin == 2) {
                        $name = "Email";
                    } else if ($key->origin == 3) {
                        $name = "Reclame aqui";
                    } else if ($key->origin == 4) {
                        $name = "Midias sociais";
                    } else if ($key->origin == 5) {
                        $name = "Site";
                    } else if ($key->origin == 6) {
						$name = "Consumidor GOV";
					} else if ($key->origin == 7) {
						$name = "Procon";
					}
                    $origin_list->push([
                        'name' => $name,
                        'total' => $key->total
                    ]);
                }
            });

        }

        return response()->json([
            'success' => true,
            'total_protocol' => $total_protocol,
            'protocol_w_u' => $protocol_w_u,
            'type_list' => array_reverse($type_list),
            'origin_list' => $origin_list,
        ], 200, array(), JSON_PRETTY_PRINT);
    }

    public function sacMonitorFilter_ajax(Request $request) {

        $field_type = $request->session()->get('filter_line') == 2 ? 2 : $request->input('type_line');
        if($field_type == 1) {
            $type_desc = 'residential';
        }
        else if ($field_type == 2) {
            $type_desc = 'commercial';
        }
        else {
            $type_desc = '';
        }

        $has_filter = false;
        if ($request->block == 1) {

            if (!empty($request->month)) {
                $has_filter = true;
                $max_day = date('t', strtotime($request->year .'-'. $request->month .'-01'));
                $total_protocol = collect([]);
                $total_days = collect([]);

                for ($i=1; $i <= $max_day; $i++) {
                    $total = SacProtocol::whereMonth('sac_protocol.created_at', $request->month)
                        ->whereYear('sac_protocol.created_at', $request->year)
						->where('is_entry_manual', 0)
                        ->whereDay('sac_protocol.created_at', $i);

                    if(!empty($type_desc)) {
                        $total->whereExists(function ($query) use ($type_desc){
                            $query->select(DB::raw(1))
                                ->from('product_air')
                                ->leftJoin('sac_model_protocol','sac_model_protocol.product_id','=','product_air.id')
                                ->whereRaw('sac_protocol.id = sac_model_protocol.sac_protocol_id')
                                ->where('product_air.'.$type_desc.'', 1);
                        });
                    }

                    $total_protocol->push($total->count());
                    $total_days->push(date('d', strtotime($i .'-'. $request->month .'-'. $request->year)));
                }

            } else {
                $has_filter = false;
                $total_protocol = collect([]);
                $total_days = collect([]);
                for ($i=1; $i <= 12; $i++) {
                    $total = SacProtocol::whereMonth('sac_protocol.created_at', $i)
						->where('is_entry_manual', 0)
                        ->whereYear('sac_protocol.created_at', date('Y'));

                    if(!empty($type_desc)) {
                        $total->whereExists(function ($query) use ($type_desc){
                            $query->select(DB::raw(1))
                                ->from('product_air')
                                ->leftJoin('sac_model_protocol','sac_model_protocol.product_id','=','product_air.id')
                                ->whereRaw('sac_protocol.id = sac_model_protocol.sac_protocol_id')
                                ->where('product_air.'.$type_desc.'', 1);
                        });
                    }

                    $total_protocol->push($total->count());
                }
            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'total_protocol' => $total_protocol,
                'total_days' => $total_days
            ], 200, array(), JSON_PRETTY_PRINT);

        } else if ($request->block == 2) {

            if (!empty($request->month)) {
                $has_filter = true;
                $protocol_w_u = collect();
                $protocol_w_u_tab = DB::table('sac_protocol')
                    ->leftjoin('users', 'sac_protocol.r_code', '=', 'users.r_code')
                    ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
                    ->where('sac_protocol.is_completed', 0)
                    ->where('sac_protocol.is_cancelled', 0)
					->where('is_entry_manual', 0)
                    ->where('sac_protocol.r_code', '!=', null)
                    ->whereMonth('sac_protocol.created_at', $request->month)
                    ->whereYear('sac_protocol.created_at', $request->year)
                    ->groupBy('sac_protocol.r_code')
                    ->orderBy('sac_protocol.r_code', 'DESC');

                if(!empty($type_desc)) {
                    $protocol_w_u_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                        ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                        ->where('product_air.'.$type_desc.'', 1);
                }

                $protocol_w_u_tab->chunk(100, function($users) use ($protocol_w_u) {
                    foreach ($users as $user)
                    {
                        $protocol_w_u->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });
            } else {
                $has_filter = false;
                $protocol_w_u = collect();
                $protocol_w_u_tab = DB::table('sac_protocol')
                    ->leftjoin('users', 'sac_protocol.r_code', '=', 'users.r_code')
                    ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
                    ->where('sac_protocol.is_completed', 0)
                    ->where('sac_protocol.is_cancelled', 0)
					->where('is_entry_manual', 0)
                    ->where('sac_protocol.r_code', '!=', null)
                    ->groupBy('sac_protocol.r_code')
                    ->orderBy('sac_protocol.r_code', 'DESC');

                if(!empty($type_desc)) {
                    $protocol_w_u_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                        ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                        ->where('product_air.'.$type_desc.'', 1);
                }

                $protocol_w_u_tab->chunk(100, function($users) use ($protocol_w_u) {
                    foreach ($users as $user)
                    {
                        $protocol_w_u->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });
            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'protocol_w_u' => $protocol_w_u
            ], 200, array(), JSON_PRETTY_PRINT);
        } else if ($request->block == 3) {

            $type_list = array(
                ['type' => 1,'name' => 'Reclamação','total' => 0],
                ['type' => 2,'name' => 'Em garantia','total' => 0],
                ['type' => 3,'name' => 'Dúvida técnica','total' => 0],
                ['type' => 4,'name' => 'Revenda','total' => 0],
                ['type' => 5,'name' => 'Credenciamento','total' => 0],
                ['type' => 6,'name' => 'Outros','total' => 0],
                ['type' => 7,'name' => 'Fora de garantia','total' => 0],
                ['type' => 8,'name' => 'Atend. negado','total' => 0],
                ['type' => 9,'name' => 'Autoriz. instalação', 'total' => 0],
                ['type' => 10,'name' => 'Atend. Tercerizado', 'total' => 0]
            );

            if (!empty($request->month)) {
                $has_filter = true;

                $type_list_tab = DB::table('sac_protocol')
                    ->select(DB::raw('count(sac_protocol.id) as total, type'))
                    ->whereMonth('sac_protocol.created_at', $request->month)
                    ->whereYear('sac_protocol.created_at', $request->year)
					->where('is_entry_manual', 0)
                    ->groupBy('sac_protocol.type')
                    ->orderBy('sac_protocol.type', 'DESC');

                if(!empty($type_desc)) {
                    $type_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                        ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                        ->where('product_air.'.$type_desc.'', 1);
                }

                foreach ($type_list_tab->get() as $key)
                {
                    $index = $key->type - 1;
                    $type_list[$index]['total'] = $key->total;
                }

            } else {
                $has_filter = false;

                $type_list_tab = DB::table('sac_protocol')
                    ->select(DB::raw('count(sac_protocol.id) as total, type'))
					->where('is_entry_manual', 0)
                    ->groupBy('sac_protocol.type')
                    ->orderBy('sac_protocol.type', 'DESC');

                if(!empty($type_desc)) {
                    $type_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                        ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                        ->where('product_air.'.$type_desc.'', 1);
                }

                foreach ($type_list_tab->get() as $key)
                {
                    $index = $key->type - 1;
                    $type_list[$index]['total'] = $key->total;
                }
            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'type_list' => array_reverse($type_list),
            ], 200, array(), JSON_PRETTY_PRINT);
        } else if ($request->block == 4) {

            if (!empty($request->month)) {
                $has_filter = true;
                $origin_list = collect();

                $origin_list_tab = DB::table('sac_protocol')
                    ->select(DB::raw('count(sac_protocol.id) as total, origin'))
                    ->whereMonth('sac_protocol.created_at', $request->month)
                    ->whereYear('sac_protocol.created_at', $request->year)
					->where('is_entry_manual', 0)
                    ->groupBy('sac_protocol.origin')
                    ->orderBy('sac_protocol.origin', 'DESC');

                if(!empty($type_desc)) {
                    $origin_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                        ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                        ->where('product_air.'.$type_desc.'', 1);
                }

                $origin_list_tab->chunk(100, function($origin) use ($origin_list) {
                    foreach ($origin as $key)
                    {
                        $name = "";
                        if ($key->origin == 1) {
                            $name = "Telefone";
                        } else if ($key->origin == 2) {
                            $name = "Email";
                        } else if ($key->origin == 3) {
                            $name = "Reclame aqui";
                        } else if ($key->origin == 4) {
                            $name = "Midias sociais";
                        } else if ($key->origin == 5) {
                            $name = "Site";
                        } else if ($key->origin == 6) {
                            $name = "Consumidor GOV";
                        } else if ($key->origin == 7) {
                            $name = "Procon";
                        }
                        $origin_list->push([
                            'name' => $name,
                            'total' => $key->total
                        ]);

                    }
                });

            } else {
                $has_filter = false;
                $origin_list = collect();

                $origin_list_tab = DB::table('sac_protocol')
                    ->select(DB::raw('count(sac_protocol.id) as total, origin'))
					->where('is_entry_manual', 0)
                    ->groupBy('sac_protocol.origin')
                    ->orderBy('sac_protocol.origin', 'DESC');

                if(!empty($type_desc)) {
                    $origin_list_tab->leftJoin('sac_model_protocol','sac_protocol.id','=','sac_model_protocol.sac_protocol_id')
                        ->leftJoin('product_air','sac_model_protocol.product_id','=','product_air.id')
                        ->where('product_air.'.$type_desc.'', 1);
                }

                $origin_list_tab->chunk(100, function($origin) use ($origin_list) {
                    foreach ($origin as $key)
                    {
                        $name = "";
                        if ($key->origin == 1) {
                            $name = "Telefone";
                        } else if ($key->origin == 2) {
                            $name = "Email";
                        } else if ($key->origin == 3) {
                            $name = "Reclame aqui";
                        } else if ($key->origin == 4) {
                            $name = "Midias sociais";
                        } else if ($key->origin == 5) {
                            $name = "Site";
                        } else if ($key->origin == 6) {
                            $name = "Consumidor GOV";
                        } else if ($key->origin == 7) {
                            $name = "Procon";
                        }
                        $origin_list->push([
                            'name' => $name,
                            'total' => $key->total
                        ]);

                    }
                });
            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'origin_list' => $origin_list
            ], 200, array(), JSON_PRETTY_PRINT);
        }
    }

    public function sacMonitorOs(Request $request) {

		$sac_remittance_news = DB::table('sac_remittance_part')->where('status', 1)->where('is_cancelled', 0)->count();

        $without_response = DB::table('sac_protocol')->where('is_completed', 0)
            ->where('is_refund', 0)
            ->where('is_cancelled', 0)
            ->where('has_notify_assist', 1);

        $analyze_part_pending = SacOsProtocol::whereDoesntHave('sacOsAnalyze')
            ->where('has_split', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)
            ->sacprotocolfilter(2);

        $part_suspense = SacOsProtocol::whereHas('sacOsAnalyze')
            ->where('has_split', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)
			->where('has_pending_payment', 0)
			->where('expedition_invoice', 0);

        $part_pending = SacOsProtocol::whereHas('sacOsAnalyze')
            ->where('has_split', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)
            ->where('has_analyze_part', 1)
            ->sacprotocolfilter(3);

        $part_approv = SacOsProtocol::where('is_cancelled', 0)
            ->where('expedition_invoice', 0)
            ->where('is_paid', 0)
            ->where('has_split', 0)
            ->sacprotocolfilter(1);

        $os_total = DB::table('sac_os_protocol')
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)->get();

        $split_pending = $os_total
            ->where('has_split', 1)
            ->where('expedition_invoice', 0);

        $in_split = $os_total
            ->where('expedition_invoice', 1);

        $pending_payment = $os_total
            ->where('has_pending_payment', 1);
		
		$gree_os = $os_total
			->where('authorized_id', 1864)
            ->where('has_pending_payment', 0);

        $services = SacOsProtocol::where('has_pending_payment', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)
            ->whereHas('sacProtocol', function($q) {
                $q->whereDoesntHave('sacpartprotocol');
            });

        $reclameaqui = SacOsProtocol::where('has_pending_payment', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0)
            ->whereHas('sacProtocol', function($q) {
                $q->where('origin', 3);
            });
		
		$warranty_extend = SacOsProtocol::where('is_cancelled', 0)
			->where('is_paid', 0)
            ->whereHas('sacProtocol', function($q) {
                $q->where('warranty_extend', 1);
            });
		
		$authorization_install = SacProtocol::where('is_cancelled', 0)
			->where('is_completed', 0)
			->where('is_refund', 0)
			->where('type', 9);

        $os_graph_open_result = DB::table('sac_os_protocol')
            ->whereYear('created_at', date('Y'))
            ->where('is_cancelled', 0)
            ->get();

        $os_graph_open_total = collect([]);

        for ($i=1; $i <= 12; $i++) {
            $total = 0;
            foreach ($os_graph_open_result as $item) {
                if (date('m', strtotime($item->created_at)) == $i)
                    $total++;
            }
            $os_graph_open_total->push($total);
        }

        $os_graph_completed_result = DB::table('sac_os_protocol')
            ->whereYear('created_at', date('Y'))
            ->where(function($q) {
                $q->where('is_paid', 1)
                    ->orWhere('has_pending_payment', 1);
            })
            ->get();

        $os_graph_completed_total = collect([]);

        for ($i=1; $i <= 12; $i++) {
            $total = 0;
            foreach ($os_graph_completed_result as $item) {
                if (date('m', strtotime($item->created_at)) == $i)
                    $total++;
            }
            $os_graph_completed_total->push($total);
        }

        $remitted_graph_open_result = DB::table('sac_remittance_part')
            ->whereYear('created_at', date('Y'))
            ->where('is_cancelled', 0)
            ->get();

        $remitted_graph_open_total = collect([]);

        for ($i=1; $i <= 12; $i++) {
            $total = 0;
            foreach ($remitted_graph_open_result as $item) {
                if (date('m', strtotime($item->created_at)) == $i)
                    $total++;
            }
            $remitted_graph_open_total->push($total);
        }

        $remitted_graph_completed_result = DB::table('sac_remittance_part')
            ->whereYear('created_at', date('Y'))
            ->where(function($q) {
                $q->where('status', 4)
                    ->orWhere('is_payment', 1);
            })
            ->get();

        $remitted_graph_completed_total = collect([]);

        for ($i=1; $i <= 12; $i++) {
            $total = 0;
            foreach ($remitted_graph_completed_result as $item) {
                if (date('m', strtotime($item->created_at)) == $i)
                    $total++;
            }
            $remitted_graph_completed_total->push($total);
        }

        $tec_with_analyze = collect();
        $tec_with_analyze_remmitance = collect();

        $tec_with_analyze_tab = DB::table('users')
            ->whereExists(function($q) {
                $q->from('sac_os_analyze')
                    ->whereRaw('sac_os_analyze.r_code = users.r_code');
            })
            ->select(DB::raw('(SELECT count(*) from sac_os_analyze where sac_os_analyze.r_code = users.r_code) as total, users.first_name, users.r_code'))
            ->where('users.r_code', '!=', '4447')
            ->orderBy('users.r_code', 'DESC');


        $tec_with_analyze_tab->chunk(100, function($users) use ($tec_with_analyze) {
            foreach ($users as $user)
            {
                $tec_with_analyze->push([
                    'name' => $user->first_name .' #'. $user->r_code,
                    'total' => $user->total
                ]);
            }
        });

        $tec_with_analyze_remmitance_tab = DB::table('users')
            ->whereExists(function($q) {
                $q->from('sac_remittance_analyze')
                    ->whereRaw('sac_remittance_analyze.r_code = users.r_code');
            })
            ->select(DB::raw('(SELECT count(*) from sac_remittance_analyze where sac_remittance_analyze.r_code = users.r_code) as total, users.first_name, users.r_code'))
            ->orderBy('users.r_code', 'DESC');


        $tec_with_analyze_remmitance_tab->chunk(100, function($users) use ($tec_with_analyze_remmitance) {
            foreach ($users as $user)
            {
                $tec_with_analyze_remmitance->push([
                    'name' => $user->first_name .' #'. $user->r_code,
                    'total' => $user->total
                ]);
            }
        });

        return view('gree_i.sac.monitorOS', [
            'os_total' => $os_total->where('has_pending_payment', 0)->count(),
			'gree_os' => $gree_os->count(),
			'sac_remittance_news' => $sac_remittance_news,
            'without_response' => $without_response->count(),
            'analyze_part_pending' => $analyze_part_pending->count(),
            'part_suspense' => $part_suspense->count(),
            'part_pending' => $part_pending->count(),
            'part_approv' => $part_approv->count(),
            'split_pending' => $split_pending->count(),
            'in_split' => $in_split->count(),
            'pending_payment' => $pending_payment->count(),
            'reclameaqui' => $reclameaqui->count(),
			'warranty_extend' => $warranty_extend->count(),
			'authorization_install' => $authorization_install->count(),
            'services' => $services->count(),
            'os_graph_open_total' => $os_graph_open_total,
            'os_graph_completed_total' => $os_graph_completed_total,
            'remitted_graph_open_total' => $remitted_graph_open_total,
            'remitted_graph_completed_total' => $remitted_graph_completed_total,
            'tec_with_analyze_remmitance' => $tec_with_analyze_remmitance,
            'tec_with_analyze' => $tec_with_analyze,
            'year_range' => range(2020, date('Y')),
        ]);
    }

    public function sacMonitorFilterOs_ajax(Request $request) {

        $has_filter = false;
        if ($request->block == 1) {
            if (!empty($request->month)) {
                $has_filter = true;
                $max_day = date('t', strtotime($request->year .'-'. $request->month .'-01'));
                $os_graph_open_total = collect([]);
                $os_graph_completed_total = collect([]);
                $total_days = collect([]);

                $os_graph_open_result = DB::table('sac_os_protocol')
                    ->whereYear('created_at', $request->year)
                    ->where('is_cancelled', 0)
                    ->get();

                $os_graph_completed_result = DB::table('sac_os_protocol')
                    ->whereYear('created_at', $request->year)
                    ->where(function($q) {
                        $q->where('is_paid', 1)
                            ->orWhere('has_pending_payment', 1);
                    })
                    ->get();

                for ($i=1; $i <= $max_day; $i++) {
                    $total = 0;
                    foreach ($os_graph_open_result as $item) {
                        if (date('d', strtotime($item->created_at)) == $i and date('m', strtotime($item->created_at)) == $request->month)
                            $total++;
                    }
                    $os_graph_open_total->push($total);
                    $total_days->push(date('d', strtotime($i .'-'. $request->month .'-'. $request->year)));
                }

                for ($i=1; $i <= $max_day; $i++) {
                    $total = 0;
                    foreach ($os_graph_completed_result as $item) {
                        if (date('d', strtotime($item->created_at)) == $i and date('m', strtotime($item->created_at)) == $request->month)
                            $total++;
                    }
                    $os_graph_completed_total->push($total);
                    $total_days->push(date('d', strtotime($i .'-'. $request->month .'-'. $request->year)));
                }

            } else {
                $has_filter = false;
                $os_graph_open_total = collect([]);
                $os_graph_completed_total = collect([]);
                $total_days = collect([]);

                $os_graph_open_result = DB::table('sac_os_protocol')
                    ->whereYear('created_at', $request->year)
                    ->where('is_cancelled', 0)
                    ->get();

                $os_graph_completed_result = DB::table('sac_os_protocol')
                    ->whereYear('created_at', $request->year)
                    ->where(function($q) {
                        $q->where('is_paid', 1)
                            ->orWhere('has_pending_payment', 1);
                    })
                    ->get();

                for ($i=1; $i <= 12; $i++) {
                    $total = 0;
                    foreach ($os_graph_open_result as $item) {
                        if (date('m', strtotime($item->created_at)) == $i)
                            $total++;
                    }
                    $os_graph_open_total->push($total);
                }

                for ($i=1; $i <= 12; $i++) {
                    $total = 0;
                    foreach ($os_graph_completed_result as $item) {
                        if (date('m', strtotime($item->created_at)) == $i)
                            $total++;
                    }
                    $os_graph_completed_total->push($total);
                }
            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'os_graph_open_total' => $os_graph_open_total,
                'os_graph_completed_total' => $os_graph_completed_total,
                'total_days' => $total_days
            ], 200, array(), JSON_PRETTY_PRINT);

        } else if ($request->block == 2) {

            if (!empty($request->month)) {
                $has_filter = true;
                $max_day = date('t', strtotime($request->year .'-'. $request->month .'-01'));
                $remitted_graph_open_total = collect([]);
                $remitted_graph_completed_total = collect([]);
                $total_days = collect([]);

                $remitted_graph_open_result = DB::table('sac_remittance_part')
                    ->whereYear('created_at', $request->year)
                    ->where('is_cancelled', 0)
                    ->get();

                $remitted_graph_completed_result = DB::table('sac_remittance_part')
                    ->whereYear('created_at', date('Y'))
                    ->where(function($q) {
                        $q->where('status', 4)
                            ->orWhere('is_payment', 1);
                    })
                    ->get();

                for ($i=1; $i <= $max_day; $i++) {
                    $total = 0;
                    foreach ($remitted_graph_open_result as $item) {
                        if (date('d', strtotime($item->created_at)) == $i and date('m', strtotime($item->created_at)) == $request->month)
                            $total++;
                    }
                    $remitted_graph_open_total->push($total);
                    $total_days->push(date('d', strtotime($i .'-'. $request->month .'-'. $request->year)));
                }

                for ($i=1; $i <= $max_day; $i++) {
                    $total = 0;
                    foreach ($remitted_graph_completed_result as $item) {
                        if (date('d', strtotime($item->created_at)) == $i and date('m', strtotime($item->created_at)) == $request->month)
                            $total++;
                    }
                    $remitted_graph_completed_total->push($total);
                    $total_days->push(date('d', strtotime($i .'-'. $request->month .'-'. $request->year)));
                }

            } else {
                $has_filter = false;
                $remitted_graph_open_total = collect([]);
                $remitted_graph_completed_total = collect([]);
                $total_days = collect([]);

                $remitted_graph_open_result = DB::table('sac_remittance_part')
                    ->whereYear('created_at', $request->year)
                    ->where('is_cancelled', 0)
                    ->get();

                $remitted_graph_completed_result = DB::table('sac_remittance_part')
                    ->whereYear('created_at', date('Y'))
                    ->where(function($q) {
                        $q->where('status', 4)
                            ->orWhere('is_payment', 1);
                    })
                    ->get();

                for ($i=1; $i <= 12; $i++) {
                    $total = 0;
                    foreach ($remitted_graph_open_result as $item) {
                        if (date('m', strtotime($item->created_at)) == $i)
                            $total++;
                    }
                    $remitted_graph_open_total->push($total);
                }

                for ($i=1; $i <= 12; $i++) {
                    $total = 0;
                    foreach ($remitted_graph_completed_result as $item) {
                        if (date('m', strtotime($item->created_at)) == $i)
                            $total++;
                    }
                    $remitted_graph_completed_total->push($total);
                }
            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'remitted_graph_open_total' => $remitted_graph_open_total,
                'remitted_graph_completed_total' => $remitted_graph_completed_total,
                'total_days' => $total_days
            ], 200, array(), JSON_PRETTY_PRINT);

        } else if ($request->block == 3) {

            if (!empty($request->month)) {

                $tec_with_analyze = collect();
                $tec_with_analyze_tab = DB::table('users')
                    ->whereExists(function($q) {
                        $q->from('sac_os_analyze')
                            ->whereRaw('sac_os_analyze.r_code = users.r_code');
                    })
                    ->select(DB::raw("(SELECT count(*) from sac_os_analyze where sac_os_analyze.r_code = users.r_code and MONTH(sac_os_analyze.created_at) = $request->month and YEAR(sac_os_analyze.created_at) = $request->year) as total, users.first_name, users.r_code"))
                    ->where('users.r_code', '!=', '4447')
                    ->orderBy('users.r_code', 'DESC');


                $tec_with_analyze_tab->chunk(100, function($users) use ($tec_with_analyze) {
                    foreach ($users as $user)
                    {
                        $tec_with_analyze->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });

            } else {

                $tec_with_analyze = collect();

                $tec_with_analyze_tab = DB::table('users')
                    ->whereExists(function($q) {
                        $q->from('sac_os_analyze')
                            ->whereRaw('sac_os_analyze.r_code = users.r_code');
                    })
                    ->select(DB::raw("(SELECT count(*) from sac_os_analyze where sac_os_analyze.r_code = users.r_code and YEAR(sac_os_analyze.created_at) = $request->year) as total, users.first_name, users.r_code"))
                    ->where('users.r_code', '!=', '4447')
                    ->orderBy('users.r_code', 'DESC');


                $tec_with_analyze_tab->chunk(100, function($users) use ($tec_with_analyze) {
                    foreach ($users as $user)
                    {
                        $tec_with_analyze->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });

            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'tec_with_analyze' => $tec_with_analyze,
            ], 200, array(), JSON_PRETTY_PRINT);
        } else if ($request->block == 4) {

            if (!empty($request->month)) {

                $tec_with_analyze_remmitance = collect();
                $tec_with_analyze_remmitance_tab = DB::table('users')
                    ->whereExists(function($q) {
                        $q->from('sac_os_analyze')
                            ->whereRaw('sac_remittance_analyze.r_code = users.r_code');
                    })
                    ->select(DB::raw("(SELECT count(*) from sac_remittance_analyze where sac_remittance_analyze.r_code = users.r_code and MONTH(sac_remittance_analyze.created_at) = $request->month and YEAR(sac_remittance_analyze.created_at) = $request->year) as total, users.first_name, users.r_code"))
                    ->where('users.r_code', '!=', '4447')
                    ->orderBy('users.r_code', 'DESC');


                $tec_with_analyze_remmitance_tab->chunk(100, function($users) use ($tec_with_analyze_remmitance) {
                    foreach ($users as $user)
                    {
                        $tec_with_analyze_remmitance->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });

            } else {

                $tec_with_analyze_remmitance = collect();

                $tec_with_analyze_remmitance_tab = DB::table('users')
                    ->whereExists(function($q) {
                        $q->from('sac_remittance_analyze')
                            ->whereRaw('sac_remittance_analyze.r_code = users.r_code');
                    })
                    ->select(DB::raw("(SELECT count(*) from sac_remittance_analyze where sac_remittance_analyze.r_code = users.r_code and YEAR(sac_remittance_analyze.created_at) = $request->year) as total, users.first_name, users.r_code"))
                    ->where('users.r_code', '!=', '4447')
                    ->orderBy('users.r_code', 'DESC');


                $tec_with_analyze_remmitance_tab->chunk(100, function($users) use ($tec_with_analyze_remmitance) {
                    foreach ($users as $user)
                    {
                        $tec_with_analyze_remmitance->push([
                            'name' => $user->first_name .' #'. $user->r_code,
                            'total' => $user->total
                        ]);
                    }
                });

            }

            return response()->json([
                'success' => true,
                'has_filter' => $has_filter,
                'tec_with_analyze_remmitance' => $tec_with_analyze_remmitance,
            ], 200, array(), JSON_PRETTY_PRINT);

        }
    }

    public function sacExpeditionPending(Request $request) {

        if (!empty($request->input('type_expedition'))) {
            $request->session()->put('ses_type_expedition', $request->input('type_expedition'));
        } else {
            $request->session()->put('ses_type_expedition', 1);
        }

        if($request->type_expedition == 3) {

            $expedition = SacRemittanceParts::with('sac_remittance_part')
                ->whereHas('sac_remittance_part', function($q) {
                    $q->where('is_cancelled', 0)
                        ->where('status', 2);
                })
                ->where('is_approv', 1)
                ->where('expedition_confirm', 0);
        }
        else if($request->type_expedition == 2) {

            $expedition = SacBuyParts::with(['SacBuyPart', 'SacPart'])
                ->whereHas('SacBuyPart', function($q){
                    $q->where('is_cancelled', 0);
                })->where('expedition_confirm', 0)
                ->where('not_part', 0)
                ->OrderBy('id', 'ASC');
        }
        else {
            $expedition = SacPartProtocol::partProtocolFilter();
        }

        $array_input = collect([
            'protocol',
            'os',
            'order_purchase',
            'code_remittance'
        ]);

        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($request->type_expedition == 1) {
                    if($name_filter == $filter_session[1]."protocol"){

                        $expedition->whereHas('sacProtocol', function($q) use ($value_filter){
                            $q->where('sac_protocol.code', $value_filter);
                        });
                    }
                    if($name_filter == $filter_session[1]."os") {

                        $expedition->whereHas('sacProtocol', function($q) use ($value_filter) {
                            $q->sacOsProtocolFilter($value_filter);
                        });
                    }
                }
                if($request->type_expedition == 2) {
                    if($name_filter == $filter_session[1]."order_purchase") {
                        $expedition->sacPurchaseOrderFilter($value_filter);
                    }
                }
                if($request->type_expedition == 3) {
                    if($name_filter == $filter_session[1]."code_remittance") {
                        $expedition->sacRemittancePart($value_filter);
                    }
                }
            }
        }

        if($request->session()->get('filter_line') == 2){
            $expedition->whereHas('SacProductAir', function($q) {
                $q->where('product_air.commercial', 1);
            });
        }

        return view('gree_i.sac.expedition_pending', [
            'expedition' => $expedition->paginate(10)
        ]);
    }

    public function sacExpeditionPending_do(Request $request) {

        if (count($request->checkitem) > 0) {

            $is_expedition = 1;
            if($request->is_expedition == 2) {
                $is_expedition = 2;
            }
            else if($request->is_expedition == 3) {
                $is_expedition = 3;
            }

            $ser = new SacExpeditionRequest;
            $ser->code_track = $request->track;
            $ser->transport = $request->transport;
            $ser->arrival_forecast = $request->arrival;
            $ser->nf_number = $request->nf_number;
            $ser->total = $request->total;
            $ser->is_expedition = $is_expedition;
            $ser->r_code = $request->session()->get('r_code');
            $ser->save();

            $p_id = 0;
            $authorized_id = 0;
            $service_code = '';

            foreach ($request->checkitem as $key) {

                if($is_expedition == 1) {

                    $item = SacPartProtocol::find($key);
                    if ($item) {

                        $item->expedition_confirm = 1;
                        $item->sac_expedition_request_id = $ser->id;
                        $item->save();
                        $p_id = $item->sac_protocol_id;

                    } else {
                        $request->session()->put('error', 'O item desejado foi excluido no momento da criação da solicitação.');
                        return redirect()->back();
                    }
                }
                else if($is_expedition == 2) {

                    $item = SacBuyParts::find($key);
                    if($item) {
                        $item->sac_expedition_request_id = $ser->id;
                        $item->expedition_confirm = 1;
                        if($item->save()) {
                            $item_part = SacBuyPart::find($item->sac_buy_part_id);
                            $item_part->track_code = $request->track;
                            $item_part->transport = $request->transport;
                            $item_part->shipping_cost = $request->total;
                            $item_part->status = 3;
                            $item_part->save();

                            $authorized_id = $item_part->authorized_id;
                            $service_code = $item_part->code;
                        }
                    } else {
                        $request->session()->put('error', 'O item desejado foi excluido no momento da criação da solicitação.');
                        return redirect()->back();
                    }
                }
                else {
                    $item = SacRemittanceParts::find($key);
                    if($item) {
                        $item->sac_expedition_request_id = $ser->id;
                        $item->expedition_confirm = 1;
                        if($item->save()) {
                            $item_part = SacRemittancePart::find($item->sac_remittance_part_id);
                            $item_part->track_code = $request->track;
                            $item_part->transport = $request->transport;
                            $item_part->shipping_cost = $request->total;
                            $item_part->status = 3;
                            $item_part->save();

                            $authorized_id = $item_part->authorized_id;
                            $service_code = $item_part->code;
                        }
                    } else {
                        $request->session()->put('error', 'Não foi possível encontrar a(s) peça(s) selecionada(s) no envio de remessa.');
                        return redirect()->back();
                    }
                }
            }

            $code = $request->track != "" ? "<br>Transportadora: ". $request->transport ."<br> Código de rastreio: ". $request->track : "";

            if($is_expedition == 1) {

                $message = new SacMsgProtocol;
                $message->message = nl2br("Peças foram enviadas <br> com previsão de chega em <br>". date('d-m-Y', strtotime($request->arrival)) ."". $code);
                $message->is_system = 1;
                $message->sac_protocol_id = $p_id;
                $message->save();
				
				$protocol = SacProtocol::with('clientProtocol', 'authorizedProtocol')->find($p_id);
				$source = array('(', ')', ' ', '-');
				$replace = array('', '', '', '');
				$phone = str_replace($source, $replace, $protocol->clientProtocol->phone);
				total_voice_sms('55'.$phone, 'Gree entrega de pecas. Protocolo: '. $protocol->code .', Previsao: '.date('d-m-Y', strtotime($request->arrival)).',  transportadora: '. $request->transport.', Rastreio: '. $request->track);
				
				$phone = str_replace($source, $replace, $protocol->authorizedProtocol->phone_1);
				total_voice_sms('55'.$phone, 'Gree entrega de pecas. Protocolo: '. $protocol->code .', Previsao: '.date('d-m-Y', strtotime($request->arrival)).',  transportadora: '. $request->transport.', Rastreio: '. $request->track);

                $os = SacOsProtocol::where('sac_protocol_id', $p_id)->where('is_cancelled', 0)->where('is_paid', 0)->get();

                foreach($os as $os_item) {
                    $msg_os = new SacMsgOs;
                    $msg_os->message = nl2br("Peças foram enviadas <br> com previsão de chega em <br>". date('d-m-Y', strtotime($request->arrival)) ."". $code);
                    $msg_os->is_system = 1;
                    $msg_os->sac_os_protocol_id = $os_item->id;
                    $msg_os->save();
                }

                $protocol = SacProtocol::find($p_id);

                if ($protocol->r_code) {

                    $user = Users::where('r_code', $protocol->r_code)->first();

                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                        'template' => 'misc.Default',
                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                    );

                    NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Peças envolvidas em seu protocolo, foram enviadas, clique aqui para visualizar.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                    SendMailJob::dispatch($pattern, $user->email);
                }

                $user = SacClient::find($protocol->client_id);

                if ($user->email) {

                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."'>". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."</a>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                    );

                    SendMailJob::dispatch($pattern, $user->email);
                }

                $sac_os = SacOsProtocol::where('sac_protocol_id', $protocol->id)->where('authorized_id', $protocol->authorized_id)->where('is_paid', 0)->where('is_cancelled', 0)->get();
                $authorized = SacAuthorized::find($protocol->authorized_id);
                if($sac_os) {
                    foreach ($sac_os as $key) {
                        $code_os = '';
                        if($key->code) {
                            $code_os = $key->code;
                        } else {
                            $code_os = '';
                        }

                        if ($authorized->email) {
                            $pattern = array(
                                'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                                'description' => nl2br("Olá! Temos atualizações da sua ordem de serviço para chegada de seu pedido de peças: (". $code_os .") <p>Previsão de entrega: ". $request->arrival ."". $code ."</p></a>"),
                                'template' => 'misc.DefaultExternal',
                                'subject' => 'O.S: '. $code_os .' atualização!',
                            );
                            SendMailJob::dispatch($pattern, $authorized->email);
                        }
                        $service_code .= ''.$code_os.' - ';
                    }
                }
            }
            else if($is_expedition == 2){

                $authorized = SacAuthorized::find($authorized_id);

                if ($authorized->email) {
                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DA ORDEM DE COMPRA',
                        'description' => nl2br("Olá! Temos atualizações da sua ordem de compra para chegada de seu pedido de peças: (". $service_code .") <p>Previsão de entrega: ". $request->arrival ."". $code ."</p></a>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'ORDEM DE COMPRA: '. $service_code .' atualização!',
                    );
                    SendMailJob::dispatch($pattern, $authorized->email);
                }
            }
            else {

                $authorized = SacAuthorized::find($authorized_id);

                if ($authorized->email) {
                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DE REMESSA DE PEÇA(S)',
                        'description' => nl2br("Olá! Temos atualizações da sua solicitação de remessa de peças: (". $service_code .") <br>Previsão de entrega: ". $request->arrival ."". $code .""),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'REMESSA DE PEÇA(S): '. $service_code .' atualização!',
                    );
                    SendMailJob::dispatch($pattern, $authorized->email);
                }
            }

            if($is_expedition == 1) {
                $subj = "O.S";
            }
            else if($is_expedition == 2) {
                $subj = "ORDEM DE COMPRA";
            }
            else {
                $subj = "REMESSA DE PEÇA";
            }

            LogSystem("Colaborador confirmou envio de peça. ".$subj." ID: ". $service_code, $request->session()->get('r_code'));
            $request->session()->put('success', 'Envio de peças confirmado com sucesso!');
            return redirect()->back();
        }

    }

    public function sacExpeditionTrack(Request $request) {

        if ($request->export == 1) {

            return Excel::download(new SacExpeditionExport($request->start_date, $request->end_date, $request->status), 'SacExpedition'. date('Y-m-d H.s') .'.xlsx');
        } else {
            // SAVE FILTERS
            if (!empty($request->input('order_purchase'))) {
                $request->session()->put('filter_order_purchase', $request->input('order_purchase'));
            } else {
                $request->session()->forget('filter_order_purchase');
            }
            if (!empty($request->input('nf_number'))) {
                $request->session()->put('filter_nf_number', $request->input('nf_number'));
            } else {
                $request->session()->forget('filter_nf_number');
            }
            if (!empty($request->input('os'))) {
                $request->session()->put('filter_os', $request->input('os'));
            } else {
                $request->session()->forget('filter_os');
            }
            if (!empty($request->input('os'))) {
                $request->session()->put('filter_', $request->input('os'));
            } else {
                $request->session()->forget('filter_os');
            }
            if (!empty($request->input('remittance_code'))) {
                $request->session()->put('filter_remittance_code', $request->input('remittance_code'));
            } else {
                $request->session()->forget('filter_remittance_code');
            }

            $ser = SacExpeditionRequest::leftJoin('sac_part_protocol','sac_expedition_request.id','=','sac_part_protocol.sac_expedition_request_id')
                ->leftJoin('sac_protocol','sac_part_protocol.sac_protocol_id','=','sac_protocol.id')
                ->leftJoin('sac_os_protocol','sac_protocol.id','=','sac_os_protocol.sac_protocol_id')
                ->leftJoin('sac_buy_parts','sac_expedition_request.id','=','sac_buy_parts.sac_expedition_request_id')
                ->leftJoin('sac_buy_part','sac_buy_parts.sac_buy_part_id','=','sac_buy_part.id')
                ->leftJoin('sac_remittance_parts','sac_expedition_request.id','=','sac_remittance_parts.sac_expedition_request_id')
                ->leftJoin('sac_remittance_part','sac_remittance_parts.sac_remittance_part_id','=','sac_remittance_part.id')
                ->leftJoin('parts','sac_part_protocol.part_id','=','parts.id')
                ->select('sac_expedition_request.*','sac_os_protocol.code as sac_os_protocol_code', 'sac_buy_part.code as buy_part_code', 'sac_remittance_part.code as remittance_part_code')
                ->groupBy('sac_protocol.code', 'sac_buy_part.code', 'sac_remittance_part.code')
                ->OrderBy('sac_expedition_request.id', 'DESC');

            if (!empty($request->session()->get('filter_order_purchase'))) {
                $ser->where('sac_buy_part.code', $request->session()->get('filter_order_purchase'));
            }
            if (!empty($request->session()->get('filter_nf_number'))) {
                $ser->where('sac_expedition_request.nf_number', $request->session()->get('filter_nf_number'));
            }
            if (!empty($request->session()->get('filter_os'))) {
                $ser->where('sac_os_protocol.code', $request->session()->get('filter_os'));
            }
            if (!empty($request->session()->get('filter_remittance_code'))) {
                $ser->where('sac_remittance_part.code', $request->session()->get('filter_remittance_code'));
            }
            if($request->session()->get('filter_line') == 2){
                $ser->leftJoin('product_air', 'sac_part_protocol.product_id', '=', 'product_air.id')
                    ->where('product_air.commercial', 1);
            }

            return view('gree_i.sac.expedition_track', [
                'ser' => $ser->paginate(10)
            ]);
        }
    }

    public function sacExpeditionTrack_do(Request $request) {

        $ser = SacExpeditionRequest::find($request->id);

        if ($ser) {

            if ($ser->is_completed == 0) {

                $ser->is_completed = 1;
                $ser->save();

                $service_code = '';

                if($ser->is_expedition == 1) {

                    $sac_part_protocol = SacPartProtocol::where('sac_expedition_request_id', $ser->id)->first();

                    $protocol = SacProtocol::find($sac_part_protocol->sac_protocol_id);

                    $message = new SacMsgProtocol;
                    $message->message = nl2br("Peças chegaram no destinatário, aguardando realização do serviço. <br> Confirmado em <br>". date('d-m-Y', strtotime($ser->updated_at)));
                    $message->is_system = 1;
                    $message->sac_protocol_id = $protocol->id;
                    $message->save();

                    if ($protocol->r_code) {

                        $user = Users::where('r_code', $protocol->r_code)->first();

                        $pattern = array(
                            'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                            'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                            'template' => 'misc.Default',
                            'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                        );

                        NotifyUser('Protocolo: #'. $protocol->code, $user->r_code, 'fa-exclamation', 'text-info', 'Peças envolvidas em seu protocolo, chegaram no destino, clique aqui para visualizar.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                        SendMailJob::dispatch($pattern, $user->email);
                    }

                    $user = SacClient::find($protocol->client_id);

                    if ($user->email) {

                        $pattern = array(
                            'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                            'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."'>". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."</a>"),
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                        );

                        SendMailJob::dispatch($pattern, $user->email);
                    }

                    $os = SacOsProtocol::where('sac_protocol_id', $protocol->id)->where('authorized_id', $protocol->authorized_id)->where('is_paid', 0)->where('is_cancelled', 0)->first();
                    $authorized = SacAuthorized::find($protocol->authorized_id);
                    if ($os) {
                        foreach ($os as $key) {
                            if ($authorized->email) {

                                $code_os = '';
                                if($key->code) {
                                    $code_os = $key->code;
                                } else {
                                    $code_os = '';
                                }

                                $pattern = array(
                                    'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                                    'description' => nl2br("Olá! Temos atualizações da sua ordem de serviço, confirmação de chegada do seu pedido de peças, OS: (". $code_os .") <p>Confirmado em: ". date('d-m-Y', strtotime($ser->updated_at)) ."</p></a>"),
                                    'template' => 'misc.DefaultExternal',
                                    'subject' => 'O.S: '. $code_os .' atualização!',
                                );
                                SendMailJob::dispatch($pattern, $authorized->email);
                            }
                            $service_code .= ''.$code_os.' - ';
                        }
                    }
                    $subj = 'O.S';
                }
                else if($ser->is_expedition == 2){

                    $sac_buy_parts = SacBuyParts::where('sac_expedition_request_id', $ser->id)->first();
                    $sac_buy_part = SacBuyPart::where('id', $sac_buy_parts->sac_buy_part_id)->first();
                    $authorized_id = $sac_buy_part->authorized_id;
                    $service_code = $sac_buy_part->code;

                    $authorized = SacAuthorized::find($authorized_id);

                    if ($authorized->email) {
                        $pattern = array(
                            'title' => 'ATUALIZAÇÃO DA ORDEM DE COMPRA',
                            'description' => nl2br("Olá! Temos atualizações da sua ordem de serviço, confirmação de chegada do seu pedido de peças: (". $sac_buy_part->code .") <p>Confirmado em: ". date('d-m-Y', strtotime($ser->updated_at)) ."</p></a>"),
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'ORDEM DE COMPRA: '. $sac_buy_part->code .' atualização!',
                        );
                        SendMailJob::dispatch($pattern, $authorized->email);
                    }
                    $subj = 'ORDEM DE COMPRA';

                } else {

                    $remittance_parts = SacRemittanceParts::where('sac_expedition_request_id', $ser->id)->first();
                    $remittance_part = SacRemittancePart::find($remittance_parts->sac_remittance_part->id);
                    if($remittance_part) {
                        $remittance_part->status = 4;
                        $remittance_part->save();
                    }
                    $service_code = $remittance_part->code;

                    $authorized = SacAuthorized::find($remittance_part->authorized_id);
                    if ($authorized->email) {
                        $pattern = array(
                            'title' => 'ATUALIZAÇÃO DE SOLICITAÇÃO DE REMESSA',
                            'description' => nl2br("Olá! Temos atualizações de sua solicitação de remessa<br> <b>Confirmação de chegada de solicitação de remessa de peças: (". $remittance_part->code .")</b> <br>Confirmado em: ". date('d-m-Y', strtotime($ser->updated_at)) ."</a>"),
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'REMESSA DE PEÇA(S): '. $remittance_part->code .' atualização!',
                        );
                        SendMailJob::dispatch($pattern, $authorized->email);
                    }

                    $subj = 'REMESSA DE PEÇA';
                }

                LogSystem("Colaborador confirmou chegada de peça no destinatário. ".$subj." ID: ". $service_code, $request->session()->get('r_code'));

                $request->session()->put('success', 'Solicitação foi finalizada com sucesso!');
                return redirect()->back();
            } else {

                $request->session()->put('error', 'Solicitação de expedição já foi concluída!');
                return redirect()->back();
            }

        } else {

            $request->session()->put('error', 'Solicitação de expedição não foi encontrada.');
            return redirect()->back();
        }
    }

    public function sacExpeditionTrackParts(Request $request, $id, $is_expedition) {

        if($is_expedition == 1) {
            $parts = SacPartProtocol::leftJoin('sac_protocol','sac_part_protocol.sac_protocol_id','=','sac_protocol.id')
                ->leftJoin('parts','sac_part_protocol.part_id','=','parts.id')
                ->leftJoin('sac_os_protocol','sac_protocol.id','=','sac_os_protocol.sac_protocol_id')
                ->select('sac_part_protocol.*', 'sac_protocol.code as sac_protocol_code', 'parts.description as parts_description', 'parts.code as parts_code', 'sac_os_protocol.code as sac_os_protocol_code')
                ->where('sac_part_protocol.sac_expedition_request_id', $id)
                ->paginate(10);
        }
        else if($is_expedition == 2){
            $parts = SacBuyParts::with(['SacBuyPart', 'SacPart'])->where('sac_expedition_request_id', $id)->paginate(10);
        }
        else {
            $parts = SacRemittanceParts::with('sac_remittance_part')->where('sac_expedition_request_id', $id)->paginate(10);
        }

        if ($parts) {

            return view('gree_i.sac.expedition_track_part', [
                'parts' => $parts,
                'id' => $id,
                'is_expedition' => $is_expedition
            ]);

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function sacWarrantyEdit(Request $request, $id) {

        $users = Users::leftjoin('user_on_permissions', 'users.r_code', '=', 'user_on_permissions.user_r_code')
            ->where('user_on_permissions.perm_id', 6)
            ->select('users.*')
            ->get();

        $problem_category = \App\Model\SacProblemCategory::All();

        if ($id == 0) {

            $r_code = "";
            $code = "";
            $buy_date = "";
            $shop = "";
            $is_warranty = 0;
            $client = [];
            $authorized = [];
            $models = [];
            $address = "";
			$district = "";
            $city = "";
            $state = "";
            $complement = "";
            $number_nf = "";
            $latitude = "";
            $longitude = "";
            $type = "";
            $origin = "";
            $description = "";
            $sac_problem_category = "";

            $nf_file = "";
            $tag_file = "";
            $c_install_file = "";
            $installed_by = "";

        } else {

            $protocol = SacProtocol::find($id);

            if ($protocol) {
                $r_code = $protocol->r_code;
                $code = $protocol->code;
                $is_warranty = $protocol->is_warranty;
                $client = SacClient::find($protocol->client_id);
                $authorized = SacAuthorized::find($protocol->authorized_id);
                $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                    ->select('sac_model_protocol.*', 'product_air.model', 'sac_model_protocol.serial_number')->where('sac_protocol_id', $id)->get();
                $type = $protocol->type;
                $buy_date = $protocol->buy_date;
                $shop = $protocol->shop;
                $number_nf = $protocol->number_nf;
                $origin = $protocol->origin;
                $description = $protocol->description;
                $address = $protocol->address;
				$district = $protocol->district;
                $city = $protocol->city;
                $state = $protocol->state;
                $complement = $protocol->complement;
                $latitude = $protocol->latitude;
                $longitude = $protocol->longitude;

                $nf_file = $protocol->nf_file;
                $tag_file = $protocol->tag_file;
                $c_install_file = $protocol->c_install_file;
                $sac_problem_category = $protocol->sac_problem_category_id;
                $installed_by = $protocol->installed_by;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        // dd($sac_problem_category);

        return view('gree_i.sac.warranty_edit', [
            'id' => $id,
            'code' => $code,
            'users' => $users,
            'is_warranty' => $is_warranty,
            'address' => $address,
			'district' => $district,
            'city' => $city,
            'state' => $state,
            'complement' => $complement,
            'number_nf' => $number_nf,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'r_code' => $r_code,
            'client' => $client,
            'authorized' => $authorized,
            'models' => $models,
            'type' => $type,
            'buy_date' => $buy_date,
            'shop' => $shop,
            'origin' => $origin,
            'description' => $description,
            'nf_file' => $nf_file,
            'tag_file' => $tag_file,
            'c_install_file' => $c_install_file,
            'problem_category' => $problem_category,
            'sac_problem_category' => $sac_problem_category,
            'installed_by' => $installed_by,
        ]);
    }
	
	private function sacRuleOsExists($protocol_id, $authorized_id, $data) {

        // Verificar se já existe OS.
        $os_exists = SacOsProtocol::with('sacProtocol', 'modelOs')->where('sac_protocol_id', $protocol_id)
            ->where('is_paid', 0)
            ->where('is_cancelled', 0)
            ->first();

        // Verifica se existe OS
        if ($os_exists) {
			
			$sac_visit_price = getConfig("sac_visit_price");
			$sac_km_price = getConfig("sac_km_price");
			$sac_distance_km = getConfig("sac_distance_km");

            $os = $os_exists->replicate();
            $os->code = null;
            $os->code_origin = $os_exists->code;
            $os->authorized_id = $os_exists->authorized_id;
            $os->total = $os->visit_total;
            $os->save();
			
			foreach ($os_exists->modelOs as $mos) {
				$new_mos = $mos->replicate();
				$new_mos->sac_os_protocol_id = $os->id;
				$new_mos->save();
			}
			
			$os_exists->authorized_id = $authorized_id;
			$os_exists->expert_name = null;
			$os_exists->expert_phone = null;
			$os_exists->description = null;
			$os_exists->observation = null;
			$os_exists->visit_date = '0000-00-00 00:00:00';
			if (count($data) > 0) {
				if ($os_exists->sacProtocol->not_address == 1) {
					$total = $sac_visit_price;
				} else {
					$total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
				}

				$max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
				if (number_format($total, 2, '.', '') > $max_total) {
					$total = $sac_visit_price;
				}
			} else {
				$total = $sac_visit_price;
			}

			// Pegar valor da mão de obra
			$new_total = $os_exists->total - $os_exists->visit_total;
			
			$os_exists->visit_total = number_format($total, 2);
			$os_exists->total = number_format($new_total + $total, 2);
			$os_exists->updated_at = $os_exists->updated_at;
			$os_exists->save();

            return [
                'exists' => true,
                'authorized' => $os_exists->authorized_id
            ];
        } else {

            return [
                'exists' => false,
                'authorized' => 0
            ];
        }
    }

    public function sacWarrantyEdit_do(Request $request) {

        if ($request->id == 0) {

            $protocol = new SacProtocol;

        } else {

            $protocol = SacProtocol::find($request->id);

            if (!$protocol) {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

            if($request->authorized) {

                if ($protocol->authorized_id != $request->authorized) {
                    $add_authorized = SacAuthorized::find($request->authorized);

                    if ($add_authorized) {

                        $client = SacClient::find($protocol->client_id);
                        $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                            ->select('product_air.model')
                            ->where('sac_protocol_id', $protocol->id)
                            ->get();

                        $pattern = array(
                            'a_name' => $add_authorized->name,
                            'c_name' => $client->name,
                            'c_phone' => $client->phone,
                            'c_phone_2' => $client->phone_2,
                            'p_address' => $protocol->address,
                            'p_model' => $models,
                            'p_tag_file' => $protocol->tag_file,
                            'p_description' => $protocol->description,
                            'title' => 'ATENDIMENTO ACEITO',
                            'description' => '',
                            'template' => 'sac.notifyAuthorized',
                            'subject' => 'Gree: Protocolo de atendimento: '. $protocol->code,
                        );

                        SendMailJob::dispatch($pattern, $add_authorized->email);
                    }
                }

                $os = SacOsProtocol::where('sac_protocol_id', $protocol->id)
                    ->where('is_paid', 0)
                    ->where('is_cancelled', 0)
                    ->where('authorized_id', $request->authorized)
                    ->first();

                if (!$os) {

                    $authorized = SacAuthorized::find($request->authorized);

                    $multiply = 1.609344;
                    $distance = getConfig("sac_distance_km");

                    $latitude = $authorized->latitude;
                    $longitude = $authorized->longitude;

                    $query = "SELECT *, "
                        . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                        . "cos( radians(latitude) ) * "
                        . "cos( radians(longitude) - radians('$longitude') ) + "
                        . "sin( radians('$latitude') ) * "
                        . "sin( radians(latitude) ) ) ,8) as distance "
                        . "from sac_protocol "
                        . "where id = ". $protocol->id ." and "
                        . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                        . "cos( radians(latitude) ) * "
                        . "cos( radians(longitude) - radians('$longitude') ) + "
                        . "sin( radians('$latitude') ) * "
                        . "sin( radians(latitude) ) ) ) ,8) "
                        . "order by distance ASC "
                        . "LIMIT 1";

                    $data = DB::select(DB::raw($query));

                    if (count($data) > 0) {

                        $sac_visit_price = getConfig("sac_visit_price");
                        $sac_km_price = getConfig("sac_km_price");
                        $sac_distance_km = getConfig("sac_distance_km");

                        // Armazena o modelo único
                        $create_unique_os = array();

                        // Armazena os modelos em conjunto
                        $create_group_os = array();

                        $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                            ->where('sac_model_protocol.sac_protocol_id', $protocol->id)
                            ->select('product_air.model', 'sac_model_protocol.*')
                            ->get();

                        foreach ($models as $model) {
                            if (substr($model->model, -2) == '/O') {

                                $search = substr($model->model, 0, -2);
                                $create_group_os[$search][] = array(
                                    $model->product_id,
                                    $model->serial_number,
                                    $model->model,
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL,
                                    $model->id,
                                );

                            } else if (substr($model->model, -2) == '/I') {

                                $search = substr($model->model, 0, -2);
                                $create_group_os[$search][] = array(
                                    $model->product_id,
                                    $model->serial_number,
                                    $model->model,
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL,
                                    $model->id,
                                );

                            } else {

                                array_push($create_unique_os, array(
                                    $model->product_id,
                                    $model->serial_number,
                                    $model->model,
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL,
                                    $model->id,
                                ));
                            }
                        }

                        if (count($create_unique_os) > 0) {
                            foreach ($create_unique_os as $create_u_os) {
                                // criar os
                                $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                    ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                    ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                    ->where('sac_os_protocol.is_paid', 0)
                                    ->where('sac_os_protocol.is_cancelled', 0)
                                    ->where('sac_model_os.product_id', $create_u_os[0])
                                    ->first();

                                if (!$os) {

                                    $os = new SacOsProtocol;
                                    $os->authorized_id = $request->authorized;
                                    $os->sac_protocol_id = $protocol->id;
                                    if ($request->authorized == '1864') {
                                        $os->expert_name = 'Gree';
                                        $os->expert_phone = '(92) 21236-900';
                                        $os->visit_date = date('Y-m-d H:i:s');
                                    }
                                    if (count($data) > 0) {
                                        if ($protocol->not_address == 1) {
                                            $total = $sac_visit_price;
                                        } else {
                                            $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                                        }

                                        $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                                        if (number_format($total, 2, '.', '') > $max_total) {
                                            $total = $sac_visit_price;
                                        }
                                    } else {
                                        $total = $sac_visit_price;
                                    }

                                    $os->visit_total = number_format($total, 2);
                                    $os->total = number_format($total, 2);

                                    $os->save();

                                }
                                $add = new SacModelOs;
                                $add->product_id = $create_u_os[0];
                                $add->serial_number = array_key_exists(1, $create_u_os) == TRUE ? $create_u_os[1] : '';
                                $add->sac_os_protocol_id = $os->id;
                                $add->sac_protocol_id = $protocol->id;
                                $add->authorized_id = $request->authorized;
                                $add->sac_model_protocol_id = $create_u_os[7];
                                $add->save();
                            }
                        }
                        if (count($create_group_os) > 0) {
                            foreach ($create_group_os as $arr_key => $create_g_os) {
                                // criar os
                                $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                    ->leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                                    ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                    ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                    ->where('sac_os_protocol.is_paid', 0)
                                    ->where('sac_os_protocol.is_cancelled', 0)
                                    ->where(function ($query) use ($arr_key) {
                                        $query->where('product_air.model', $arr_key .'/O')
                                            ->Orwhere('product_air.model', $arr_key .'/I');
                                    })
                                    ->first();

                                if (!$os) {

                                    $os = new SacOsProtocol;
                                    $os->authorized_id = $request->authorized;
                                    $os->sac_protocol_id = $protocol->id;
                                    if ($request->authorized == '1864') {
                                        $os->expert_name = 'Gree';
                                        $os->expert_phone = '(92) 21236-900';
                                        $os->visit_date = date('Y-m-d H:i:s');
                                    }
                                    if (count($data) > 0) {
                                        if ($protocol->not_address == 1) {
                                            $total = $sac_visit_price;
                                        } else {
                                            $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                                        }

                                        $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                                        if (number_format($total, 2, '.', '') > $max_total) {
                                            $total = $sac_visit_price;
                                        }
                                    } else {
                                        $total = $sac_visit_price;
                                    }

                                    $os->visit_total = number_format($total, 2);
                                    $os->total = number_format($total, 2);
                                    $os->save();


                                }
                                foreach ($create_g_os as $arr_model) {
                                    $add = new SacModelOs;
                                    $add->product_id = $arr_model[0];
                                    $add->serial_number = array_key_exists(1, $arr_model) == TRUE ? $arr_model[1] : '';
                                    $add->authorized_id = $request->authorized;
                                    $add->sac_os_protocol_id = $os->id;
                                    $add->sac_protocol_id = $protocol->id;
                                    $add->sac_model_protocol_id = $arr_model[7];
                                    $add->save();
                                }
                            }
                        }

                    } else {

                        $sac_visit_price = getConfig("sac_visit_price");
                        $sac_km_price = getConfig("sac_km_price");
                        $sac_distance_km = getConfig("sac_distance_km");

                        // Armazena o modelo único
                        $create_unique_os = array();

                        // Armazena os modelos em conjunto
                        $create_group_os = array();

                        $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                            ->where('sac_model_protocol.sac_protocol_id', $protocol->id)
                            ->select('product_air.model', 'sac_model_protocol.*')
                            ->get();

                        foreach ($models as $model) {
                            if (substr($model->model, -2) == '/O') {

                                $search = substr($model->model, 0, -2);
                                $create_group_os[$search][] = array(
                                    $model->product_id,
                                    $model->serial_number,
                                    $model->model,
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL,
                                    $model->id,
                                );

                            } else if (substr($model->model, -2) == '/I') {

                                $search = substr($model->model, 0, -2);
                                $create_group_os[$search][] = array(
                                    $model->product_id,
                                    $model->serial_number,
                                    $model->model,
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL,
                                    $model->id,
                                );

                            } else {

                                array_push($create_unique_os, array(
                                    $model->product_id,
                                    $model->serial_number,
                                    $model->model,
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL,
                                    $model->id,
                                ));
                            }
                        }

                        if (count($create_unique_os) > 0) {
                            foreach ($create_unique_os as $create_u_os) {
                                // criar os
                                $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                    ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                    ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                    ->where('sac_os_protocol.is_paid', 0)
                                    ->where('sac_os_protocol.is_cancelled', 0)
                                    ->where('sac_model_os.product_id', $create_u_os[0])
                                    ->first();

                                if (!$os) {

                                    $os = new SacOsProtocol;
                                    $os->authorized_id = $request->authorized;
                                    $os->sac_protocol_id = $protocol->id;
                                    if ($request->authorized == '1864') {
                                        $os->expert_name = 'Gree';
                                        $os->expert_phone = '(92) 21236-900';
                                        $os->visit_date = date('Y-m-d H:i:s');
                                    }

                                    $total = $sac_visit_price;
                                    $os->visit_total = number_format($total, 2);
                                    $os->total = number_format($total, 2);

                                    $os->save();

                                }
                                $add = new SacModelOs;
                                $add->product_id = $create_u_os[0];
                                $add->serial_number = array_key_exists(1, $create_u_os) == TRUE ? $create_u_os[1] : '';
                                $add->sac_os_protocol_id = $os->id;
                                $add->sac_protocol_id = $protocol->id;
                                $add->sac_model_protocol_id = $create_u_os[7];
                                $add->authorized_id = $request->authorized;
                                $add->save();
                            }
                        }
                        if (count($create_group_os) > 0) {
                            foreach ($create_group_os as $arr_key => $create_g_os) {
                                // criar os
                                $os = SacModelOs::leftjoin('sac_os_protocol', 'sac_model_os.sac_os_protocol_id', '=', 'sac_os_protocol.id')
                                    ->leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                                    ->where('sac_os_protocol.sac_protocol_id', $protocol->id)
                                    ->where('sac_os_protocol.authorized_id', $protocol->authorized_id)
                                    ->where('sac_os_protocol.is_paid', 0)
                                    ->where('sac_os_protocol.is_cancelled', 0)
                                    ->where(function ($query) use ($arr_key) {
                                        $query->where('product_air.model', $arr_key .'/O')
                                            ->Orwhere('product_air.model', $arr_key .'/I');
                                    })
                                    ->first();

                                if (!$os) {

                                    $os = new SacOsProtocol;
                                    $os->authorized_id = $request->authorized;
                                    $os->sac_protocol_id = $protocol->id;
                                    if ($request->authorized == '1864') {
                                        $os->expert_name = 'Gree';
                                        $os->expert_phone = '(92) 21236-900';
                                        $os->visit_date = date('Y-m-d H:i:s');
                                    }
                                    $total = $sac_visit_price;
                                    $os->visit_total = number_format($total, 2);
                                    $os->total = number_format($total, 2);
                                    $os->save();


                                }
                                foreach ($create_g_os as $arr_model) {
                                    $add = new SacModelOs;
                                    $add->product_id = $arr_model[0];
                                    $add->serial_number = array_key_exists(1, $arr_model) == TRUE ? $arr_model[1] : '';
                                    $add->authorized_id = $request->authorized;
                                    $add->sac_os_protocol_id = $os->id;
                                    $add->sac_protocol_id = $protocol->id;
                                    $add->sac_model_protocol_id = $arr_model[7];
                                    $add->save();
                                }
                            }
                        }

                    }

                } else {

                    $authorized = SacAuthorized::find($request->authorized);

                    if ($authorized) {
                        $multiply = 1.609344;
                        $distance = getConfig("sac_distance_km");

                        $latitude = $authorized->latitude;
                        $longitude = $authorized->longitude;

                        $query = "SELECT *, "
                            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ,8) as distance "
                            . "from sac_protocol "
                            . "where id = ". $protocol->id ." and "
                            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                            . "cos( radians(latitude) ) * "
                            . "cos( radians(longitude) - radians('$longitude') ) + "
                            . "sin( radians('$latitude') ) * "
                            . "sin( radians(latitude) ) ) ) ,8) "
                            . "order by distance ASC "
                            . "LIMIT 1";

                        $data = DB::select(DB::raw($query));

                        if (count($data) > 0) {

                            $sac_visit_price = getConfig("sac_visit_price");
                            $sac_km_price = getConfig("sac_km_price");
                            $sac_distance_km = getConfig("sac_distance_km");

                            // Armazena o modelo único
                            $create_unique_os = array();

                            // Armazena os modelos em conjunto
                            $create_group_os = array();

                            $oss = SacOsProtocol::where('sac_protocol_id', $protocol->id)
                                ->where('authorized_id', $request->authorized)
                                ->where('is_cancelled', 0)
                                ->where('is_paid', 0)
                                ->get();

                            if (count($oss) > 0) {
                                foreach ($oss as $os) {
                                    $os->authorized_id = $request->authorized;
                                    $os->sac_protocol_id = $protocol->id;
                                    if ($request->authorized == '1864') {
                                        $os->expert_name = 'Gree';
                                        $os->expert_phone = '(92) 21236-900';
                                        $os->visit_date = date('Y-m-d H:i:s');
                                    }
                                    if (count($data) > 0) {
                                        if ($protocol->not_address == 1) {
                                            $total = $sac_visit_price;
                                        } else {
                                            $total = ($data[0]->distance * $sac_km_price) + $sac_visit_price;
                                        }

                                        $max_total = $sac_visit_price + (number_format($sac_km_price * $sac_distance_km,2));
                                        if (number_format($total, 2, '.', '') > $max_total) {
                                            $total = $sac_visit_price;
                                        }
                                    } else {
                                        $total = $sac_visit_price;
                                    }

                                    $os->visit_total = number_format($total, 2);
                                    $os->total = number_format($total, 2);

                                    $os->save();
                                }
								
                            }

                        }
                    }

                }

            }

        }

        $protocol->r_code = $request->r_code;
        if ($request->is_warranty == 1) {
			if ($request->id != 0) {
				if (!$protocol->is_warranty == 0) {
					$protocol->is_warranty_start = date('Y-m-d H:i:s');
				}
			} else {
				$protocol->is_warranty_start = date('Y-m-d H:i:s');
			}
        }
		
        $protocol->is_warranty = $request->is_warranty ? 1 : 0;
        $protocol->number_nf = $request->number_nf;
        $protocol->client_id = $request->client;
        $protocol->authorized_id = $request->authorized;
        $protocol->type = $request->type;
        $protocol->origin = $request->origin;
        $protocol->description = $request->description;
        $protocol->address = $request->address;
		$protocol->district = $request->district;
        $protocol->city = $request->city;
        $protocol->state = $request->state;
        $protocol->complement = $request->complement;
        if ($request->installed_by)
            $protocol->installed_by = $request->installed_by;

        if ($request->problem_category)
            $protocol->sac_problem_category_id = $request->problem_category;

        if ($request->latitude and $request->longitude) {
            $protocol->latitude = $request->latitude;
            $protocol->longitude = $request->longitude;
        }
        $protocol->not_address = $request->not_address == 1 ? 1 : 0;
        if ($request->buy_date) {
            $old_date = str_replace("/", "-", $request->buy_date);
            $buy_date = date('Y-m-d', strtotime($old_date));
            $protocol->buy_date = $buy_date;
        }
        $protocol->shop = $request->shop;

        if ($request->hasFile('nf_file')) {
            $response = $this->uploadS3(1, $request->nf_file, $request);
            if ($response['success']) {
                $protocol->nf_file = $response['url'];
            } else {
                return Redirect('/sac/warranty/edit/'. $request->id);
            }
        }
        if ($request->hasFile('tag_file')) {
            $response = $this->uploadS3(2, $request->tag_file, $request);
            if ($response['success']) {
                $protocol->tag_file = $response['url'];
            } else {
                return Redirect('/sac/warranty/edit/'. $request->id);
            }
        }
        if ($request->hasFile('c_install_file')) {
            $response = $this->uploadS3(3, $request->c_install_file, $request);
            if ($response['success']) {
                $protocol->c_install_file = $response['url'];
            } else {
                return Redirect('/sac/warranty/edit/'. $request->id);
            }
        }

        $protocol->save();

        // JSON
        $raw_payload = $request->input('json_data');
        $payload = json_decode($raw_payload, true);

        if (isset($payload)) {
            if (count($payload) > 0) {
                foreach ($payload as $key) {

                    if ($key['item_id'] > 0) {
                        $add = SacModelProtocol::find($key['item_id']);
                        $upd = SacModelOs::where('sac_model_protocol_id', $key['item_id'])
                            ->get();

                        if ($upd->count() > 0) {
                            foreach($upd as $upd_item) {
                                $save_item = SacModelOs::find($upd_item->id);
                                $save_item->product_id = $key['product_id'];
                                $save_item->serial_number = $key['serial'];
                                $save_item->save();
                            }
                        }
                    } else {
                        $add = new SacModelProtocol;
                    }
                    $add->product_id = $key['product_id'];
                    $add->serial_number = $key['serial'];
                    $add->price = $key['price'];
                    $add->sac_protocol_id = $protocol->id;
                    $add->save();

                }
            }
        }

        if ($request->id == 0) {
            // CREATE PROTOCOL
            $code = sacCreateProtocol($protocol->id);
        }

        if ($request->id == 0) {

            $client = SacClient::find($request->client);
            $p_id = $code;
            if ($client->email) {

                $pattern = array(
                    'title' => 'PROTOCOLO: '. $p_id,
                    'description' => nl2br("Olá! Para acompanhar o seu protocolo: (". $p_id .") Acesse o link abaixo: \n\n <a href='". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."'>". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."</a>"),
                    'template' => 'misc.DefaultExternal',
                    'subject' => 'Gree - Protocolo: '. $p_id,
                );

                SendMailJob::dispatch($pattern, $client->email);
            }
        }

        if ($request->is_warranty == 1 and empty($request->authorized) and $request->alertRequest == 1) {

            SacAlertOperator::dispatch($protocol->id)->delay(now()->addDays(2));

            $multiply = 1.609344;
            $distance = getConfig("sac_distance_km");
            if (isset($code)) {
                $p_id = $code;
            } else {
                $p_id = $protocol->code;
            }


            $latitude = $protocol->latitude;
            $longitude = $protocol->longitude;

            // GEN ACCEPT CODE
            if (!$protocol->accept_code) {
                $accept = $protocol->id .''. Str::random(10);
                $a_protocol = SacProtocol::find($protocol->id);
                $a_protocol->accept_code = $accept;
                $a_protocol->save();
            }

            $query = "SELECT *, "
                . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                . "cos( radians(latitude) ) * "
                . "cos( radians(longitude) - radians('$longitude') ) + "
                . "sin( radians('$latitude') ) * "
                . "sin( radians(latitude) ) ) ,8) as distance "
                . "from sac_authorized "
                . "where is_active = 1 and "
                . "type = 1 and "
                . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                . "cos( radians(latitude) ) * "
                . "cos( radians(longitude) - radians('$longitude') ) + "
                . "sin( radians('$latitude') ) * "
                . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                . "order by distance ASC "
                . "LIMIT 10";

            $data = DB::select(DB::raw($query));

            if (count($data) > 0) {

                $client = SacClient::find($protocol->client_id);
                $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                    ->select('product_air.model')
                    ->where('sac_protocol_id', $protocol->id)
                    ->get();

                foreach ($data as $key) {

                    if ($key->only_repair_sell == 0) {
                        if (SacProtocol::where('authorized_id', $key->id)->where('is_completed', 0)->where('is_cancelled', 0)->where('is_refund', 0)->count() == 0) {

                            $distance = round($key->distance, 2);
                            $total = getConfig("sac_visit_price");
                            if ($request->not_address == 1) {
                                $total = getConfig("sac_visit_price");
                            } else {
                                $total = ($distance * getConfig("sac_km_price")) + getConfig("sac_visit_price");

                            }
                            $max_total = getConfig("sac_visit_price") + (number_format(getConfig("sac_km_price") * getConfig("sac_distance_km"),2));
                            if (number_format($total, 2, '.', '') > $max_total) {
                                $total = getConfig("sac_visit_price");
                            }

                            $pattern = array(
                                'a_name' => $key->name,
                                'c_name' => $client->name,
                                'c_phone' => $client->phone,
                                'c_phone_2' => $client->phone_2,
                                'p_city' => $protocol->city,
                                'p_state' => $protocol->state,
                                'p_address' => $protocol->address,
								'p_district' => $protocol->district,
                                'p_model' => $models,
                                'p_tag_file' => $protocol->tag_file,
                                'p_description' => $protocol->description,
                                'code' => $protocol->accept_code,
                                'total' => $total,
                                'title' => 'ATENDIMENTO EM GARANTIA',
                                'description' => '',
                                'template' => 'sac.notifyAuthorizeds',
                                'subject' => 'Gree: Protocolo de atendimento: '. $p_id,
                            );

                            SendMailJob::dispatch($pattern, $key->email);

                        }
                    }

                }

            } else {

                $pattern = array(
                    'title' => 'SEM AUTORIZADA PRÓXIMA',
                    'description' => nl2br("Foi realizado um possível atendimento em garantia: \n \n Protocolo: ". $p_id ." \n Endereço: ". $protocol->address ." \n \n Em um raio de:". $distance ."KM."),
                    'template' => 'misc.DefaultExternal',
                    'subject' => 'Gree - sem autorizada próxima',
                );

                SendMailJob::dispatch($pattern, getConfig("sac_email_default"));

            }

        }

        if ($request->id == 0) {
            LogSystem("Colaborador criou novo protocolo para sac ". $protocol->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Novo atendimento criado com sucesso!");
        }
        else {
            LogSystem("Colaborador atualizou protocolo para sac ". $protocol->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Atendimento atualizado com sucesso!");
        }
        return Redirect('/sac/warranty/edit/'. $request->id);
    }

    public function sacWarrantyNotifyAssist(Request $request, $id) {

        $protocol = SacProtocol::find($id);

        if (!$protocol) {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

        if ($protocol->has_notify_assist == 1) {

            $protocol->has_notify_assist = 2;
            $request->session()->put('success', 'Protocolo foi marcado como respondido.');
        } else {

            $protocol->has_notify_assist = 1;
            $request->session()->put('success', 'Protocolo foi enviado para lista da assistência para tratamento.');
        }

        $protocol->save();


        return redirect()->back();
    }

    public function sacWarrantyPart(Request $request, $id) {

        $os = SacOsProtocol::find($id);
        if (!$os) {
            $request->session()->put('error', 'A OS que está tentando solicitar peça, não existe mais.');
            return redirect()->back();
        }

        $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', 'product_air.id')
            ->select('product_air.*', 'sac_model_protocol.serial_number')
            ->where('sac_model_protocol.sac_protocol_id', $os->sac_protocol_id)
            ->get();

        $protocol = SacProtocol::find($os->sac_protocol_id);
        if (!$protocol) {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

        $parts = SacPartProtocol::where('sac_protocol_id', $os->sac_protocol_id)->where('sac_os_protocol_id', $os->id)->get();
        LogSystem("Colaborador a página de solicitação de peças: ". $os->id, $request->session()->get('r_code'));

        return view('gree_i.assisttec.part_request', [
            'id' => $os->sac_protocol_id,
            'models' => $models,
            'protocol' => $protocol,
            'parts' => $parts,
            'os' => $os,
        ]);
    }

    public function sacWarrantyPart_do(Request $request) {

        $protocol = SacProtocol::find($request->id);
        $protocol->paid_info = $request->paid_info;
        $protocol->save();

        $group = $request->group;

        $os = SacOsProtocol::where('id', $request->os_id)->first();

        $txt = "";
        $has_new = false;
        $has_approv = 0;
        $total = 0.00;
        if ($group) {
            if (count($group) > 0) {
                for ($i = 0; $i < count($group); $i++) {

                    if (isset($group[$i]['model']) and isset($group[$i]['part']) and isset($group[$i]['quantity'])) {

                        if ($group[$i]['item_id'] != 0 and $group[$i]['item_id'] != "") {
                            $item = SacPartProtocol::find($group[$i]['item_id']);
                            $has_new = false;
							if (!$item)
								continue;

                            if ($item->product_id != $group[$i]['model']) {
                                $upd_model_osn = SacModelOs::where('sac_protocol_id', $item->sac_protocol_id)->where('product_id', $item->product_id)->first();
                                if ($upd_model_osn) {
                                    $upd_model_osn->product_id = $group[$i]['model'];
                                    $upd_model_osn->save();
                                }
                            }
                        } else {
                            $item = new SacPartProtocol;
                            $has_new = true;
                        }

                        $item->sac_protocol_id = $request->id;
						$item->sac_os_protocol_id = $request->os_id;
                        $item->product_id = $group[$i]['model'];
                        $item->part_id = $group[$i]['part'];
						$item->serial_number = $group[$i]['serial_number'];
                        $item->description = $group[$i]['description'];
						$item->description_defect = $group[$i]['description_defect'];
						$item->description_reason = $group[$i]['description_reason'];
                        if ($group[$i]['quantity'] > 0) {
                            $item->quantity = $group[$i]['quantity'];
                        } else {
                            $item->quantity = 1;
                        }
                        if (isset($group[$i]['status'])) {
                            if (!empty($group[$i]['status'])) {

                                if ($group[$i]['status'] != $item->is_approv) {
                                    if ($group[$i]['status'] == 1) {
                                        $has_approv = 1;
                                        $part = Parts::find($group[$i]['part']);
										$part_desc = "";
										if ($part) {
											$part_desc = $part->description;
										}
										$txt .= nl2br("\n". $group[$i]['quantity'] ."x ". $part_desc);
                                        
                                    }
                                }

                                $item->is_approv = $group[$i]['status'] == 1 ? 1 : 0;
                                $item->is_repprov = $group[$i]['status'] == 2 ? 1 : 0;
                                $item->who_analyze = $request->session()->get('r_code');
								$item->date_analyze = date('Y-m-d H:i:s');

                            }
                        }
                        if ($group[$i]['price']) {
                            $item->total = $group[$i]['price'];
                        } else {
                            $item->total = 0.00;
                        }
                        $item->save();

                    }

                }

				$parts = SacPartProtocol::where('sac_protocol_id', $protocol->id)
							->where('sac_os_protocol_id', $request->os_id)
                            ->get();
				
                if ($parts->count() > 0) {
                    if ($os) {
						
                        // Reset value
                        $os->total = $os->visit_total;
                        $os->save();

						foreach ($parts as $key) {
							
								if ($key->is_approv == 1) {
									$os->total += number_format($key->total, 2);
								}

								if (is_null($os->code)) {
									//Add Code
									$last_id = Settings::where('command', 'last_os_id')->first();
									$seg = $last_id->value + 1;
									$os->created_at = date('Y-m-d H:i:s');
									$os->code = 'W'. $seg;

									// update id
									$last_id->value = $seg;
									$last_id->save();
								}
								$os->save();
						}
                    }
                }
            }

            $authorized = SacAuthorized::find($protocol->authorized_id);

            if ($txt) {
                $message = new SacMsgProtocol;
                $message->message = nl2br($authorized->name ."\n Peça(s) preparada(s) pra envio ". $txt);
                $message->is_system = 1;
                $message->message_visible = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                if ($os) {
					$message = new SacMsgOs;
					$message->message = nl2br("<b>Aprovado por</b> \n". $request->session()->get('first_name')." ". $request->session()->get('last_name')."\n <b>Matricula: </b> ". $request->session()->get('r_code'));
					$message->is_system = 1;
					$message->message_visible = 0;
					$message->sac_os_protocol_id = $os->id;
					$message->save();
					
                    $message = new SacMsgOs;
                    $message->message = nl2br("Gree do Brasil \n Aprovou sua(s) peça(s) \n Aprovado em: ". date('d/m/Y H:i') ."". $txt);
                    $message->is_system = 1;
                    $message->message_visible = 1;
                    $message->sac_os_protocol_id = $os->id;
                    $message->save();
                }


                if ($protocol->r_code) {

                    $user = Users::where('r_code', $protocol->r_code)->first();

                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                        'template' => 'misc.Default',
                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                    );

                    SendMailJob::dispatch($pattern, $user->email);
                }

                $user = SacClient::find($protocol->client_id);

                if ($os) {
                    if ($authorized->email) {

                        if ($authorized->email_copy)
                            $copy = [$authorized->email_copy];
                        else
                            $copy = [];

                        $pattern = array(
                            'title' => 'APROVAÇÃO DE PEÇAS',
                            'description' => nl2br("Olá! Foi aprovado as seguintes peças \n ". $txt ." \n\n veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/autorizada/os'>". $request->root() ."/autorizada/os</a>"),
                            'copys' => $copy,
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'OS: '. $os->code .' Aprovação de peças!',
                        );

                        SendMailJob::dispatch($pattern, $authorized->email);
                    }
                }

                if ($user->email) {

                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."'>". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."</a>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                    );

                    if ($protocol->r_code) {
                        NotifyUser('Protocolo: #'. $protocol->code, $protocol->r_code, 'fa-exclamation', 'text-info', 'Houve aprovação de pedido de peças em seu protocolo, clique aqui para visualizar.', $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                    }
                    SendMailJob::dispatch($pattern, $user->email);
                }

                LogSystem("Colaborador atualizou informações de peças do protocolo: ". $protocol->id, $request->session()->get('r_code'));
            }

            $request->session()->put('success', "Peças do atendimento foram atualizadas com sucesso!");
            return redirect()->back();
        } else {
            $request->session()->put('success', "Operação realizada com sucesso, sem atualização de peças!");
            return redirect()->back();
        }
    }

    public function sacWarrantyPartDelete(Request $request, $id) {
        $part = SacPartProtocol::find($id);

        if ($part) {

            LogSystem("Colaborador removeu a peça do protocolo: ". $part->sac_protocol_id, $request->session()->get('r_code'));

			$os_id = $part->sac_os_protocol_id;
            SacPartProtocol::where('id', $id)->delete();

            // Reset value
            $os = SacOsProtocol::where('id', $os_id)->first();
            $os->total = $os->visit_total;
            $os->save();
			
            $parts = SacPartProtocol::where('sac_os_protocol_id', $os_id)
                ->get();

			foreach ($parts as $key) {

				if ($key->is_approv == 1) {
					$os->total += number_format($key->total, 2);
				}

				if (is_null($os->code)) {
					//Add Code
					$last_id = Settings::where('command', 'last_os_id')->first();
					$seg = $last_id->value + 1;
					$os->created_at = date('Y-m-d H:i:s');
					$os->code = 'W'. $seg;

					// update id
					$last_id->value = $seg;
					$last_id->save();
				}
				$os->save();
			}

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function sacWarrantyModelDelete(Request $request, $id) {
        $model = SacModelProtocol::find($id);

        if ($model) {

            LogSystem("Colaborador removeu o modelo do protocolo: ". $model->sac_protocol_id, $request->session()->get('r_code'));
            SacModelProtocol::where('id', $id)->delete();
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function sacWarrantyInteractive(Request $request, $id) {

        $protocol = SacProtocol::find($id);

        if (!$protocol) {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

        $client = SacClient::find($protocol->client_id);

        $sp_model = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
            ->select('product_air.model', 'sac_model_protocol.serial_number')
            ->where('sac_protocol_id', $id)
            ->get();

        $messages = SacMsgProtocol::where('sac_protocol_id', $id)->get();
        $userall = Users::all();

        //$os = SacOsProtocol::where('sac_protocol_id', $id)->where('is_paid', 1)->first();
        $os = null;

        $report_os = SacOsProtocol::where('sac_protocol_id', $id)
            ->where('is_paid', 0)
            ->where('is_cancelled', 0)
            ->orderBy('id', 'DESC')
            ->first();

        $all_os = SacOsProtocol::leftjoin('sac_authorized', 'sac_os_protocol.authorized_id', '=', 'sac_authorized.id')
            ->select('sac_authorized.name', 'sac_authorized.identity', 'sac_os_protocol.code', 'sac_os_protocol.id')
            ->where('sac_os_protocol.sac_protocol_id', $id)
            ->where('sac_os_protocol.is_paid', 0)
            ->where('sac_os_protocol.is_cancelled', 0)
            ->orderBy('sac_os_protocol.id', 'DESC')
            ->get();

        return view('gree_i.sac.warranty_interactive', [
            'id' => $id,
            'all_os' => $all_os,
            'userall' => $userall,
            'protocol' => $protocol,
            'messages' => $messages,
            'client' => $client,
            'sp_model' => $sp_model,
            'os' => $os,
            'report_os' => $report_os,
        ]);
    }

    public function sacWarrantyInteractiveEdit(Request $request) {

        $message = SacMsgProtocol::find($request->message_id);

        if (!$message)
            return redirect()->back()->with('error', 'Não foi encontrada a message para ser atualizada.');

        if ($request->type_action == 2) {
            $message->delete();

            return redirect()->back()->with('success', 'Mensagem foi deletada com sucesso!');
        } else {
            $message->message_visible = $request->message_visible == 1 ? 1 : 0;
            $message->message = nl2br($request->message_txt);
            $message->save();

            return redirect()->back()->with('success', 'Mensagem foi atualizada com sucesso!');
        }

    }

    public function sacWarrantySendMsg(Request $request) {

        if ($request->id) {

            $message = new SacMsgProtocol;
            $message->sac_protocol_id = $request->id;
            $message->r_code = $request->session()->get('r_code');
            $message->message_visible = $request->send_type == 1 ? 1 : 0;
            $message->message = nl2br($request->msg);

            if ($request->hasFile('file_msg')) {
                $response = $this->uploadS3(1, $request->file_msg, $request);
                if ($response['success']) {
                    $message->file = $response['url'];
                } else {
                    return redirect()->back();
                }
            }

            $message->save();

            $protocol = SacProtocol::find($request->id);
            $protocol->has_aswer = 1;
            $protocol->save();

            if ($request->send_type == 1) {

                $user = SacClient::find($protocol->client_id);

                if ($user->email) {

                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DE PROTOCOLO',
                        'description' => nl2br("Olá! Temos atualizações do seu protocolo: (". $protocol->code .") veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."'>". $request->root() ."/suporte/interacao/atendimento/". $protocol->id ."</a>"),
                        'template' => 'misc.DefaultExternal',
						'h_namespace' => 'App\Model\SacProtocol',
						'h_code' => $protocol->code,
                        'subject' => 'Protocolo: '. $protocol->code .' atualização!',
                    );

                    SendMailJob::dispatch($pattern, $user->email);
                }
            }

            if ($request->user_alert) {
                // Convert comma to array
                $users_alert = explode(',', $request->user_alert);
                if (count($users_alert) > 0) {
                    foreach ($users_alert as $key) {
                        $user = Users::where('r_code', $key)->first();

                        $pattern = array(
                            'title' => 'CONVITE DE INTERAÇÃO',
                            'description' => nl2br("". nl2br($request->msg) ."\n\n veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/interactive/". $protocol->id ."'>". $request->root() ."/sac/warranty/interactive/". $protocol->id ."</a>"),
                            'template' => 'misc.Default',
                            'subject' => 'Protocolo: '. $protocol->code .' convite de interação!',
                        );

                        $msg = "";
                        if (!$request->msg) {
                            $msg = "Clique aqui para ver mais informações sobre esse cnovite.";
                        } else {
                            $msg = $request->msg;
                        }
                        NotifyUser('Convite de interação', $user->r_code, 'fa-exclamation', 'text-info', $msg, $request->root() .'/sac/warranty/interactive/'. $protocol->id);
                        SendMailJob::dispatch($pattern, $user->email);

                    }
                }
            }

            LogSystem("Colaborador interagiou com o protocolo: ". $request->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Nova interação inserida com sucesso! O cliente será notificado.");
            return redirect()->back();
        } else {

            $request->session()->put('error', "Ocorreu algum erro ao inserir a interação, tente novamente.");
            return redirect()->back();
        }
    }

    public function sacWarrantyInteractiveUpd(Request $request, $id) {

        $protocol = SacProtocol::find($id);

        if ($protocol) {

            if ($request->status == 1) {
				$protocol->in_wait_documents = 0;
                $protocol->in_wait = 1;
                $protocol->in_progress = 0;
                $protocol->is_completed = 0;
                $protocol->is_cancelled = 0;
                $protocol->pending_completed = 0;
                $protocol->is_denied = 0;
                $protocol->is_refund = 0;
				$protocol->is_refund_pending = 0;

                LogSystem("Colaborador atualizou os status para aguardando do protocolo: ". $id, $request->session()->get('r_code'));
            } else if ($request->status == 2) {
				$protocol->in_wait_documents = 0;
                $protocol->in_wait = 1;
                $protocol->in_progress = 1;
                $protocol->is_completed = 0;
                $protocol->is_cancelled = 0;
                $protocol->pending_completed = 0;
                $protocol->is_denied = 0;
                $protocol->is_refund = 0;
				$protocol->is_refund_pending = 0;

                LogSystem("Colaborador atualizou os status para em andamento do protocolo: ". $id, $request->session()->get('r_code'));
            } else if ($request->status == 3) {
                $protocol->in_wait = 1;
                $protocol->in_progress = 1;
                $protocol->is_completed = 1;
                $protocol->pending_completed = 0;
                $protocol->is_cancelled = 0;
                $protocol->is_denied = 0;
                $protocol->is_refund = 0;
				$protocol->is_refund_pending = 0;
				
				// Mudar os para pendentes de pagamento
				$os_pending = SacOsProtocol::where('sac_protocol_id', $protocol->id)->where('is_cancelled', 0)->get();
				foreach ($os_pending as $os_key) {
					$os_key->has_pending_payment = 1;
					$os_key->save();
				}

				$message = new SacMsgProtocol;
                $message->message = nl2br("<b>Finalizado por</b> \n". $request->session()->get('first_name')." ". $request->session()->get('last_name')."\n <b>Matricula: </b> ". $request->session()->get('r_code'));
                $message->is_system = 1;
				$message->message_visible = 0;
                $message->sac_protocol_id = $protocol->id;
                $message->save();
				
				$protocol->who_finalized = $request->session()->get('first_name')." ". $request->session()->get('last_name') ." (".$request->session()->get('r_code').")";
				
                $message = new SacMsgProtocol;
                $message->message = nl2br("<b>Gree finalizou o atendimento</b> \n". date('d-m-Y H:i'));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                LogSystem("Colaborador atualizou os status para finalizado do protocolo: ". $id, $request->session()->get('r_code'));
                if ($protocol->authorized_id) {

                    $authorized = SacAuthorized::find($protocol->authorized_id);

                    if ($authorized->live < 10) {
                        $authorized->live = $authorized->live + 1;
                        $authorized->save();
                    }
                }

            } else if ($request->status == 4) {
                $protocol->is_cancelled = 1;
                $protocol->is_denied = 0;
                $protocol->pending_completed = 0;
                $protocol->in_wait = 1;
                $protocol->in_progress = 1;
                $protocol->is_completed = 0;
                $protocol->is_refund = 0;
				$protocol->is_refund_pending = 0;

                $message = new SacMsgProtocol;
                $message->message = nl2br("<b>Gree cancelou o atendimento</b> \n". date('d-m-Y H:i'));
                $message->is_system = 1;
                $message->sac_protocol_id = $protocol->id;
                $message->save();

                LogSystem("Colaborador atualizou os status para cancelado do protocolo: ". $id, $request->session()->get('r_code'));
                if ($protocol->authorized_id) {
                    $os_pending = SacOsProtocol::leftjoin('sac_protocol', 'sac_protocol.id', '=', 'sac_os_protocol.sac_protocol_id')
                        ->where('sac_protocol.authorized_id', $protocol->authorized_id)
                        ->where('sac_os_protocol.visit_date', '0000-00-00 00:00:00')
                        ->where('sac_os_protocol.is_cancelled', 0)
                        ->first();

                    if ($os_pending) {
                        $os_pending->is_cancelled == 1;
                        $os_pending->save();

                    }
                }

            } else if ($request->status == 5) {
                $protocol->is_cancelled = 1;
                $protocol->is_denied = 0;
                $protocol->pending_completed = 0;
                $protocol->in_wait = 1;
                $protocol->in_progress = 1;
                $protocol->is_completed = 0;
                $protocol->is_refund = 1;
				$protocol->is_refund_pending = 0;

				// Mudar os para pendentes de pagamento
				$os_pending = SacOsProtocol::where('sac_protocol_id', $protocol->id)->where('is_cancelled', 0)->get();
				foreach ($os_pending as $os_key) {
					$os_key->has_pending_payment = 1;
					$os_key->save();
				}

                LogSystem("Colaborador atualizou os status para reembolso do protocolo: ". $id, $request->session()->get('r_code'));
            } else if ($request->status == 6) {
                $protocol->is_cancelled = 0;
                $protocol->is_denied = 0;
                $protocol->pending_completed = 0;
				$protocol->in_wait_documents = 1;
                $protocol->in_wait = 1;
                $protocol->in_progress = 1;
                $protocol->is_completed = 0;
                $protocol->is_refund = 0;
				$protocol->is_refund_pending = 0;

                LogSystem("Colaborador atualizou os status para aguardando documentos do protocolo: ". $id, $request->session()->get('r_code'));
            } else if ($request->status == 7) {
                $protocol->is_cancelled = 0;
                $protocol->is_denied = 0;
                $protocol->pending_completed = 0;
				$protocol->in_wait_documents = 1;
                $protocol->in_wait = 1;
                $protocol->in_progress = 1;
                $protocol->is_completed = 0;
                $protocol->is_refund = 0;
				$protocol->is_refund_pending = 1;

                LogSystem("Colaborador atualizou os status para reembolso pendente do protocolo: ". $id, $request->session()->get('r_code'));
            }

            
            if ($request->is_warranty == 1 and !$protocol->is_warranty) {
                $protocol->is_warranty_start = date('Y-m-d H:i:s');
            }
			
			$protocol->is_warranty = $request->is_warranty;
			
			if ($request->warranty_extend) {
				$protocol->warranty_extend = $request->warranty_extend;
			} else {
				$protocol->warranty_extend = 0;
			}

            $protocol->type = $request->type;
            $protocol->save();

            if ($request->is_warranty == 1 and $protocol->authorized_id == null and $request->alertRequest == 1) {

                SacAlertOperator::dispatch($protocol->id)->delay(now()->addDays(2));

                $multiply = 1.609344;
                $distance = getConfig("sac_distance_km");

                $latitude = $protocol->latitude;
                $longitude = $protocol->longitude;

                // GEN ACCEPT CODE
                if (!$protocol->accept_code) {
                    $accept = $protocol->id .''. Str::random(10);
                    $a_protocol = SacProtocol::find($protocol->id);
                    $a_protocol->accept_code = $accept;
                    $a_protocol->save();
                } else {
                    $accept = $protocol->accept_code;
                }

                $query = "SELECT *, "
                    . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ,8) as distance "
                    . "from sac_authorized "
                    . "where is_active = 1 and "
                    . "type = 1 and "
                    . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                    . "order by distance ASC "
                    . "LIMIT 10";

                $data = DB::select(DB::raw($query));

                if (count($data) > 0) {

                    $client = SacClient::find($protocol->client_id);
                    $models = SacModelProtocol::leftjoin('product_air', 'sac_model_protocol.product_id', '=', 'product_air.id')
                        ->select('product_air.model')
                        ->where('sac_protocol_id', $protocol->id)
                        ->get();

                    foreach ($data as $key) {

                        if ($key->only_repair_sell == 0) {
                            $distance = round($key->distance, 2);
                            $total = ($distance * getConfig("sac_km_price")) + getConfig("sac_visit_price");

                            $pattern = array(
                                'a_name' => $key->name,
                                'c_name' => $client->name,
                                'c_phone' => $client->phone,
                                'c_phone_2' => $client->phone_2,
                                'p_city' => $protocol->city,
                                'p_state' => $protocol->state,
                                'p_address' => $protocol->address,
								'p_district' => $protocol->district,
                                'p_model' => $models,
                                'p_tag_file' => $protocol->tag_file,
                                'p_description' => $protocol->description,
                                'code' => $accept,
                                'total' => $total,
                                'title' => 'ATENDIMENTO EM GARANTIA',
                                'description' => '',
                                'template' => 'sac.notifyAuthorizeds',
                                'subject' => 'Gree: Protocolo de atendimento: '. $protocol->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    }

                } else {

                    $pattern = array(
                        'title' => 'SEM AUTORIZADA PRÓXIMA',
                        'description' => nl2br("Foi realizado um possível atendimento em garantia: \n \n Protocolo: ". $protocol->code ." \n Endereço: ". $protocol->address ." \n \n Em um raio de:". $distance ."KM."),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Gree - sem autorizada próxima',
                    );

                    SendMailJob::dispatch($pattern, getConfig("sac_email_default"));

                }

            }

            $request->session()->put('success', "Status do protocol foi atualizado com sucesso!");
            return redirect()->back();
        } else {

            $request->session()->put('error', "Ocorreu algum erro, tente novamente.");
            return redirect()->back();
        }
    }

    public function sacWarrantyAll(Request $request) {

        $sacProtocol = new SacProtocol;
        $protocol = $sacProtocol->protocolRelOrderfilter();

        $array_input = collect([
            'r_code',
            'segment',
            'authorized',
            'code',
            'status',
            'is_warranty',
            'origin',
            'type',
            'left_5',
            'left_15',
            'left_30',
            'type_attendance',
            'client',
            'not_response',
            'model',
            'city',
            'state',
            'start_date',
            'end_date',
            'monitor_block_1',
            'monitor_block_2',
            'monitor_block_3',
            'monitor_block_4',
			'monitor_p_block_1',
			'monitor_p_block_2',
			'monitor_p_block_3',
			'monitor_p_block_4',
			'monitor_p_block_5',
			'monitor_p_block_6',
			'monitor_p_block_7',
			'monitor_p_block_8',
			'monitor_p_block_9',
			'monitor_p_block_10',
			'operator_not_response',
			'problem_category'
        ]);

        $array_input = putSession($request, $array_input, 'sacf_');
        $filtros_sessao = getSessionFilters('sacf_');

        $type_line = 0;
        if($filtros_sessao[0]->isNotEmpty()){

            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."monitor_block_1"){
                    $protocol->where('is_completed', 0)
                        ->where('is_refund', 0)
                        ->where('is_cancelled', 0)
                        ->where('is_entry_manual', 0)
                        ->where('has_aswer', 0);
                }
                if($nome_filtro == $filtros_sessao[1]."monitor_block_3"){
                    $protocol->whereRaw('id IN(SELECT sac_protocol_id FROM sac_os_protocol
                              WHERE EXISTS (SELECT 1
											FROM sac_part_protocol
											WHERE sac_part_protocol.sac_protocol_id = sac_protocol.id
											AND (is_approv = 1 OR is_approv = 0 AND is_repprov = 0))
                              AND DATE(visit_date) < CURDATE()
                              AND sac_os_protocol.is_cancelled = 0
                              AND sac_os_protocol.is_paid = 0
                              GROUP BY sac_protocol_id)')
                        ->where('is_completed', 0)
                        ->where('is_refund', 0)
                        ->where('is_cancelled', 0)
                        ->where('pending_completed', 0)
                        ->where('is_entry_manual', 0)
                        ->where('has_aswer', 1);
                }
                if($nome_filtro == $filtros_sessao[1]."monitor_block_4"){
                    $protocol->where('pending_completed', 1);
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_1"){
                    $protocol->where('r_code', NULL)
						->where('is_cancelled', 0)
						->where('is_completed', 0)
						->where('is_entry_manual', 0)
						->whereDoesntHave('sacosprotocol');
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_2"){
                    $protocol->where('is_completed', 0)
						->where('is_refund', 0)
						->where('is_refund_pending', 0)
						->where('is_entry_manual', 0)
						->where('r_code', '!=', NULL)
						->where('is_cancelled', 0)
						->where('is_completed', 0)
						->where('authorized_id', NULL)
						->where('in_wait', 1)
						->where('in_wait_documents', 0)
						->whereDoesntHave('sacosprotocol');
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_3"){
                    $protocol->where('is_completed', 0)
						->where('is_refund', 0)
						->where('is_refund_pending', 0)
						->where('is_entry_manual', 0)
						->where('r_code', '!=', NULL)
						->where('authorized_id', NULL)
						->where('is_cancelled', 0)
						->where('is_completed', 0)
						->where('in_wait_documents', 1)
						->whereDoesntHave('sacosprotocol');
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_4"){
                    $protocol->where('is_completed', 0)
						->where('is_refund', 0)
						->where('is_refund_pending', 0)
						->where('is_entry_manual', 0)
						->where('r_code', '!=', NULL)
						->where('authorized_id', NULL)
						->where('is_cancelled', 0)
						->where('is_completed', 0)
						->where('in_wait_documents', 0)
						->where('is_warranty', 1)
						->whereDoesntHave('sacosprotocol');
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_5"){
                    $protocol->where('is_completed', 0)
						->where('is_refund', 0)
						->where('is_refund_pending', 0)
						->where('is_entry_manual', 0)
						->where('r_code', '!=', NULL)
						->where('authorized_id', '!=', NULL)
						->where('is_cancelled', 0)
						->where('is_completed', 0)
						->where('in_wait_documents', 0)
						->where('is_warranty', 1)
						->whereRaw("id IN(SELECT sac_protocol_id FROM sac_os_protocol
								  WHERE DATE(visit_date) >= CURDATE()
								  AND sac_os_protocol.is_cancelled = 0
								  AND sac_os_protocol.is_paid = 0
								  AND sac_os_protocol.has_pending_payment = 0
								  AND sac_os_protocol.visit_date != '0000-00-00 00:00:00'
								  GROUP BY sac_protocol_id)");
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_6"){
                    $protocol->where('is_completed', 0)
						->where('is_refund', 0)
						->where('is_refund_pending', 0)
						->where('is_entry_manual', 0)
						->where('r_code', '!=', NULL)
						->where('authorized_id', '!=', NULL)
						->where('is_cancelled', 0)
						->where('is_completed', 0)
						->where('in_wait_documents', 0)
						->where('is_warranty', 1)
						->whereRaw("id IN(SELECT sac_protocol_id FROM sac_os_protocol
								  WHERE NOT EXISTS (SELECT 1
												FROM sac_part_protocol
												WHERE sac_part_protocol.sac_protocol_id = sac_protocol.id
												AND (is_approv = 1 OR is_approv = 0 AND is_repprov = 0))
								  AND DATE(visit_date) < CURDATE()
								  AND sac_os_protocol.is_cancelled = 0
								  AND sac_os_protocol.is_paid = 0
								  AND sac_os_protocol.has_pending_payment = 0
								  AND sac_os_protocol.visit_date != '0000-00-00 00:00:00'
								  GROUP BY sac_protocol_id)");
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_7"){
                    $protocol->where('is_completed', 0)
							->where('is_refund', 0)
							->where('is_entry_manual', 0)
							->where('is_cancelled', 0)
							->where('is_completed', 0)
							->where('is_refund_pending', 1);
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_8"){
                    $protocol->where('is_completed', 0)
							->where('is_refund', 0)
							->where('is_refund_pending', 0)
							->where('is_entry_manual', 0)
							->where('is_cancelled', 0)
							->where('is_completed', 0)
							->where('origin', 3);
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_9"){
                    $protocol->where('is_completed', 0)
							->where('is_refund', 0)
							->where('is_refund_pending', 0)
							->where('is_entry_manual', 0)
							->where('is_cancelled', 0)
							->where('is_completed', 0)
							->where('type', 10);
                }
				if($nome_filtro == $filtros_sessao[1]."monitor_p_block_10"){
                    $protocol->where('is_completed', 0)
							->where('is_refund', 0)
							->where('is_cancelled', 0)
							->where('type', 9);
                }
                if($nome_filtro == $filtros_sessao[1]."left_5"){
                    $protocol->sacProtocolLeftFilter(5, $type_line);
                }
                if($nome_filtro == $filtros_sessao[1]."left_15"){
                    $protocol->sacProtocolLeftFilter(15, $type_line);
                }
                if($nome_filtro == $filtros_sessao[1]."left_30"){
                    $protocol->sacProtocolLeftFilter(30, $type_line);
                }
                if($nome_filtro == $filtros_sessao[1]."r_code"){
                    $protocol->where('sac_protocol.r_code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $protocol->where('sac_protocol.code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."type_attendance"){
                    $protocol->where('sac_protocol.type', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."authorized"){
                    $protocol->where('sac_protocol.authorized_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $protocol->where('sac_protocol.client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."model"){
                    $protocol->SacModelsProtocolFilter($valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."city"){
                    $protocol->where('city', 'like','%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."state"){
                    $protocol->where('state', 'like','%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."city"){
                    $protocol->where('city', 'like','%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $protocol->where('created_at','>=', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $protocol->where('created_at','<=', date('Y-m-d 23:59:59', strtotime($valor_filtro)));
                }

                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1) {
                        $protocol->where('sac_protocol.in_wait', 1)
                            ->where('sac_protocol.pending_completed', 0)
                            ->where('sac_protocol.in_progress', 0)
                            ->where('sac_protocol.is_cancelled', 0)
                            ->where('sac_protocol.is_refund', 0)
                            ->where('sac_protocol.is_completed', 0);
                    } else if ($valor_filtro == 2) {
                        $protocol->where('sac_protocol.in_progress', 1)
                            ->where('sac_protocol.is_completed', 0)
                            ->where('sac_protocol.pending_completed', 0)
                            ->where('sac_protocol.is_refund', 0)
                            ->where('sac_protocol.is_cancelled', 0);
                    } else if ($valor_filtro == 3) {
                        $protocol->where('sac_protocol.is_completed', 1);
                    } else if ($valor_filtro == 4) {
                        $protocol->where('sac_protocol.is_cancelled', 1)
                            ->where('sac_protocol.is_completed', 0);
                    } else if ($valor_filtro == 5) {
                        $protocol->where('sac_protocol.pending_completed', 1);
                    } else if ($valor_filtro == 6) {
                        $protocol->where('sac_protocol.is_refund', 1);
                    } else if ($valor_filtro == 7) {
                        $protocol->where('sac_protocol.is_denied', 1);
                    } else if ($valor_filtro == 8) {
                        $protocol->where('sac_protocol.r_code', null)
							->where('sac_protocol.is_completed', 0)
							->where('sac_protocol.is_cancelled', 0)
							->where('sac_protocol.is_refund', 0);
                    } else if ($valor_filtro == 9) {
						$protocol->where('is_cancelled', 0)
							->where('is_refund', 0)
							->where('is_completed', 0)
							->where('is_entry_manual', 0)
							->OnlyMsgNotReadFilter();
					}
                }
                if($nome_filtro == $filtros_sessao[1]."is_warranty"){
                    if ($valor_filtro == 1) {
                        $protocol->where('sac_protocol.is_warranty', 1);
                    } else if ($valor_filtro == 2) {
                        $protocol->where('sac_protocol.is_warranty', 0);
                    }
                }
                if($nome_filtro == $filtros_sessao[1]."origin"){
                    $protocol->where('sac_protocol.origin', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."not_response"){
                    $protocol->where('sac_protocol.has_notify_assist', $valor_filtro)
                        ->where('sac_protocol.is_refund', 0)
                        ->where('sac_protocol.is_completed', 0)
                        ->where('sac_protocol.is_cancelled', 0);
                }
                if($nome_filtro == $filtros_sessao[1]."segment"){
                    if ($valor_filtro == 'residential') {
                        $protocol->sacModelProtocolFilter(1);
                        $type_line = 1;
                    } else {
                        $protocol->sacModelProtocolFilter(2);
                        $type_line = 2;
                    }
                }
				if($nome_filtro == $filtros_sessao[1]."problem_category"){
                    $protocol->where('sac_protocol.sac_problem_category_id', $valor_filtro);
                }
            }
        }

        if ($request->session()->get('filter_line') == 2) {
            $protocol->sacModelProtocolFilter(2);
            $type_line = 2;
        }

        if ($request->export_external == 1) {

			$request->merge(['e_r_code' => $request->session()->get('r_code')]);
            $response = $this->fileManagerSVR(
				$request->all(),
				'/api/v1/sac/protocol/export/general'
			);

			if (!$response->success)
            	return redirect()->back()->with('error', $response->msg);

			return redirect('/sac/warranty/all')->with('success','Você receberá o Excel em seu email cadastrado no seu perfil e também no alerta do seu painel.');
        }

        $userall = Users::all();
		$problem_category = \App\Model\SacProblemCategory::All();

        return view('gree_i.sac.warranty_list', [
            'userall' => $userall,
			'problem_category' => $problem_category,
            'total_5' =>  $sacProtocol->sacProtocolLeftFilter(5, $type_line)->count(),
            'total_15' => $sacProtocol->sacProtocolLeftFilter(15, $type_line)->count(),
            'total_30' => $sacProtocol->sacProtocolLeftFilter(30, $type_line)->count(),
            'protocol' => $protocol->paginate(10),
        ]);
    }

    public function sacAttachmentAll(Request $request){
        $data = [
            1 => $this->sacIdsOsProtocol($request->id), //file
            2 => SacMsgProtocol::where('sac_protocol_id', $request->id)->where('file', '!=', NULL)->where('file', '!=', "")->get(), //file
            3 => SacOsProtocol::where('sac_protocol_id', $request->id)
                ->where('is_cancelled', 0)
                ->where('expert_name', '!=', null)
                ->orderBy('id', 'DESC')->first(), //os_signature, diagnostic_test, diagnostic_test_part
            4 => SacProtocol::where('id', $request->id)->first(), //nf_file, c_install_file, tag_file
        ];


        return response()->json([
            'success' => true,
            'data' =>  $this->sacTabView($request->tab, $data),
        ], 200);
    }

    private function sacIdsOsProtocol($id){
        $IdSacOsProtocol = SacOsProtocol::where('sac_protocol_id', $id)->get()->pluck('id')->toArray();

        $result = SacMsgOs::with('sac_os_protocol')->whereIn('sac_os_protocol_id', $IdSacOsProtocol)->where('file', '!=', NULL)->get();

        return $result;
    }

    private function sacTabView($tab, $data){

        $dataView = [];

        if($tab == 1){
            foreach ($data[$tab] as $key){
                $arr = [];
                $arr['name'] = 'Arquivo Referene a OS: '.$key->sac_os_protocol->code;
                $arr['file'] = $key->file;

                array_push($dataView, $arr);
            }
        };

        if($tab == 2){
            foreach ($data[$tab] as $key){
                $arr = [];
                $arr['name'] = '';
                $arr['file'] = $key->file;

                array_push($dataView, $arr);
            }
        };

        if($tab == 3){
            if(isset($data[$tab])){
                $arr = [];
                $arr['name'] = 'OS Assinada';
                $arr['file'] = ($data[$tab]->os_signature) ? $data[$tab]->os_signature : '';
                array_push($dataView, $arr);

                $arr = [];
                $arr['name'] = 'Relatório técnico';
                $arr['file'] = ($data[$tab]->diagnostic_test) ? $data[$tab]->diagnostic_test: '';
                array_push($dataView, $arr);

                $arr = [];
                $arr['name'] = 'Diagnóstico de Teste da Peça';
                $arr['file'] = ($data[$tab]->diagnostic_test_part) ? $data[$tab]->diagnostic_test_part : '';
                array_push($dataView, $arr);
            }
        }

        if($tab == 4){
            if(isset($data[$tab])){
                $arr = [];
                $arr['name'] = 'Notal Fiscal';
                $arr['file'] = ($data[$tab]->nf_file != null) ? $data[$tab]->nf_file : '';
                array_push($dataView, $arr);

                $arr = [];
                $arr['name'] = 'Comprovante de Instalação';
                $arr['file'] = ($data[$tab]->c_install_file != null) ? $data[$tab]->c_install_file : '';
                array_push($dataView, $arr);

                $arr = [];
                $arr['name'] = 'Foto da etiqueta';
                $arr['file'] = ($data[$tab]->tag_file != null) ? $data[$tab]->tag_file : '';
                array_push($dataView, $arr);
            }
        }

        return $dataView;

    }

    public function sacProblemcategory(Request $request) {
        $problemcategory = \App\Model\SacProblemCategory::all();

        if (!empty($request->input('type_line'))) {
            $request->session()->put('sacf_type_line', $request->input('type_line'));
        } else {
            $request->session()->forget('sacf_type_line');
            if($request->session()->get('filter_line') == 2){
                $request->session()->put('sacf_type_line', 2);
            }
        }

        $list = \App\Model\SacProblemCategory::orderBy('id', 'DESC');

        if (!empty($request->session()->get('sacf_type_line'))) {

            if($request->session()->get('sacf_type_line') == 1) {
                $list->where('type_line', 1);
            }
            else {
                $list->where('type_line', 2);
            }
        }

        return view('gree_i.sac.sac_problemcategory', [
            'list' => $list->paginate(10),
        ]);
    }

    public function sacProblemcategoryExcluir(Request $request){

        DB::beginTransaction();
        try{
            $problemcategory = SacProblemCategory::where('id',$request->id)->delete();
            $request->session()->put('success', "Descrição excluída com sucesso!");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Algo deu errado. Consulte o TI!');
        }
        DB::commit();

        return Redirect('/sac/problemcategory');
    }

    public function sacProblemacategoryEdit(Request $request) {
        $descript = $request->Input('description');

        DB::beginTransaction();
        try {
            if($request->id == 0) {
                $description = new \App\Model\SacProblemCategory;
                $description->description = $descript;
                $request->session()->put('success', "Descrição criada com sucesso!");
            }else{
                $description = \App\Model\SacProblemCategory::find($request->id);
                $description->description = $descript;
                $request->session()->put('success', "Descrição atualizada com sucesso!");
            }

            $description->save();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Algo deu errado. Consulte o TI!');
        }
        DB::commit();

        return Redirect('/sac/problemcategory');
    }

    public function sacWarrantyOsPaid(Request $request) {

        if (!empty($request->input('code'))) {
            $request->session()->put('sacf_code', $request->input('code'));
        } else {
            $request->session()->forget('sacf_code');
        }
        if (!empty($request->input('os'))) {
            $request->session()->put('sacf_os', $request->input('os'));
        } else {
            $request->session()->forget('sacf_os');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }

        $os = DB::table('sac_os_protocol')
            ->leftJoin('sac_protocol','sac_os_protocol.sac_protocol_id','=','sac_protocol.id')
            ->leftJoin('sac_authorized','sac_os_protocol.authorized_id','=','sac_authorized.id')
            ->select('sac_os_protocol.*', 'sac_authorized.name as sac_authorized_name', 'sac_protocol.code as sac_protocol_code', 'sac_protocol.updated_at as sac_protocol_updated_at', 'sac_protocol.paid_info', 'sac_protocol.id as sac_protocol_id')
            ->where(function ($q) {
                $q->where('sac_protocol.is_completed', 1)
                    ->where('sac_os_protocol.is_paid', 0)
                    ->where('sac_os_protocol.is_cancelled', 0)
                    ->orWhere(function ($query) {
                        $query->where('sac_protocol.is_refund', 1)
                            ->where('sac_os_protocol.is_paid', 0)
                            ->where('sac_os_protocol.is_cancelled', 0);
                    });

            })
            ->orderBy('sac_protocol.updated_at', 'ASC');

        if (!empty($request->session()->get('sacf_code'))) {
            $os->where('sac_protocol.code', $request->session()->get('sacf_code'));
        }
        if (!empty($request->session()->get('sacf_os'))) {
            $os->where('sac_os_protocol.code', $request->session()->get('sacf_os'));
        }
        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $os->where('sac_os_protocol.payment_nf_request', 0)
                    ->where('sac_os_protocol.request_tec_approv', 0);
            } else if ($request->session()->get('sacf_status') == 2) {
                $os->where('sac_os_protocol.payment_nf_request', 0)
                    ->where('sac_os_protocol.request_tec_approv', 1);
            } else if ($request->session()->get('sacf_status') == 3) {
                $os->where('sac_os_protocol.payment_nf_request', 1);

            }
        }

        return view('gree_i.sac.warranty_os_paid', [
            'os' => $os->paginate(10),
        ]);
    }

    public function sacWarrantyOsModel(Request $request, $id) {

        $models_protocol = SacOsProtocol::find($id);
        $models_os = SacModelOs::where('sac_os_protocol_id', $id);

        $sac_protocol_os = SacModelProtocol::where('sac_protocol_id', $models_protocol->sac_protocol_id)
            ->whereNotExists(function ($query) use ($id) {
                $query->select(DB::raw(1))
                    ->from('sac_model_os')
                    ->where('sac_model_os.sac_os_protocol_id', $id)
                    ->whereColumn('sac_model_os.sac_protocol_id', '=', 'sac_model_protocol.sac_protocol_id')
                    ->whereColumn('sac_model_os.product_id', '=', 'sac_model_protocol.product_id');
            })->get();

        return view('gree_i.sac.warranty_os_model', [
            'id' => $id,
            'sac_protocol_os' => $sac_protocol_os,
            'authorized_id' => $models_protocol->authorized_id,
            'sac_protocol_id' => $models_protocol->sac_protocol_id,
            'code_os' => $models_protocol->code,
            'models_protocol' => $models_protocol,
            'models_os' => $models_os
        ]);
    }

    public function sacWarrantyOsModelAjax(Request $request) {

        //$model_os = SacModelOs::where('sac_os_protocol_id', $request->os_id)->where('sac_model_protocol_id', $request->model_id);
        //if($model_os->count() == 0) {

            $model_protocol = SacModelProtocol::where('id', $request->model_id)->where('sac_protocol_id', $request->sac_protocol_id)->first();

            $model = new SacModelOs;
            $model->sac_os_protocol_id = $request->os_id;
            $model->sac_protocol_id = $model_protocol->sac_protocol_id;
            $model->sac_model_protocol_id = $request->model_id;
            $model->authorized_id = $request->authorized_id;
            $model->product_id = $model_protocol->product_id;
            $model->serial_number = $model_protocol->serial_number;

            $model->save();

            // Write Log
            LogSystem("Colaborador adicionou um novo modelo a ordem de serviço. OS ID: ". $request->os_id, $request->session()->get('r_code'));

            return response()->json([
                'message' => 'Modelo adicionado a Ordem de Serviço!',
            ], 200);
        //}
    }

    public function sacWarrantyOsModelDelete(Request $request, $os_id, $model_id) {
        $model = SacModelOs::where('sac_os_protocol_id', $os_id)->where('sac_model_protocol_id', $model_id);
        if ($model) {
            SacModelOs::where('sac_os_protocol_id', $os_id)->where('sac_model_protocol_id', $model_id)->delete();

            // Write Log
            LogSystem("Colaborador removeu o modelo da ordem de serviço. OS ID: ". $os_id, $request->session()->get('r_code'));

            $request->session()->put('success', 'Modelo excluído com sucesso!');
            return redirect()->back();
        }
        else {
            $request->session()->put('success', 'Modelo não encontrado!');
            return redirect()->back();
        }
    }

    public function sacWarrantyOSInteractive(Request $request, $id) {

        $os = SacOsProtocol::with(['modelOs.sacProductAir', 'authorizedOs', 'sacProtocol'])->where('id', $id)->first();

        if (!$os) {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

        $messages = SacMsgOs::where('sac_os_protocol_id', $id)->get();

        $userall = Users::all();

        return view('gree_i.sac.warranty_os_interactive', [
            'id' => $id,
            'os' => $os,
            'userall' => $userall,
            'messages' => $messages
        ]);
    }
	
	public function sacWarrantyOSInteractiveEdit(Request $request) {

		$message = SacMsgOs::find($request->message_id);

		if (!$message)
			return redirect()->back()->with('error', 'Não foi encontrada a message para ser atualizada.');

		if ($request->type_action == 2) {
			$message->delete();

			LogSystem("Colaborador excluiu mensagem(id: ".$message->id.") da Interação O.S: ". $message->sac_os_protocol_id, $request->session()->get('r_code'));
			return redirect()->back()->with('success', 'Mensagem foi deletada com sucesso!');

		} else {
			$message->message_visible = $request->message_visible == 1 ? 1 : 0;
			$message->message = nl2br($request->message_txt);
			$message->save();

			LogSystem("Colaborador atualizou mensagem da Interação O.S, ID: ". $message->id, $request->session()->get('r_code'));
			return redirect()->back()->with('success', 'Mensagem foi atualizada com sucesso!');
		}
	}

    public function sacWarrantySendOsMsg(Request $request) {

        if ($request->id) {

            $message = new SacMsgOs;
            $message->sac_os_protocol_id = $request->id;
            $message->r_code = $request->session()->get('r_code');
            $message->message_visible = $request->send_type == 1 ? 1 : 0;
            $message->message = nl2br($request->msg);

            if ($request->hasFile('file_msg')) {
                $response = $this->uploadS3(1, $request->file_msg, $request, 800);
                if ($response['success']) {
                    $message->file = $response['url'];
                } else {
                    $request->session()->put('error', 'Não foi possível realizar upload da imagem');
                    return redirect()->back();
                }
            }

            $message->save();

            if($request->os_code != "") {
                $code = $request->os_code;
            } else {
                $code = "Protocolo: ".$request->protocol_code;
            }

            if ($request->send_type == 1) {

                $user = SacAuthorized::find($request->authorized_id);

                if ($user->email) {

                    $pattern = array(
                        'title' => 'ATUALIZAÇÃO DA ORDEM DE SERVIÇO',
                        'description' => nl2br("Olá! Temos atualizações da sua ordem de serviço: (". $code ."), veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/autorizada/os/interacao/". $request->id ."'>". $request->root() ."/autorizada/os/interacao/". $request->id ."</a>"),
                        'template' => 'misc.DefaultExternal',
                        'subject' => 'Ordem de serviço: '. $code .' atualização!',
                    );

                    SendMailJob::dispatch($pattern, $user->email);
                }
            }

            if ($request->user_alert) {
                // Convert comma to array
                $users_alert = explode(',', $request->user_alert);
                if (count($users_alert) > 0) {
                    foreach ($users_alert as $key) {
                        $user = Users::where('r_code', $key)->first();

                        $pattern = array(
                            'title' => 'CONVITE DE INTERAÇÃO',
                            'description' => nl2br("". nl2br($request->msg) ."\n\n veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/sac/warranty/os/interactive/". $request->id ."'>". $request->root() ."/sac/warranty/os/interactive/". $request->id ."</a>"),
                            'template' => 'misc.Default',
                            'subject' => 'Ordem de serviço: '. $code .' convite de interação!',
                        );

                        $msg = "";
                        if (!$request->msg) {
                            $msg = "Clique aqui para ver mais informações sobre esse convite.";
                        } else {
                            $msg = $request->msg;
                        }
                        NotifyUser('Ordem de serviço: Convite de interação', $user->r_code, 'fa-exclamation', 'text-info', $msg, $request->root() .'/sac/warranty/os/interactive/'. $request->id);
                        SendMailJob::dispatch($pattern, $user->email);

                    }
                }
            }

            LogSystem("Colaborador interagiou com a ordem de serviço: ". $request->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Nova interação inserida com sucesso! O autorizado será notificado.");
            return redirect()->back();
        } else {

            $request->session()->put('error', "Ocorreu algum erro ao inserir a interação, tente novamente.");
            return redirect()->back();
        }
    }

    public function sacWarrantyPrintOb(Request $request, $id) {

        $ob = SacBuyPart::find($id);

        if ($ob) {

            $authorized = SacAuthorized::find($ob->authorized_id);

            $parts = SacBuyParts::where('sac_buy_part_id', $id)
                ->get();

            return view('gree_i.sac.ob_print', [
                'ob' => $ob,
                'authorized' => $authorized,
                'parts' => $parts
            ]);

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');

        }
    }

    public function sacWarrantyObSendPrint(Request $request) {

        if (count($request->check) > 0) {

            foreach ($request->check as $key) {
                $item = SacBuyPart::where('id', $key)->where('status', '!=', 6)->first();
                if ($item) {

                    $item->status = 6;
                    $item->save();

                    LogSystem("Colaborador mudou os status da ordem de compra para impressão: #". $item->code, $request->session()->get('r_code'));
                } else {
                    $request->session()->put('error', 'A Ordem de compra desejada foi excluido ou já foi imprimida.');
                    return redirect()->back();
                }
            }

        } else {

            $request->session()->put('error', 'Você precisa selecionar ao menos 1 para realizar essa ação.');
            return redirect()->back();
        }

        $request->session()->put('success', "Ordem de compras foram enviadas para impressão.");
        return redirect()->back();
    }

    public function sacWarrantyOb(Request $request) {

        if ($request->export == 1) {

            if (!empty($request->input('type_line_exp'))) {
                $request->session()->put('sacf_type_line', $request->input('type_line_exp'));
            } else {
                $request->session()->forget('sacf_type_line');
                if($request->session()->get('filter_line') == 2) {
                    $request->session()->put('sacf_type_line', 2);
                }
            }

            $ob = DB::table('sac_buy_part')
                ->leftjoin('sac_authorized', 'sac_buy_part.authorized_id', '=', 'sac_authorized.id')
                ->select('sac_buy_part.*', 'sac_authorized.name', 'sac_authorized.identity', 'sac_authorized.address', 'sac_authorized.zipcode', 'sac_authorized.code as sac_authorized_code')
                ->where('sac_buy_part.created_at', '>=', $request->start_date)
                ->where('sac_buy_part.created_at', '<=', $request->end_date ." 23:59:59")
                ->orderBy('sac_buy_part.id', 'DESC');

            if (!empty($request->session()->get('sacf_type_line'))) {
                if ($request->session()->get('sacf_type_line') == 1) {
                    $ob->leftJoin('sac_buy_parts', 'sac_buy_part.id', '=', 'sac_buy_parts.sac_buy_part_id')
                        ->leftJoin('product_air', 'sac_buy_parts.model', '=', 'product_air.id')
                        ->where('product_air.residential', 1)
                        ->where('sac_buy_parts.not_part', 0);
                } else if($request->session()->get('sacf_type_line') == 2) {
                    $ob->leftJoin('sac_buy_parts', 'sac_buy_part.id', '=', 'sac_buy_parts.sac_buy_part_id')
                        ->leftJoin('product_air', 'sac_buy_parts.model', '=', 'product_air.id')
                        ->where('product_air.commercial', 1)
                        ->where('sac_buy_parts.not_part', 0);
                } else if($request->session()->get('sacf_type_line') == 3) {
                    $ob->leftJoin('sac_buy_parts', 'sac_buy_part.id', '=', 'sac_buy_parts.sac_buy_part_id')
                        ->leftJoin('product_air', 'sac_buy_parts.model', '=', 'product_air.id')
                        ->where('sac_buy_parts.not_part', 1);
                }
            }

            $heading = array('Código', 'Código de credenciado', 'Autorizada', 'Endereço', 'CEP', 'Solicitado em', 'Status');
            $rows = array();

            foreach ($ob->get() as $key) {
                $line = array();

                $status = "";
                if ($key->status == 1 and $key->is_cancelled == 0) {
                    $status = "Em análise";
                } else if ($key->status == 2 and $key->is_cancelled == 0) {
                    $status = "Aguardando pagamento";
                } else if ($key->status == 3 and $key->is_cancelled == 0) {
                    $status = "Enviando";
                } else if ($key->status == 4 and $key->is_cancelled == 0) {
                    $status = "Concluído";
                } else if ($key->is_cancelled == 1) {
                    $status = "Cancelado";
                }

                $line[0] = $key->code;
                $line[1] = $key->sac_authorized_code;
                $line[2] = $key->name;
                $line[3] = $key->address;
                $line[4] = $key->zipcode;
                $line[5] = date('d-m-Y H:i', strtotime($key->created_at));
                $line[6] = $status;

                array_push($rows, $line);

            }

            return Excel::download(new DefaultExport($heading, $rows), 'ObExport-'. date('Y-m-d') .'.xlsx');

        } else {

            if (!empty($request->input('ob'))) {
                $request->session()->put('sacf_ob', $request->input('ob'));
            } else {
                $request->session()->forget('sacf_ob');
            }

            if (!empty($request->input('authorized'))) {
                $request->session()->put('sacf_authorized', $request->input('authorized'));
            } else {
                $request->session()->forget('sacf_authorized');
            }

            if (!empty($request->input('status'))) {
                $request->session()->put('sacf_status', $request->input('status'));
            } else {
                $request->session()->forget('sacf_status');
            }

            if (!empty($request->input('type_line'))) {
                $request->session()->put('sacf_type_line', $request->input('type_line'));
            } else {
                $request->session()->forget('sacf_type_line');
                if($request->session()->get('filter_line') == 2) {
                    $request->session()->put('sacf_type_line', 2);
                }
            }

            $ob = DB::table('sac_buy_part')
                ->leftjoin('sac_authorized', 'sac_buy_part.authorized_id', '=', 'sac_authorized.id')
                ->select('sac_buy_part.*', 'sac_authorized.name')
                ->orderBy('id', 'DESC');

            if (!empty($request->session()->get('sacf_ob'))) {
                $ob->where('sac_buy_part.code', $request->session()->get('sacf_ob'));
            }
            if (!empty($request->session()->get('sacf_authorized'))) {
                $ob->where('authorized_id', $request->session()->get('sacf_authorized'));
            }
            if (!empty($request->session()->get('sacf_status'))) {
                if ($request->session()->get('sacf_status') == 1) {
                    $ob->where('sac_buy_part.status', 1)
                        ->where('sac_buy_part.is_cancelled', 0);
                } else if ($request->session()->get('sacf_status') == 2) {
                    $ob->where('sac_buy_part.status', 2)
                        ->where('sac_buy_part.is_cancelled', 0);
                } else if ($request->session()->get('sacf_status') == 3) {
                    $ob->where('sac_buy_part.status', 3)
                        ->where('sac_buy_part.is_cancelled', 0);
                } else if ($request->session()->get('sacf_status') == 4) {
                    $ob->where('sac_buy_part.status', 4)
                        ->where('sac_buy_part.is_cancelled', 0);
                } else if ($request->session()->get('sacf_status') == 5) {
                    $ob->where('sac_buy_part.is_cancelled', 1);
                }
            }

            if (!empty($request->session()->get('sacf_type_line'))) {

                if ($request->session()->get('sacf_type_line') == 1) {
                    $ob->leftJoin('sac_buy_parts', 'sac_buy_part.id', '=', 'sac_buy_parts.sac_buy_part_id')
                        ->leftJoin('product_air', 'sac_buy_parts.model', '=', 'product_air.id')
                        ->where('product_air.residential', 1)
                        ->where('sac_buy_parts.not_part', 0);

                } else if($request->session()->get('sacf_type_line') == 2) {
                    $ob->leftJoin('sac_buy_parts', 'sac_buy_part.id', '=', 'sac_buy_parts.sac_buy_part_id')
                        ->leftJoin('product_air', 'sac_buy_parts.model', '=', 'product_air.id')
                        ->where('product_air.commercial', 1)
                        ->where('sac_buy_parts.not_part', 0);

                } else if($request->session()->get('sacf_type_line') == 3) {
                    $ob->leftJoin('sac_buy_parts', 'sac_buy_part.id', '=', 'sac_buy_parts.sac_buy_part_id')
                        ->leftJoin('product_air', 'sac_buy_parts.model', '=', 'product_air.id')
                        ->where('sac_buy_parts.not_part', 1);
                }
            }

            return view('gree_i.sac.warranty_ob', [
                'ob' => $ob->paginate(10),
            ]);
        }
    }

    public function sacWarrantyOb_do(Request $request) {

        $ob = SacBuyPart::find($request->id);

        if ($ob) {

            if ($request->status != 99) {
                $ob->status = $request->status;
            }
            $ob->track_code = $request->track_code;
            $ob->is_cancelled = $request->status == 99 ? 1 : 0;
            $ob->shipping_cost = $request->shipping_cost;
            $ob->total = $request->total;
            $ob->save();

            $authorized = SacAuthorized::find($ob->authorized_id);

            if ($authorized->email) {

                if ($authorized->email_copy)
                    $copy = [$authorized->email_copy];
                else
                    $copy = [];

                $pattern = array(
                    'title' => 'ATUALIZAÇÃO DE ORDEM DE COMPRA',
                    'description' => nl2br("Olá! Foi realizado uma atualização no seu pedido de compra \n\n veja mais informações no link abaixo: \n\n <a href='". $request->root() ."/autorizada/lista/ob'>". $request->root() ."/autorizada/lista/ob</a>"),
                    'copys' => $copy,
                    'template' => 'misc.DefaultExternal',
                    'subject' => 'Ordem de compra: '. $ob->code .' atualização!',
                );

                SendMailJob::dispatch($pattern, $authorized->email);
            }

            LogSystem("Colaborador atualizou as informações do pedido de compra de peças. identificado por #". $ob->code, $request->session()->get('r_code'));
            $request->session()->put('success', 'Pedido de compra foi atualizado com sucesso!');
            return redirect('/sac/warranty/ob');

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function sacWarrantyPartsOb(Request $request, $id) {

        $parts = SacBuyParts::leftjoin('product_air', 'sac_buy_parts.model', 'product_air.id')
            ->select('product_air.*', 'sac_buy_parts.sac_buy_part_id', 'sac_buy_parts.not_part', 'sac_buy_parts.model as sac_buy_parts_model', 'sac_buy_parts.part', 'sac_buy_parts.id as sac_buy_parts_id', 'sac_buy_parts.quantity', 'sac_buy_parts.description', 'sac_buy_parts.image')
            ->where('sac_buy_parts.sac_buy_part_id', $id)
            ->get();

        $sacbuypart = SacBuyPart::find($id);

        return view('gree_i.sac.warranty_ob_part', [
            'id' => $id,
            'parts' => $parts,
            'optional' => $sacbuypart->optional
        ]);

    }

    public function sacWarrantyPartsOb_do(Request $request) {

        $group = $request->group;

        if ($group) {
            if (count($group) > 0) {

                for ($i = 0; $i < count($group); $i++) {

                    if (isset($group[$i]['model']) and isset($group[$i]['part'])) {

                        if ($group[$i]['item_id'] != 0 and $group[$i]['item_id'] != "") {
                            $obp = SacBuyParts::where('id', $group[$i]['item_id'])->first();
                        } else {
                            $obp = new SacBuyParts;
                        }

                        $obpa = SacBuyPart::where('id', $request->id)->first();
                        $obpa->optional = $request->optional;
                        $obpa->save();

                        $pic = "";
                        if (isset($group[$i]['picture'])) {
                            $response = $this->uploadS3($i, $group[$i]['picture'], $request);
                            if ($response['success']) {
                                $pic = $response['url'];
                                $obp->image = $response['url'];
                            }
                        }

                        $obp->model = $group[$i]['model'];
                        $obp->part = $group[$i]['part'];
                        $qty = 1;
                        if ($group[$i]['quantity']) {
                            $qty = $group[$i]['quantity'];
                            $obp->quantity = $group[$i]['quantity'];
                        } else {
                            $obp->quantity = 1;
                        }

                        $obp->description = $group[$i]['description'];
                        $obp->not_part = 1;
                        $obp->sac_buy_part_id = $request->id;
                        $obp->save();

                    } else if (isset($group[$i]['models2']) and isset($group[$i]['parts2'])) {

                        if ($group[$i]['item_id'] != 0 and $group[$i]['item_id'] != "") {
                            $obp = SacBuyParts::where('id', $group[$i]['item_id'])->first();
                        } else {
                            $obp = new SacBuyParts;
                        }

                        $obpa = SacBuyPart::where('id', $request->id)->first();
                        $obpa->optional = $request->optional;
                        $obpa->save();

                        $pic = "";
                        if (isset($group[$i]['picture'])) {
                            $response = $this->uploadS3($i, $group[$i]['picture'], $request);
                            if ($response['success']) {
                                $pic = $response['url'];
                                $obp->image = $response['url'];
                            }
                        }

                        $obp->model = $group[$i]['models2'];
                        $obp->part = $group[$i]['parts2'];
                        $qty = 1;
                        if ($group[$i]['quantity']) {
                            $qty = $group[$i]['quantity'];
                            $obp->quantity = $group[$i]['quantity'];
                        } else {
                            $obp->quantity = 1;
                        }
                        $obp->description = $group[$i]['description'];
                        $obp->not_part = 0;
                        $obp->sac_buy_part_id = $request->id;
                        $obp->save();
                    }
                }

                $request->session()->put('success', "Peças adicionadas com sucesso.");
                return redirect('/sac/warranty/ob');
            } else {
                $request->session()->put('error', "Você não adicionou nenhuma peça a sua solicitação.");
                return redirect('/sac/warranty/ob');
            }

        } else {

            $request->session()->put('error', "Você não adicionou nenhuma peça a sua solicitação.");
            return redirect('/sac/warranty/ob');
        }
    }

    public function sacWarrantyPartsObDelete(Request $request, $id) {

        $part = SacBuyParts::find($id);

        if ($part) {

            LogSystem("Colaborador removeu a peça da ordem de compra: ". $part->sac_buy_part_id, $request->session()->get('r_code'));

            SacBuyParts::where('id', $id)->delete();

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function sacWarrantyOsRequestNf(Request $request) {

        $os = SacOsProtocol::find($request->id);

        if ($os) {

            $os->payment_nf_request = $os->payment_nf_request == 1 ? 0 : 1;
            $os->save();

            $request->session()->put('success', 'Atualização na ordem de serviço realizada com sucesso!');
            return redirect()->back();

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function sacWarrantyOsPaymentStatus(Request $request) {
        $os = SacOsProtocol::find($request->id);

        if ($os) {

            if ($request->status == 0) {

                $os->payment_nf_request = 0;
                $os->request_tec_approv = 0;
                if (!empty($request->description)) {
                    $protocol = SacProtocol::find($os->sac_protocol_id);
                    if ($protocol) {
                        $protocol->paid_info = $request->description;
                        $protocol->save();
                    }
                }
                $os->save();

            } else if ($request->status == 1) {

                $os->payment_nf_request = 0;
                $os->request_tec_approv = 1;
                if (!empty($request->description)) {
                    $protocol = SacProtocol::find($os->sac_protocol_id);
                    if ($protocol) {
                        $protocol->paid_info = $request->description;
                        $protocol->save();
                    }
                }
                $os->save();

            } else if ($request->status == 2) {

                $os->payment_nf_request = 1;
                $os->request_tec_approv = 1;
                if (!empty($request->description)) {
                    $protocol = SacProtocol::find($os->sac_protocol_id);
                    if ($protocol) {
                        $protocol->paid_info = $request->description;
                        $protocol->save();
                    }
                }
                $os->save();

            }

            LogSystem("Colaborador atualizou os status na hora do pagamento, atualizou os status da O.S identificado por #". $os->code, $request->session()->get('r_code'));
            $request->session()->put('success', 'O.S foi atualizada com sucesso!');
            return redirect('/sac/warranty/os/paid');

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function sacWarrantyOsPayment(Request $request) {

        $os = SacOsProtocol::find($request->id);

        if ($os) {

            $authorized = SacAuthorized::find($os->authorized_id);

            if ($authorized->agency and $authorized->account and $authorized->bank) {

                if($request->is_payment_request == 1){
                    //Executar codigo de pagamento

                    $nf_url = "";
                    if ($request->hasFile('payment_nf')) {
                        $response = $this->uploadS3(1, $request->payment_nf, $request);
                        if ($response['success']) {
                            $nf_url = $response['url'];
                        } else {
                            return redirect()->back();
                        }
                    }

                    // $r_payment = new FinancyRPayment;

                    // $r_payment->request_r_code = $request->session()->get('r_code');
                    // $r_payment->nf_nmb = $request->payment_number_nf;
                    // $r_payment->request_category = 1;
                    // $r_payment->description = 'PAGAMENTO DE ORDEM DE SERVIÇO PARA AUTORIZADA/CREDÊNCIADA';
                    // $r_payment->has_analyze = 1;
                    //if ($authorized->id == 1864) {
                    // $r_payment->recipient = $request->full_name;
                    //} else {
                    // $r_payment->recipient = $authorized->name;
                    //}
                    // $r_payment->recipient = $authorized->name;
                    // $r_payment->recipient_r_code = "";

                    // $r_payment->amount_gross = $request->total;

                    // $r_payment->due_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + 8 day'));
                    // $r_payment->amount_liquid = $request->total;
                    // $r_payment->p_method = 2;
                    // $r_payment->optional = $request->option;

                    //if ($authorized->id == 1864) {
                    // $r_payment->agency = $request->agency;
                    // $r_payment->account = $request->account;
                    // $r_payment->bank = $request->bank;
                    // $r_payment->identity = $request->identity;
                    // $r_payment->cnpj = $request->identity;
                    //} else {
                    // $r_payment->agency = $authorized->agency;
                    // $r_payment->account = $authorized->account;
                    // $r_payment->bank = $authorized->bank;
                    // $r_payment->identity = $authorized->identity;
                    // $r_payment->cnpj = $authorized->identity;
                    //}
                    // $r_payment->module_id = $os->id;
                    // $r_payment->module_type = 3;
                    // $r_payment->save();

                    // // GEN CODE SEGMENT
                    // codeSegmentBase($r_payment->id, 3, $request);

                    // $attach = new FinancyRPaymentAttach;
                    // $attach->name = 'Nota_fiscal';
                    // $attach->size = 500;
                    // $attach->financy_r_payment_id = $r_payment->id;
                    // $attach->url = $nf_url;
                    // $attach->save();

                    // $attach = new FinancyRPaymentAttach;
                    // $attach->name = 'ordem_de_servico';
                    // $attach->size = 500;
                    // $attach->financy_r_payment_id = $r_payment->id;
                    // $attach->url = $request->root() .'/sac/warranty/os/print/'. $os->id;
                    // $attach->save();


                    $os->total = $request->total;
					$os->total_extra = $request->total_extra;
					$os->total_gas = $request->total_gas;
                    $os->is_payment_request = $request->is_payment_request;
                    $os->payment_number_nf = $request->payment_number_nf;
                    $os->payment_nf = $nf_url;
                    $os->is_paid = 1;
                    $os->save();

                    // $immediate = UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
                    //                             ->select('users.*', 'user_immediate.*')
                    //                             ->where('user_r_code', $request->session()->get('r_code'))
                    //                             ->get();

                    // $r_user = Users::where('r_code', $request->session()->get('r_code'))->first();

                    // $pattern = array(
                    //     'id' => $r_payment->id,
                    //     'immediates' => $immediate,
                    //     'title' => 'PEDIDO FOI REALIZADO',
                    //     'description' => '',
                    //     'template' => 'payment.Success',
                    //     'subject' => 'Pedido de pagamento: #'. $r_payment->id,
                    // );

                    // SendMailJob::dispatch($pattern, $r_user->email);


                    //     if (count($immediate) > 0) {

                    //         $pattern = array(
                    //             'id' => $r_payment->id,
                    //             'sector_id' => $r_user->sector_id,
                    //             'r_code' => $r_user->r_code,
                    //             'content' => $r_payment->description,
                    //             'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                    //             'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                    //             'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                    //             'method' => $r_payment->p_method,
                    //             'optional' => $r_payment->optional,
                    //             'title' => 'APROVAÇÃO DE PAGAMENTO',
                    //             'description' => '',
                    //             'template' => 'payment.Analyze',
                    //             'subject' => 'APROVAÇÃO DE PAGAMENTO',
                    //         );

                    //         foreach ($immediate as $key) {

                    //             $imdt = Users::where('r_code', $key->immediate_r_code)->first();

                    //             // send email
                    //             SendMailJob::dispatch($pattern, $imdt->email);

                    //             App::setLocale($imdt->lang);
                    //             NotifyUser(__('layout_i.n_payment_001_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_payment_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]), $request->root() .'/financy/payment/request/approv/'. $r_payment->id);
                    //             App::setLocale($request->session()->get('lang'));

                    //         }

                    //     }

                }else{
                    $os->total = $request->total;
                    $os->is_payment_request = $request->is_payment_request;
                    $os->is_paid = 1;
                    $os->save();
                }



                // LogSystem("Colaborador enviou solicitação de pagamento para análise. identificado por #". $r_payment->id, $request->session()->get('r_code'));

                LogSystem("Colaborador mudou o status da OS para pago. identificado por #". $os->code, $request->session()->get('r_code'));
                $request->session()->put('success', 'Nova solicitação de pagamento realizada e enviada para análise.');
                return redirect('/sac/warranty/os/paid');

            } else {
                $request->session()->put('error', 'Autorizada não cadastrou dados bancários');
                return redirect('/sac/warranty/os/paid');
            }

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function sacWarrantyOsPrint(Request $request, $id) {

        $os = SacOsProtocol::find($id);

        if ($os) {

            $protocol = SacProtocol::find($os->sac_protocol_id);

            $authorized = SacAuthorized::find($os->authorized_id);
            $client = SacClient::find($protocol->client_id);

            $sac_models = SacModelOs::leftjoin('product_air', 'sac_model_os.product_id', '=', 'product_air.id')
                ->select('product_air.*', 'sac_model_os.serial_number as smp_serial_number')
                ->where('sac_model_os.sac_os_protocol_id', $os->id)
                ->get();

            $parts = SacPartProtocol::leftjoin('parts', 'sac_part_protocol.part_id', '=', 'parts.id')
                ->select('sac_part_protocol.*', 'parts.description as parts_description', 'parts.code')
                ->where('sac_protocol_id', $os->sac_protocol_id)
				->where('sac_os_protocol_id', $os->id)
                ->where('sac_part_protocol.is_approv', 1)
                ->orWhere(function ($query) use ($os) {
                    $query->where('sac_protocol_id', $os->sac_protocol_id)
						->where('sac_os_protocol_id', $os->id)
                        ->where('sac_part_protocol.is_approv', 0)
                        ->where('sac_part_protocol.is_repprov', 0);
                })
                ->get();

            return view('gree_i.sac.warranty_os_print', [
                'parts' => $parts,
                'os' => $os,
                'protocol' => $protocol,
                'authorized' => $authorized,
                'client' => $client,
                'sac_models' => $sac_models,
            ]);

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function sacWarrantyOsStatus(Request $request, $status, $id) {

        $os = SacOsProtocol::find($id);

        if ($os) {

            if($status == 2){
                LogSystem("Colaborador alterou a ordem de serviço para pago!". $os->id, $request->session()->get('r_code'));
                $request->session()->put('success', "Ordem de serviço foi alterada pra pago com sucesso!");

                $os->is_paid = 1;
                $os->save();

            }else if($status == 1){

                if ($os->is_cancelled == 1) {
                    LogSystem("Colaborador reabriu Ordem de serviço: ". $os->id, $request->session()->get('r_code'));
                    $request->session()->put('success', "Ordem de serviço reaberta com sucesso!");
                } else {
                    LogSystem("Colaborador cancelou Ordem de serviço: ". $os->id, $request->session()->get('r_code'));
                    $request->session()->put('success', "Ordem de serviço cancelada com sucesso!");
                }

                $os->is_cancelled = $os->is_cancelled == 1 ? 0 : 1;
                $os->save();
            }

            return redirect()->back();

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function sacWarrantyOsAnalyze(Request $request) {

        $os = SacOsProtocol::find($request->id);
        if ($os) {

            LogSystem("Colaborador análisou antes do envio das peças O.S: #". $os->code, $request->session()->get('r_code'));
            if (!$os->observation) {
                $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                    ->select('users.*')
                    ->where('user_on_permissions.can_approv', 1)
                    ->where('user_on_permissions.perm_id', 16)
                    ->get();

                if (count($assist_tect) > 0) {

                    foreach($assist_tect as $key) {
                        NotifyUser('Análise da O.S #'. $os->code, $key->r_code, 'fa-exclamation', 'text-info', 'Foi realizado análise técnica referente ao pedido de peças, aguardando aprovação.', $request->root() .'/sac/warranty/os/all');
                    }

                }
            }

            $os->observation = $request->description;
            $os->has_service = $request->has_service == 1 ? 1 : 0;
            $os->has_print = $request->has_print == 1 ? 1 : 0;
            $os->has_pending_payment = $request->has_pending_payment == 1 ? 1 : 0;
            if ($request->status == 1) {
                $os->has_split = 1;
				$os->split_export_date = date('Y-m-d H:i:s');
                $os->expedition_invoice = 0;
            } else if ($request->status == 2) {
                $os->has_split = 0;
				$os->split_export_date = null;
                $os->expedition_invoice = 1;
            } else if ($request->status == 3) {
                $os->has_split = 0;
				$os->split_export_date = null;
                $os->expedition_invoice = 0;
            } else if ($request->status == 4) {
                $os->has_analyze_part = 1;
                $os->has_split = 0;
				$os->split_export_date = null;
                $os->expedition_invoice = 0;
            }
            $os->save();

            $request->session()->put('success', "O.S foi atualizada com a sua nova análise.");
            return redirect()->back();

        } else {
            $request->session()->put('error', "O.S não foi encontrada...");
            return redirect()->back();
        }
    }

    public function sacOsAnalyzeanalyzeTechnique(Request $request){

        $model = new \App\Model\SacOsAnalyze();

        $model->sac_os_protocol_id = $request->id_;
        $model->description = nl2br($request->description_);
        $model->r_code = $request->session()->get('r_code');

        $model->save();

        LogSystem("Colaborador analisou antes do envio das peças O.S: #". $request->id_, $request->session()->get('r_code'));
        $assist_tect = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
            ->select('users.*')
            ->where('user_on_permissions.can_approv', 1)
            ->where('user_on_permissions.perm_id', 16)
            ->get();

        if (count($assist_tect) > 0) {

            foreach($assist_tect as $key) {
                NotifyUser('Análise da O.S #'. $request->id_, $request->session()->get('r_code'), 'fa-exclamation', 'text-info', 'Foi realizado análise técnica referente ao pedido de peças, aguardando aprovação.', $request->root() .'/sac/warranty/os/all');
            }
        }

        $userName = Users::where('r_code', $request->session()->get('r_code'))->first();

        return response()->json([
            'name' => $userName->short_name,
            'description' => $model->description,
            'created_at' => $model->date_formmat,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function sacWarrantyOsSplit(Request $request) {

        if (count($request->check) > 0) {

            foreach ($request->check as $key) {
                $item = SacOsProtocol::where('id', $key)->where('has_split', 0)->first();
                if ($item) {

                    $item->has_split = 1;
					$item->split_export_date = date('Y-m-d H:i:s');
                    $item->save();

                    LogSystem("Colaborador enviou para separação OS: #". $item->code, $request->session()->get('r_code'));
                } else {
                    $request->session()->put('error', 'A O.S desejada foi excluido ou já foi enviado para separação');
                    return redirect()->back();
                }
            }

        } else {

            $request->session()->put('error', 'Você precisa selecionar ao menos 1 O.S para realizar essa ação.');
            return redirect()->back();
        }

        $request->session()->put('success', "O.S foram enviadas para a separação.");
        return redirect()->back();
    }

    public function sacWarrantyOsSplitExport(Request $request) {

        if (count($request->check) > 0) {

            $heading = array('OS', 'Data da reclamação', 'Data da emissão', 'Código da credenciada', 'Nome da credenciada', 'Código de peça', 'Descrição da peça', 'Quantidade', 'Modelo do equipamento', 'Número de série');
            $rows = array();

            foreach ($request->check as $key) {
                $line = array();

                $item = SacPartProtocol::with(['SacOsProtocol'=> function ($q) {
                    $q->where('is_cancelled', 0)->where('is_paid', 0);
                }, 'SacOsProtocol.authorizedOs', 'SacOsProtocol.modelProtocol', 'modelParts', 'SacProductAir', 'SacProtocol'])
                    ->SacOSProtocolFilter($key)
					->where('is_approv', 1)
                    ->get();

                if (count($item) > 0) {
                    foreach($item as $iten) {

                        $item = SacOsProtocol::with('authorizedOs')->find($key);
                        $item->expedition_invoice = 1;
                        $item->save();

                        if ($iten->is_repprov == 1) {
                            continue;
                        }

                        if (!$iten->SacOsProtocol)
                            return redirect()->back()->with('error', 'Alguma OS envolvida nessa exportação, não está disponível para essa operação.');

                        $line[0] = $item->code;
                        $line[1] = date('d-m-Y', strtotime($iten->SacProtocol->created_at));
                        $line[2] = date('d-m-Y', strtotime($item->created_at));
                        $line[3] = $item->authorizedOs->code;
                        $line[4] = $item->authorizedOs->name;
                        $line[5] = $iten->modelParts->code;
                        $line[6] = $iten->modelParts->description;
                        $line[7] = $iten->quantity;
                        $line[8] = $iten->SacProductAir ? $iten->SacProductAir->model : '';

                        if ($iten->SacOsProtocol->modelProtocol)
                            $line[9] = $iten->SacOsProtocol->modelProtocol->serial_number;
                        else
                            $line[9] = '';

                        array_push($rows, $line);
                    }

                } else {
                    $request->session()->put('error', 'Não há peças envolvidas na O.S selecionada.');
                    return redirect()->back();
                }

            }

            return Excel::download(new DefaultExport($heading, $rows), 'OsExportSplit-'. date('Y-m-d') .'.xlsx');
        } else {

            $request->session()->put('error', 'Você precisa selecionar ao menos 1 O.S para realizar essa ação.');
            return redirect()->back();
        }

    }

    public function sacWarrantyOsAll(Request $request) {

        if (!empty($request->input('serial_number'))) {
            $request->session()->put('sacf_serial_number', $request->input('serial_number'));
        } else {
            $request->session()->forget('sacf_serial_number');
        }
        if (!empty($request->input('code'))) {
            $request->session()->put('sacf_code', $request->input('code'));
        } else {
            $request->session()->forget('sacf_code');
        }
        if (!empty($request->input('os'))) {
            $request->session()->put('sacf_os', $request->input('os'));
        } else {
            $request->session()->forget('sacf_os');
        }
        if (!empty($request->input('origin'))) {
            $request->session()->put('sacf_origin', $request->input('origin'));
        } else {
            $request->session()->forget('sacf_origin');
        }
        if (!empty($request->input('start_date'))) {
            $request->session()->put('sacf_start_date', $request->input('start_date'));
        } else {
            $request->session()->forget('sacf_start_date');
        }
        if (!empty($request->input('end_date'))) {
            $request->session()->put('sacf_end_date', $request->input('end_date'));
        } else {
            $request->session()->forget('sacf_end_date');
        }
        if (!empty($request->input('authorized'))) {
            $request->session()->put('sacf_authorized', $request->input('authorized'));
        } else {
            $request->session()->forget('sacf_authorized');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }
        if (!empty($request->input('see_part'))) {
            $request->session()->put('sacf_see_part', $request->input('see_part'));
        } else {
            $request->session()->forget('sacf_see_part');
        }
        if (!empty($request->input('all_msg'))) {
            $request->session()->put('sacf_all_msg', $request->input('all_msg'));
        } else {
            $request->session()->forget('sacf_all_msg');
        }
		if (!empty($request->input('warranty_extend'))) {
            $request->session()->put('sacf_warranty_extend', $request->input('warranty_extend'));
        } else {
            $request->session()->forget('sacf_warranty_extend');
        }
		if (!empty($request->input('gree_os'))) {
            $request->session()->put('sacf_gree_os', $request->input('gree_os'));
        } else {
            $request->session()->forget('sacf_gree_os');
        }
		if (!empty($request->input('authorization_install'))) {
            $request->session()->put('sacf_authorization_install', $request->input('authorization_install'));
        } else {
            $request->session()->forget('sacf_authorization_install');
        }
        if (!empty($request->input('type_line'))) {
            $request->session()->put('sacf_type_line', $request->input('type_line'));
        } else {
            $request->session()->forget('sacf_type_line');
            if($request->session()->get('filter_line') == 2){
                $request->session()->put('sacf_type_line', 2);
            }
        }
        if (!empty($request->input('left_5'))) {
            $request->session()->put('sacf_left_5', $request->input('left_5'));
        } else {
            $request->session()->forget('sacf_left_5');
        }

        if (!empty($request->input('left_15'))) {
            $request->session()->put('sacf_left_15', $request->input('left_15'));
        } else {
            $request->session()->forget('sacf_left_15');
        }

        if (!empty($request->input('left_30'))) {
            $request->session()->put('sacf_left_30', $request->input('left_30'));
        } else {
            $request->session()->forget('sacf_left_30');
        }

        $os = SacOsProtocol::with(
			'modelOs.sacPartProtocol.modelParts', 
			'modelOs.sacProductAir',
			'authorizedOs', 
			'sacProtocol.sacpartprotocol.modelParts', 
			'sacProtocol.sacModelProtocol.sacProductAir', 
			'osMsgsOne', 
			'sacOsAnalyze.sacUsers', 
			'sacProtocol.SacProblemCategory',
			'sacPartProtocol.modelParts',
			'sacPartProtocol.SacProductAir',
		)
            ->orderByRaw('ISNULL(sac_os_protocol.code) DESC, sac_os_protocol.code DESC');

        if (!empty($request->session()->get('sacf_serial_number'))) {
            $os->SacModelOsFilter($request->session()->get('sacf_serial_number'));
        }
        if (!empty($request->session()->get('sacf_start_date'))) {
            $os->where('created_at', '>=', $request->session()->get('sacf_start_date'));
        }
        if (!empty($request->session()->get('sacf_end_date'))) {
            $os->where('created_at', '<=', $request->session()->get('sacf_end_date').' 23:59:59');
        }
        if (!empty($request->session()->get('sacf_code'))) {
            $os->sacprotocolfilter('code', $request->session()->get('sacf_code'));
        }
        if (!empty($request->session()->get('sacf_os'))) {
            $os->where('code', $request->session()->get('sacf_os'));
        }

        if (!empty($request->session()->get('sacf_authorized'))) {
            $os->where('authorized_id', $request->session()->get('sacf_authorized'));
        }

        if (!empty($request->session()->get('sacf_all_msg'))) {
            $os->where('is_cancelled', 0)->where('is_paid', 0)->OnlyMsgNotReadFilter();
        }

        if ($request->session()->get('sacf_see_part') == 1 and empty($request->session()->get('sacf_all_msg'))) {
            $os->sacprotocolfilter('sacf_see_part');
        } else if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $os->where('is_cancelled', 0)
                    ->where('expedition_invoice', 0)
                    ->where('is_paid', 0)
                    ->where('has_split', 0)
                    ->sacprotocolfilter(1);
            } else if ($request->session()->get('sacf_status') == 2) {
                $os->whereDoesntHave('sacOsAnalyze')
                    ->where('has_split', 0)
                    ->where('is_cancelled', 0)
                    ->where('is_paid', 0)
                    ->sacprotocolfilter(2);
            } else if ($request->session()->get('sacf_status') == 3) {
                $os->whereHas('sacOsAnalyze')
                    ->where('is_cancelled', 0)
                    ->where('is_paid', 0)
                    ->where('has_split', 0)
                    ->where('has_analyze_part', 1)
                    ->sacprotocolfilter(3);
            } else if ($request->session()->get('sacf_status') == 4) {
                $os->where('has_split', 1)
                    ->where('is_cancelled', 0)
                    ->where('is_paid', 0)
                    ->where('expedition_invoice', 0);
            } else if ($request->session()->get('sacf_status') == 5) {
                $os->where('has_service', 1);
            } else if ($request->session()->get('sacf_status') == 6) {
                $os->where('is_cancelled', 1);
            } else if ($request->session()->get('sacf_status') == 7) {
                $os->where('is_paid', 1);
            } else if ($request->session()->get('sacf_status') == 8) {
                $os->where('expedition_invoice', 1)
                    ->where('is_cancelled', 0)
                    ->where('is_paid', 0);
            } else if ($request->session()->get('sacf_status') == 10) {
                $os->whereHas('sacOsAnalyze')
                    ->where('is_cancelled', 0)
                    ->where('is_paid', 0)
                    ->where('has_split', 0)
					->where('has_pending_payment', 0)
					->where('expedition_invoice', 0);
				
            } else if ($request->session()->get('sacf_status') == 11) {

                $os->where('is_cancelled', 0)
                    ->where('is_paid', 0)
                    ->where('has_pending_payment', 1);

            } else if ($request->session()->get('sacf_status') == 12) {

                $os->whereHas('sacProtocol', function($q) {
                    $q->whereDoesntHave('sacpartprotocol');
                })
                    ->where('is_cancelled', 0)
                    ->where('has_pending_payment', 0)
                    ->where('is_paid', 0);

            } else if ($request->session()->get('sacf_status') == 14) {
                $os->whereHas('modelOs.sacPartProtocol', function($q) {
                    $q->where('is_invoice', 1);
                });
            } else if ($request->session()->get('sacf_status') == 15) {
                $os->whereHas('modelOs.sacPartProtocol', function($q) {
                    $q->where('is_invoice', 0);
                });
            }
			
        }

        if ($request->session()->get('sacf_origin')) {

            $os->where('has_pending_payment', 0)
                ->where('is_cancelled', 0)
                ->where('is_paid', 0)
                ->whereHas('sacProtocol', function($q) use ($request) {
                    $q->where('origin', $request->session()->get('sacf_origin'));
                });
        }
		
		if ($request->session()->get('sacf_warranty_extend')) {

            $os->where('is_cancelled', 0)
				->where('is_paid', 0)
				->whereHas('sacProtocol', function($q) {
					$q->where('warranty_extend', 1);
				});
        }
		if ($request->session()->get('sacf_gree_os')) {

            $os->where('is_cancelled', 0)
				->where('is_paid', 0)
				->where('authorized_id', 1864)
				->where('has_pending_payment', 0);
        }
		if ($request->session()->get('sacf_authorization_install')) {

            $os->where('is_cancelled', 0)
				->where('is_paid', 0)
				->whereHas('sacProtocol', function($q) {
					$q->where('type', 9);
				});
        }
		

        if (!empty($request->session()->get('sacf_type_line'))) {

            if($request->session()->get('sacf_type_line') == 1) {
                $os->sacprotocolfilter('sacf_type_line', 1);

            }
            else {
                $os->sacprotocolfilter('sacf_type_line', 2);

            }
        }

        if (!empty($request->session()->get('sacf_left_5'))) {
            $os->where('created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 5 day')))
                ->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 14 day')))
                ->where('has_pending_payment', 0)
                ->where('is_cancelled', 0)
                ->where('is_paid', 0);
        }

        if (!empty($request->session()->get('sacf_left_15'))) {
            $os->where('created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 15 day')))
                ->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 29 day')))
                ->where('has_pending_payment', 0)
                ->where('is_cancelled', 0)
                ->where('is_paid', 0);
        }

        if (!empty($request->session()->get('sacf_left_30'))) {
            $os->where('created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 29 day')))
                ->where('has_pending_payment', 0)
                ->where('is_cancelled', 0)
                ->where('is_paid', 0);
        }

        $total_5 = SacOsProtocol::where('created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 5 day')))
            ->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 14 day')))
            ->where('has_pending_payment', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0);

        $total_15 = SacOsProtocol::where('created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 15 day')))
            ->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 29 day')))
            ->where('has_pending_payment', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0);

        $total_30 = SacOsProtocol::where('created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 29 day')))
            ->where('has_pending_payment', 0)
            ->where('is_cancelled', 0)
            ->where('is_paid', 0);

        if (!empty($request->session()->get('sacf_type_line'))) {

            if($request->session()->get('sacf_type_line') == 1) {
                $total_5->sacprotocolfilter('sacf_type_line', 1);
                $total_15->sacprotocolfilter('sacf_type_line', 1);
                $total_30->sacprotocolfilter('sacf_type_line', 1);

            }
            else {
                $total_5->sacprotocolfilter('sacf_type_line', 2);
                $total_15->sacprotocolfilter('sacf_type_line', 2);
                $total_30->sacprotocolfilter('sacf_type_line', 2);

            }
        }

        if ($request->export_external == 1) {

			$request->merge(['e_r_code' => $request->session()->get('r_code')]);
            $response = $this->fileManagerSVR(
				$request->all(),
				'/api/v1/sac/os/export/general'
			);

			if (!$response->success)
            	return redirect()->back()->with('error', $response->msg);

			return redirect('/sac/warranty/os/all')->with('success','Você receberá o Excel em seu email cadastrado no seu perfil e também no alerta do seu painel.');
        }

        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 3 or $request->session()->get('sacf_status') == 4) {

                return view('gree_i.sac.warranty_os_all', [
                    'os' => $os->paginate(100),
                    'total_5' => $total_5->count(),
                    'total_15' => $total_15->count(),
                    'total_30' => $total_30->count()
                ]);
            } else {

                return view('gree_i.sac.warranty_os_all', [
                    'os' => $os->paginate(10),
                    'total_5' => $total_5->count(),
                    'total_15' => $total_15->count(),
                    'total_30' => $total_30->count()
                ]);
            }
        } else {

            return view('gree_i.sac.warranty_os_all', [
                'os' => $os->paginate(10),
                'total_5' => $total_5->count(),
                'total_15' => $total_15->count(),
                'total_30' => $total_30->count()
            ]);
        }
    }

    public function sacWarrantyOsAllAjax(Request $request) {

        $os = SacOsProtocol::with('sacProtocol', 'modelOs.sacPartProtocol', 'modelOs.sacProductAir.productSubLevel1.productSubLevel2')->find($request->id);

        $modelos = [];
        $expedition = [];

        foreach ($os->modelOs as $value) {

            if($value->sacProductAir != null) {

                $add = array();
                $add['serial_number'] = $value->serial_number;
                $add['model'] = $value->sacProductAir->model;

				$segment = "";
                if($value->sacProductAir->productSubLevel1) {
                    $segment = $value->sacProductAir->productSubLevel1->name;
                }
                if($value->sacProductAir->productSubLevel1) {
                    if ($value->sacProductAir->productSubLevel1->productSubLevel2) {
                        $segment = $value->sacProductAir->productSubLevel1->productSubLevel2->name;
                    }
                }

                $add['segment'] = $segment;
                $add['product_id'] = $value->product_id;
                $add['parts'] = [];

                foreach ($value->sacPartProtocol as $key) {

                    if($value->product_id == $key->product_id) {

                        if($key->modelParts) {
                            $part = array();
                            $part['code'] = $key->modelParts->code? $key->modelParts->code: '';
                            $part['description'] = $key->modelParts->description? $key->modelParts->description: '';
                            $part['quantity'] = $key->quantity;
                            $part['motive'] = $key->description;
                            $part['sac_expedition_request_id'] = $key->sac_expedition_request_id;
                            $part['product_id'] = $key->product_id;
							$part['is_invoice'] = $key->is_invoice;

                            if($key->is_approv == 1) {
                                $part['status'] = 'Aprovado';
                            } elseif ($key->repprov == 1) {
                                $part['status'] = 'Reprovado';
                            } elseif ($key->is_approv == 0 && $key->is_repprov == 0) {
                                $part['status'] = 'Sem análise';
                            }
                            array_push($add['parts'], $part);
                        }
                    }

                    if($key->SacExpedition != null) {

                        if (!in_array($key->sac_expedition_request_id, array_column($expedition, 'id'))) {

                            $exped = array();

                            $exped['id'] = $key->SacExpedition['id'];
                            $exped['nf_number'] = $key->SacExpedition['nf_number'];
                            $exped['code_track'] = $key->SacExpedition['code_track'];
                            $exped['transport'] = $key->SacExpedition['transport'];
                            $exped['arrival_forecast'] = $key->SacExpedition['arrival_forecast'];
                            $exped['arrived_at'] = '--';
                            if($key->SacExpedition['is_completed'] == 1) {
                                $exped['arrived_at'] = date('d-m-Y', strtotime($key->SacExpedition->updated_at));
                            }
                            $exped['status'] = null;
                            if ($key->SacExpedition['is_completed'] == 1) {
                                $exped['status'] = 'Concluído';
                            } else {
                                $exped['status'] = 'A caminho';
                            }
                            $exped['total'] = number_format($key->SacExpedition['total'], 2, ',', '.');

                            array_push($expedition, $exped);
                        }
                    }
                }
                array_push($modelos, $add);
            }

        }

        return response()->json([
            'code' => $os->code,
            'motive_description' => $os->sacProtocol->description,
            'model_os' => $modelos,
            'expedition' => $expedition
        ], 200, [], JSON_PRETTY_PRINT);

    }

    public function sacWarrantyOsAllModelAjax(Request $request) {

        $os = SacModelOs::with(['sacOsProtocol.sacProtocol'])->where('serial_number', $request->serial_number)->paginate(10);
        $count = SacModelOs::with(['sacOsProtocol.sacProtocol'])->where('serial_number', $request->serial_number)->count();

        $all_os = [];
        foreach ($os as $key) {

            if($key->sacOsProtocol != null) {

                $add = array();
                $add['os_id'] = $key->sacOsProtocol->id;
                $add['os_code'] = $key->sacOsProtocol->code != null ? $key->sacOsProtocol->code: ' - ';
                $add['is_paid'] = $key->sacOsProtocol->is_paid;
                $add['protocol_id'] = $key->sacOsProtocol->sacProtocol->id;
                $add['protocol_code'] = $key->sacOsProtocol->sacProtocol->code;
                $add['is_paid'] = $key->sacOsProtocol->is_paid;
                $add['is_cancelled'] = $key->sacOsProtocol->is_cancelled;
                $add['is_completed'] = $key->sacOsProtocol->sacProtocol->is_completed;
                $add['sac_protocol_is_cancelled'] = $key->sacOsProtocol->sacProtocol->is_cancelled;
                $add['is_refund'] = $key->sacOsProtocol->sacProtocol->is_refund;

                $status = "";
                if ($key->sacOsProtocol->is_cancelled == 1) {
                    $status = '<span class="badge badge-light-danger">Cancelado</span>';
                } else if ($key->sacOsProtocol->is_paid == 1 && $key->sacOsProtocol->is_payment_request == 1 ) {
                    $status = '<span class="badge badge-light-success">Pago</span>';
                } else if ($key->sacOsProtocol->is_paid == 1 && $key->sacOsProtocol->is_payment_request == 0 ) {
                    $status = '<span class="badge badge-light-success">Concluido</span>';
                } else if ($key->sacOsProtocol->expedition_invoice == 1) {
                    $status = '<span class="badge badge-light-success">Exportado (Separação & Faturamento)</span>';
                } else if ($key->sacOsProtocol->has_split == 1) {
                    $status = '<span class="badge badge-light-warning">Enviado para separação</span>';
                } else if ($key->sacOsProtocol->is_approv == 1 and $key->sacOsProtocol->observation) {
                    $status = '<span class="badge badge-light-info">Aguardando envio P/ separação</span>';
                } else if (!$key->sacOsProtocol->observation) {
                    if ($key->sacOsProtocol->sacProtocol->has_part_analyze) {
                        $status = '<span class="badge badge-light-secondary">Aguardando análise</span>';
                    } else {
                        $status = '<span class="badge badge-light-warning">Em andamento</span>';
                    }
                } else if ($key->sacOsProtocol->observation) {
                    $status = '<span class="badge badge-light-warning">Em andamento</span>';
                } else {
                    $status = '<span class="badge badge-light-warning">Em andamento</span>';
                }
                $add['status'] = $status;

                array_push($all_os, $add);
            }
        }

        $os->appends($request->except('page'));
        $os->setPath('/sac/warranty/os/all/model/ajax');
        $paginate = '<nav aria-label="Page navigation" class="mt-2">';
        $paginate .= '<ul class="pagination justify-content-end">';
        $paginate .= $os->links('vendor.pagination.ajax',['html_render' => '']);
        $paginate .= '</ul>';
        $paginate .= '</nav>';

        return response()->json([
            'os' => $all_os,
            'count' => $count,
            'paginate' => $paginate,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function sacWarrantyApprov(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('sacf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('sacf_r_code');
        }
        if (!empty($request->input('client'))) {
            $request->session()->put('sacf_client', $request->input('client'));
        } else {
            $request->session()->forget('sacf_client');
        }
        if (!empty($request->input('authorized'))) {
            $request->session()->put('sacf_authorized', $request->input('authorized'));
        } else {
            $request->session()->forget('sacf_authorized');
        }
        if (!empty($request->input('code'))) {
            $request->session()->put('sacf_code', $request->input('code'));
        } else {
            $request->session()->forget('sacf_code');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }

        if (!empty($request->input('left_5'))) {
            $request->session()->put('sacf_left_5', $request->input('left_5'));
        } else {
            $request->session()->forget('sacf_left_5');
        }

        if (!empty($request->input('left_15'))) {
            $request->session()->put('sacf_left_15', $request->input('left_15'));
        } else {
            $request->session()->forget('sacf_left_15');
        }

        if (!empty($request->input('left_30'))) {
            $request->session()->put('sacf_left_30', $request->input('left_30'));
        } else {
            $request->session()->forget('sacf_left_30');
        }

        $protocol = DB::table('sac_protocol')
            ->leftJoin('sac_client','sac_protocol.client_id','=','sac_client.id')
            ->leftJoin('sac_authorized','sac_protocol.authorized_id','=','sac_authorized.id')
            ->leftJoin('sac_os_protocol','sac_protocol.id','=','sac_os_protocol.sac_protocol_id')
            ->leftJoin('sac_part_protocol','sac_protocol.id','=','sac_part_protocol.sac_protocol_id')
            ->select('sac_os_protocol.code as sac_os_protocol_code', 'sac_os_protocol.id as sac_os_protocol_id', 'sac_protocol.*', 'sac_client.name as sac_client_name', 'sac_client.id as sac_client_id', 'sac_authorized.name as sac_authorized_name', 'sac_authorized.id as sac_authorized_id')
            ->where('sac_part_protocol.is_approv', 0)
            ->where('sac_part_protocol.is_repprov', 0)
            ->where('sac_os_protocol.is_cancelled', 0)
            ->groupBy('sac_protocol.id')
            ->orderBy('sac_protocol.id', 'DESC');

        if (!empty($request->session()->get('sacf_r_code'))) {
            $protocol->where('sac_protocol.r_code', $request->session()->get('sacf_r_code'));
        }
        if (!empty($request->session()->get('sacf_code'))) {
            $protocol->where('sac_os_protocol.code', $request->session()->get('sacf_code'));
        }
        if (!empty($request->session()->get('sacf_authorized'))) {
            $protocol->where('sac_protocol.authorized_id', $request->session()->get('sacf_authorized'));
        }
        if (!empty($request->session()->get('sacf_client'))) {
            $protocol->where('sac_protocol.client_id', $request->session()->get('sacf_client'));
        }
        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $protocol->where('sac_protocol.in_wait', 1);
            } else if ($request->session()->get('sacf_status') == 2) {
                $protocol->where('sac_protocol.in_progress', 1);
            } else if ($request->session()->get('sacf_status') == 3) {
                $protocol->where('sac_protocol.is_completed', 1);
            } else if ($request->session()->get('sacf_status') == 4) {
                $protocol->where('sac_protocol.is_cancelled', 1);
            }
        }

        $userall = Users::all();

        return view('gree_i.assisttec.approv_part', [
            'userall' => $userall,
            'protocol' => $protocol->paginate(10),
        ]);
    }

    public function sacConfig(Request $request) {



        return view('gree_i.sac.configs');
    }

    public function sacConfig_do(Request $request) {

		$configs = $request->all();
		
		foreach ($configs as $name => $val) {
			$input = Settings::where('command', $name)->first();
			$input->value = $val;
			$input->save();
		}

        $request->session()->put('success', "Configurações foram atualizadas");
        return redirect('/sac/config');
    }

    public function sacWarrantyAuthorizeds(Request $request) {

        $multiply = 1.609344;
        $distance = getConfig("sac_distance_km");

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $type_line = $request->type_line == "" ? 1 : $request->type_line;

        $query = "SELECT sac_authorized.*, "
            . "ROUND(" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ,8) as distance "
            . "from sac_authorized "
            . "WHERE EXISTS (SELECT 1 FROM sac_authorized_type "
            . "LEFT JOIN sac_type ON sac_authorized_type.id_sac_type = sac_type.id "
            . "where sac_authorized.id = sac_authorized_type.id_authorized and "
            . "sac_type.type_line = ".$type_line.") and "
            . "sac_authorized.is_active = 1 and "
            . "sac_authorized.type != 3 and "
            . "ROUND((" . $multiply . " * 3956 * acos( cos( radians('$latitude') ) * "
            . "cos( radians(latitude) ) * "
            . "cos( radians(longitude) - radians('$longitude') ) + "
            . "sin( radians('$latitude') ) * "
            . "sin( radians(latitude) ) ) ) ,8) <= $distance "
            . "order by distance ASC "
            . "LIMIT 20";

        $data = DB::select(DB::raw($query));

        $authorizeds = array();
        foreach ($data as $key) {
            $row = array();
            $row['name'] = $key->name;
            $row['name_contact'] = $key->name_contact;
            $row['identity'] = $key->identity;
            $row['phone_1'] = $key->phone_1;
            $row['phone_2'] = $key->phone_2;
            $row['email'] = $key->email;
            $row['latitude'] = $key->latitude;
            $row['longitude'] = $key->longitude;

            array_push($authorizeds, $row);
        }

        return response()->json([
            'success' => true,
            'authorizeds' => $authorizeds
        ]);

    }

    public function sacMap(Request $request) {

        $sac_type = SacType::All();

        return view('gree_i.sac.map_global', [
            'sac_type' => $sac_type
        ]);
    }

    public function sacWarrantyAuthorizedsAll(Request $request) {

        $status = $request->status == 1 ? 1 : 0;

        $data = sacAuthorized::orderBy('id', 'ASC');

        if ($request->status) {
            $data->where('is_active', $status);
        } else {
			$data->where('is_active', 1);
		}

        if ($request->authorized) {
            $data->where('id', $request->authorized);

        }

        if ($request->state) {
            $data->where('state', $request->state);

        }

        if ($request->city) {
            $data->where('city', 'like', '%'.$request->city.'%');

        }

        if ($request->skill) {
            $data->SkillFilter($request->skill);

        }

        //|| $request->type_line == null

        if ($request->session()->get('filter_line') == 2) {

            $data->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sac_authorized_type')
                    ->leftjoin('sac_type', 'sac_authorized_type.id_sac_type','=','sac_type.id')
                    ->whereRaw('sac_authorized.id = sac_authorized_type.id_authorized')
                    ->where('sac_type.type_line', 2);
            });
        } else if ($request->type_line) {

            $data->whereExists(function ($query) use($request) {
                $query->select(DB::raw(1))
                    ->from('sac_authorized_type')
                    ->leftjoin('sac_type', 'sac_authorized_type.id_sac_type','=','sac_type.id')
                    ->whereRaw('sac_authorized.id = sac_authorized_type.id_authorized')
                    ->where('sac_type.type_line', $request->type_line);
            });

        }


        /*if ($request->type_line) {
            $data->whereExists(function ($query) use ($type_line){
                $query->select(DB::raw(1))
                      ->from('sac_authorized_type')
                      ->leftjoin('sac_type', 'sac_authorized_type.id_sac_type','=','sac_type.id')
                      ->whereRaw('sac_authorized.id = sac_authorized_type.id_authorized')
                      ->where('sac_type.type_line', $request->type_line);
            });
        }*/

        $authorizeds = array();
        foreach ($data->get() as $key) {
            $row = array();
            $row['name'] = $key->name;
            $row['name_contact'] = $key->name_contact;
            $row['identity'] = $key->identity;
            $row['phone_1'] = $key->phone_1;
            $row['phone_2'] = $key->phone_2;
            $row['email'] = $key->email;
            $row['latitude'] = $key->latitude;
            $row['longitude'] = $key->longitude;

            array_push($authorizeds, $row);
        }

        return response()->json([
            'success' => true,
            'authorizeds' => $authorizeds,
            'teste' => $request->session()->get('filter_line'),
            'teste3' => $request->type_line
        ]);

    }

    public function sacClientEdit(Request $request, $id) {

        if ($id == 0) {

            $is_active = 1;
            $name = "";
            $type_people = 1;
            $latitude = 0;
            $longitude = 0;
            $identity = "";
            $phone = "";
            $phone_2 = "";
            $email = "";
            $address = "";
            $complement = "";

        } else {

            $client = SacClient::find($id);

            if ($client) {

                $name = $client->name;
                $type_people = $client->type_people;
                $is_active = $client->is_active;
                $latitude = $client->latitude;
                $longitude = $client->longitude;
                $identity = $client->identity;
                $phone = $client->phone;
                $phone_2 = $client->phone_2;
                $email = $client->email;

                if (empty($client->address)) {
                    $protocol = SacProtocol::where('client_id', $client->id)->first();
                    if ($protocol) {
                        $address = $protocol->address;
                        $complement = $protocol->complement;
                    } else {
                        $address = $client->address;
                        $complement = $client->complement;
                    }
                } else {
                    $address = $client->address;
                    $complement = $client->complement;
                }

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.sac.client_edit', [
            'id' => $id,
            'is_active' => $is_active,
            'name' => $name,
            'type_people' => $type_people,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'identity' => $identity,
            'phone' => $phone,
            'phone_2' => $phone_2,
            'email' => $email,
            'address' => $address,
            'complement' => $complement,
        ]);
    }

    public function sacClientEdit_do(Request $request) {

        if ($request->id == 0) {

            $client = new SacClient;

            if (SacClient::where('identity', $request->identity)->count() > 0) {

                $request->session()->put('error', 'Cliente já cadastrado nesse CPF ou CNPJ');
                return redirect()->back();
            }

        } else {

            $client = SacClient::find($request->id);

            if (!$client) {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $client->name = $request->name;
        $client->type_people = $request->type_people;
        if ($request->password) {
            $client->password = Hash::make($request->password);
        }
        $client->latitude = $request->latitude;
        $client->longitude = $request->longitude;
        $client->identity = $request->identity;
        $client->phone = $request->phone;
        $client->phone_2 = $request->phone_2;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $client->email = $request->email;
        }
        $client->address = $request->address;
        $client->complement = $request->complement;

        $client->save();

        if ($request->id == 0) {
            LogSystem("Colaborador criou novo cliente para sac ". $client->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Novo cliente criado com sucesso!");
        }
        else {
            LogSystem("Colaborador atualizou cliente para sac ". $client->id, $request->session()->get('r_code'));
            $request->session()->put('success', "cliente atualizado com sucesso!");
        }
        return redirect('/sac/client/edit/'. $request->id);
    }

    public function sacRepresentationEdit(Request $request, $id) {

        if ($id == 0) {

            $is_active = 1;
            $name = "";
            $name_contact = "";
            $identity = "";
            $phone_1 = "";
            $phone_2 = "";
            $email = "";
            $address = "";
            $zipcode = "";
            $city = "";
            $state = "";
            $complement = "";

        } else {

            $item = Representation::find($id);

            if ($item) {

                $is_active = $item->is_active;
                $name = $item->name;
                $name_contact = $item->name_contact;
                $identity = $item->identity;
                $phone_1 = $item->phone_1;
                $phone_2 = $item->phone_2;
                $email = $item->email;
                $address = $item->address;
                $zipcode = $item->zipcode;
                $city = $item->city;
                $state = $item->state;
                $complement = $item->complement;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.sac.representation_edit', [
            'id' => $id,
            'is_active' => $is_active,
            'name' => $name,
            'name_contact' => $name_contact,
            'identity' => $identity,
            'phone_1' => $phone_1,
            'phone_2' => $phone_2,
            'email' => $email,
            'address' => $address,
            'zipcode' => $zipcode,
            'city' => $city,
            'state' => $state,
            'complement' => $complement,
        ]);
    }

    public function sacRepresentationEdit_do(Request $request) {

        if ($request->id == 0) {

            $item = new Representation;

        } else {

            $item = Representation::find($request->id);

            if (!$item) {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $item->name = $request->name;
        $item->name_contact = $request->name_contact;
        $item->is_active = $request->is_active;
        $item->identity = $request->identity;
        $item->phone_1 = $request->phone_1;
        $item->phone_2 = $request->phone_2;
        $item->email = $request->email;
        $item->address = $request->address;
        $item->zipcode = $request->zipcode;
        $item->complement = $request->complement;

        $item->save();

        if ($request->id == 0) {
            LogSystem("Colaborador criou novo representante para sac ". $item->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Novo representante criada com sucesso!");
        }
        else {
            LogSystem("Colaborador atualizou representante para sac ". $item->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Representante atualizada com sucesso!");
        }

        return redirect('/sac/register/salesman/edit/'. $request->id);
    }

    public function sacRepresentationAll(Request $request) {

        if (!empty($request->input('name_identity'))) {
            $request->session()->put('sacf_name_identity', $request->input('name_identity'));
        } else {
            $request->session()->forget('sacf_name_identity');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }

        $list = DB::table('representation')->orderBy('id', 'DESC');

        if (!empty($request->session()->get('sacf_name_identity'))) {
            $list->where('id', $request->session()->get('sacf_client'));
        }
        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $list->where('is_active', 1);
            } else if ($request->session()->get('sacf_status') == 2) {
                $list->where('is_active', 0);
            }
        }

        return view('gree_i.sac.representation_list', [
            'list' => $list->paginate(10),
        ]);
    }

    public function sacShopEdit(Request $request, $id) {

        if ($id == 0) {

            $is_active = 1;
            $name = "";
            $phone = "";
            $phone_2 = "";
            $email = "";
            $address = "";
            $zipcode = "";
            $city = "";
            $state = "";
            $complement = "";
            $observation = "";

        } else {

            $item = SacShop::find($id);

            if ($item) {

                $is_active = $item->is_active;
                $name = $item->name;
                $phone = $item->phone;
                $phone_2 = $item->phone_2;
                $email = $item->email;
                $address = $item->address;
                $zipcode = $item->zipcode;
                $city = $item->city;
                $state = $item->state;
                $complement = $item->complement;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.sac.shop_edit', [
            'id' => $id,
            'is_active' => $is_active,
            'name' => $name,
            'phone' => $phone,
            'phone_2' => $phone_2,
            'email' => $email,
            'address' => $address,
            'zipcode' => $zipcode,
            'city' => $city,
            'state' => $state,
            'complement' => $complement,
        ]);
    }

    public function sacShopEdit_do(Request $request) {

        if ($request->id == 0) {

            $item = new SacShop;

        } else {

            $item = SacShop::find($request->id);

            if (!$item) {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $item->name = $request->name;
        $item->is_active = $request->is_active;
        $item->phone = $request->phone_1;
        $item->phone_2 = $request->phone_2;
        $item->email = $request->email;
		$item->city = $request->city;
		$item->state = $request->state;
        $item->address = $request->address;
        $item->zipcode = $request->zipcode;
        $item->complement = $request->complement;

        $item->save();

        if ($request->id == 0) {
            LogSystem("Colaborador criou nova loja para sac ". $item->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Nova loja criada com sucesso!");
        }
        else {
            LogSystem("Colaborador atualizou loja para sac ". $item->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Loja atualizada com sucesso!");
        }

        return redirect('/sac/register/shop/edit/'. $request->id);
    }

    public function sacShopAll(Request $request) {


        if (!empty($request->input('name_identity'))) {
            $request->session()->put('sacf_name_identity', $request->input('name_identity'));
        } else {
            $request->session()->forget('sacf_name_identity');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }

        $list = DB::table('sac_shop')->orderBy('id', 'DESC');

        if (!empty($request->session()->get('sacf_name_identity'))) {
            $list->where('id', $request->session()->get('sacf_client'));
        }
        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $list->where('is_active', 1);
            } else if ($request->session()->get('sacf_status') == 2) {
                $list->where('is_active', 0);
            }
        }

        return view('gree_i.sac.shop_list', [
            'list' => $list->paginate(10),
        ]);
    }

    public function sacShopPartsEdit(Request $request, $id) {

        if ($id == 0) {

            $is_active = 1;
            $code = "";
            $name = "";
            $phone = "";
            $phone_2 = "";
            $email = "";
            $address = "";
            $zipcode = "";
            $city = "";
            $state = "";
            $complement = "";
            $observation = "";
			$site = "";

        } else {

            $item = SacShopParts::find($id);

            if ($item) {

                $is_active = $item->is_active;
                $code = $item->code;
                $name = $item->name;
                $phone = $item->phone;
                $phone_2 = $item->phone_2;
                $email = $item->email;
                $address = $item->address;
                $zipcode = $item->zipcode;
                $city = $item->city;
                $state = $item->state;
                $complement = $item->complement;
				$site = $item->site;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.sac.shop_parts_edit', [
            'id' => $id,
            'is_active' => $is_active,
            'code' => $code,
            'name' => $name,
            'phone' => $phone,
            'phone_2' => $phone_2,
            'email' => $email,
            'address' => $address,
            'zipcode' => $zipcode,
            'city' => $city,
            'state' => $state,
            'complement' => $complement,
			'site' => $site,
        ]);
    }

    public function sacShopPartsEdit_do(Request $request) {

        if ($request->id == 0) {

            $item = new SacShopParts;

        } else {

            $item = SacShopParts::find($request->id);

            if (!$item) {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $item->name = $request->name;
        $item->code = $request->code;
        $item->is_active = $request->is_active;
        $item->phone = $request->phone;
        $item->phone_2 = $request->phone_2;
        $item->email = $request->email;
        $item->address = $request->address;
        $item->state = $request->state;
        $item->city = $request->city;
        $item->zipcode = $request->zipcode;
        $item->complement = $request->complement;
		$item->site = $request->site;

        $item->save();

        if ($request->id == 0) {
            LogSystem("Colaborador criou nova loja de peças para sac ". $item->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Nova loja de peças criada com sucesso!");
        }
        else {
            LogSystem("Colaborador atualizou loja de peças para sac ". $item->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Loja de peças atualizada com sucesso!");
        }

        return redirect()->back();
    }

    public function sacShopPartsAll(Request $request) {


        if (!empty($request->input('name_identity'))) {
            $request->session()->put('sacf_name_identity', $request->input('name_identity'));
        } else {
            $request->session()->forget('sacf_name_identity');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }

        $list = DB::table('sac_shop_parts')->orderBy('id', 'DESC');

        if (!empty($request->session()->get('sacf_name_identity'))) {
            $list->where('id', $request->session()->get('sacf_client'));
        }
        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $list->where('is_active', 1);
            } else if ($request->session()->get('sacf_status') == 2) {
                $list->where('is_active', 0);
            }
        }

        return view('gree_i.sac.shop_parts_list', [
            'list' => $list->paginate(10),
        ]);
    }

    public function sacClientAll(Request $request) {


        if (!empty($request->input('client'))) {
            $request->session()->put('sacf_client', $request->input('client'));
        } else {
            $request->session()->forget('sacf_client');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }

        $client = DB::table('sac_client')->orderBy('id', 'DESC');

        if (!empty($request->session()->get('sacf_client'))) {
            $client->where('id', $request->session()->get('sacf_client'));
        }
        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $client->where('is_active', 1);
            } else if ($request->session()->get('sacf_status') == 2) {
                $client->where('is_active', 0);
            }
        }

        return view('gree_i.sac.client_list', [
            'client' => $client->paginate(10),
        ]);
    }

    public function sacComunicationAuthorizedAll(Request $request) {

        $comunication = SacAuthorizedNotify::orderBy('id', 'DESC')->paginate(10);

        return view('gree_i.sac.comunication_notify', [
            'comunication' => $comunication,
        ]);
    }

    public function sacComunicationAuthorizedEdit(Request $request, $id) {

        if ($id == 0) {
            
            $authorized_id = "";
            $priority = 1;
            $subject = "";
            $description = "";
            $attach_1 = "";
            $attach_2 = "";
            $attach_3 = "";
            $link_external = "";
            $picture = "";
            $link_picture = "";

        } else {

            $comunication = SacAuthorizedNotify::find($id);

            if ($comunication) {
                
                $authorized_id = $comunication->authorized_id;
                $priority = $comunication->priority;
                $subject = $comunication->subject;
                $description = $comunication->description;
                $attach_1 = $comunication->attach_1;
                $attach_2 = $comunication->attach_2;
                $attach_3 = $comunication->attach_3;
                $link_external = $comunication->link_external;
                $picture = $comunication->picture;
                $link_picture = $comunication->link_picture;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $userall = SacAuthorized::all();

        return view('gree_i.sac.comunication_notify_edit', [
            'id' => $id,
            'userall' => $userall,
            'authorized_id' => $authorized_id,
            'priority' => $priority,
            'subject' => $subject,
            'description' => $description,
            'attach_1' => $attach_1,
            'attach_2' => $attach_2,
            'attach_3' => $attach_3,
            'link_external' => $link_external,
            'picture' => $picture,
            'link_picture' => $link_picture
        ]);
    }

    public function sacComunicationAuthorizedEdit_do(Request $request) {

        if ($request->id == 0) {
            $comunication = new SacAuthorizedNotify;
        } else {
            $comunication = SacAuthorizedNotify::find($request->id);
        }

        $comunication->authorized_id = $request->authorized_id;
        $comunication->priority = $request->priority;
        $comunication->r_code = $request->session()->get('r_code');
        $comunication->subject = $request->subject;
        $comunication->description = $request->description;

        if ($request->hasFile('picture')) {
            $response = uploadS3(1, $request->picture, $request);
            if ($response['success']) {
                $comunication->picture = $response['url'];
            } else {
                return Redirect('/sac/comunication/authorized/all');
            }
        }
        if ($request->hasFile('attach_1')) {
            $response = uploadS3(1, $request->attach_1, $request);
            if ($response['success']) {
                $comunication->attach_1 = $response['url'];
            } else {
                return Redirect('/sac/comunication/authorized/all');
            }
        }
        if ($request->hasFile('attach_2')) {
            $response = uploadS3(2, $request->attach_2, $request);
            if ($response['success']) {
                $comunication->attach_2 = $response['url'];
            } else {
                return Redirect('/sac/comunication/authorized/all');
            }
        }
        if ($request->hasFile('attach_3')) {
            $response = uploadS3(3, $request->attach_3, $request);
            if ($response['success']) {
                $comunication->attach_3 = $response['url'];
            } else {
                return Redirect('/sac/comunication/authorized/all');
            }
        }

        $comunication->link_picture = $request->link_picture;
        $comunication->link_external = $request->link_external;
        $comunication->save();
        
        if ($request->id == 0) {

            if ($request->authorized_id) {

                $authorized = SacAuthorized::find($request->authorized_id);

                if ($authorized->email) {
                    
                    $attachs = $comunication->attach_1 != null ? "<p>Anexos</p> <br><a href='". $comunication->attach_1 ."'>". $comunication->attach_1 ."</a><br><a href='". $comunication->attach_2 ."'>". $comunication->attach_2 ."</a><br><a href='". $comunication->attach_3 ."'>". $comunication->attach_3 ."</a>" : "";
                    $pattern = array(
                        'title' => strtoupper($request->subject),
                        'image' => $comunication->picture,
                        'link_image' => $comunication->link_picture,
                        'description' => nl2br($request->description ."". $attachs),
                        'template' => 'misc.DefaultExternalNotifyAuthorized',
                        'subject' => $request->subject,
                    );

                    SendMailJob::dispatch($pattern, $authorized->email);
                }

            } else {

                $attachs = $comunication->attach_1 != null ? "<p>Anexos</p> <br><a href='". $comunication->attach_1 ."'>". $comunication->attach_1 ."</a><br><a href='". $comunication->attach_2 ."'>". $comunication->attach_2 ."</a><br><a href='". $comunication->attach_3 ."'>". $comunication->attach_3 ."</a>" : "";
                $pattern = array(
                    'title' => strtoupper($request->subject),
                    'image' => $comunication->picture,
                    'link_image' => $comunication->link_picture,
                    'description' => nl2br($request->description ."". $attachs),
                    'template' => 'misc.DefaultExternalNotifyAuthorized',
                    'subject' => $request->subject,
                );

                DB::table('sac_authorized')->where('is_active', 1)->orderBy('id', 'DESC')->chunk(10, function($authorizeds) use ($pattern)
                    {
                        foreach ($authorizeds as $authorized)
                    {
                        if ($authorized->email) {
        
							delayQueueEmail($pattern, $authorized->email);
                        }
                    }
                });

            }
        
            LogSystem("Colaborador criou nova notificação para autorizada ". $comunication->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Nova notificação criada com sucesso!");
        }
        else { 
        LogSystem("Colaborador atualizou autorizada para sac ". $comunication->id, $request->session()->get('r_code'));
        $request->session()->put('success', "notificação atualizada com sucesso!");
        }
        return Redirect('/sac/comunication/authorized/all');
        
    }

    public function sacComunicationAuthorizedDelete(Request $request, $id) {

        $comunication = SacAuthorizedNotify::find($id);

        if ($comunication) {

            $comunication = SacAuthorizedNotify::where('id', $id)->delete();
            LogSystem("Colaborador atualizou autorizada para sac ". $id, $request->session()->get('r_code'));
            $request->session()->put('success', "notificação deletada com sucesso!");
            return Redirect('/sac/comunication/authorized/all');

        } else {

            $request->session()->put('success', "notificação não foi encontrada.");
            return Redirect('/sac/comunication/authorized/all');
        }

    }

    public function sacFaqAll(Request $request) {

        // SAVE FILTERS
        if (!empty($request->input('type'))) {
            $request->session()->put('sacf_type', $request->input('type'));
        } else {
            $request->session()->forget('sacf_type');
        }

        $faq = SacFaq::orderBy('id', 'DESC');

        if (!empty($request->session()->get('sacf_type'))) {
            $faq->where('type', $request->session()->get('sacf_type'));
        }

        return view('gree_i.sac.faq_list', [
            'faq' => $faq->paginate(10),
        ]);
    }

    public function sacFaqEdit(Request $request, $id) {

        if ($id == 0) {

            $type = 1;
            $question = "";
            $answer = "";

        } else {

            $comunication = SacFaq::find($id);

            if ($comunication) {

                $type = $comunication->type;
                $question = $comunication->question;
                $answer = $comunication->answer;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $userall = SacAuthorized::all();

        return view('gree_i.sac.faq_edit', [
            'id' => $id,
            'type' => $type,
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function sacFaqEdit_do(Request $request) {

        if ($request->id == 0) {
            $comunication = new SacFaq;
        } else {
            $comunication = SacFaq::find($request->id);
        }

        $comunication->type = $request->type;
        $comunication->question = $request->question;
        $comunication->r_code = $request->session()->get('r_code');
        $comunication->answer = $request->answer;
        $comunication->save();

        if ($request->id == 0) {
            LogSystem("Colaborador criou nova pergunta frequente ". $comunication->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Nova pergunta frequente criada com sucesso!");
        }
        else {
            LogSystem("Colaborador atualizou pergunta frequente para sac ". $comunication->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Pergunta frequente atualizada com sucesso!");
        }
        return Redirect('/sac/faq/all');

    }

    public function sacFaqDelete(Request $request, $id) {

        $comunication = SacFaq::find($id);

        if ($comunication) {

            $comunication = SacFaq::where('id', $id)->delete();
            LogSystem("Colaborador atualizou perguntas frequentes para sac ". $id, $request->session()->get('r_code'));
            $request->session()->put('success', "Pergunta frequente deletada com sucesso!");
            return Redirect('/sac/faq/all');

        } else {

            $request->session()->put('success', "notificação não foi encontrada.");
            return Redirect('/sac/faq/all');
        }

    }

    public function sacAuthorizedView(Request $request, $id) {

        $user = SacAuthorized::find($id);
        if ($user) {

            LogSystem("Colaborador realizou acesso como a autorizada ID: ". $id, $request->session()->get('r_code'));

            $request->session()->put('sac_authorized_id', $user->id);
            $request->session()->put('sac_authorized_name', $user->name);
            $request->session()->put('sac_authorized_email', $user->email);
            $request->session()->put('sac_authorized_identity', $user->identity);

            return redirect('/autorizada/painel');
        } else {

            $request->session()->put('error', 'Autorizada/Credenciada não foi encontrada');
            return redirect()->back();
        }

    }

    public function sacAuthorizedEdit(Request $request, $id) {

        $sac_type = SacType::All();

        if ($id == 0) {

            $is_active = 1;
            $type = 1;
            $type_people = 2;
            $code = "";
            $name = "";
            $name_contact = "";
            $latitude = 0;
            $longitude = 0;
            $identity = "";
            $phone_1 = "";
            $phone_2 = "";
            $email = "";
            $email_copy = "";
            $agency = "";
            $account = "";
            $bank = "";
            $address = "";
            $zipcode = "";
            $city = "";
            $state = "";
            $complement = "";
            $observation = "";
            $only_repair_sell = 0;
            $authorized = '';
            $remittance = '';

        } else {

            $authorized = SacAuthorized::with('sacTypes')->where('id', $id)->first();

            if ($authorized) {

                $is_active = $authorized->is_active;
                $type = $authorized->type;
                $type_people = $authorized->type_people;
                $code = $authorized->code;
                $name = $authorized->name;
                $name_contact = $authorized->name_contact;
                $latitude = $authorized->latitude;
                $longitude = $authorized->longitude;
                $identity = $authorized->identity;
                $phone_1 = $authorized->phone_1;
                $phone_2 = $authorized->phone_2;
                $email = $authorized->email;
                $email_copy = $authorized->email_copy;
                $agency = $authorized->agency;
                $account = $authorized->account;
                $bank = $authorized->bank;
                $address = $authorized->address;
                $zipcode = $authorized->zipcode;
                $city = $authorized->city;
                $state = $authorized->state;
                $complement = $authorized->complement;
                $observation = $authorized->observation;
                $only_repair_sell = $authorized->only_repair_sell;
                $remittance = $authorized->is_remittance;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.sac.authorized_edit', [
            'id' => $id,
            'is_active' => $is_active,
            'only_repair_sell' => $only_repair_sell,
            'type' => $type,
            'type_people' => $type_people,
            'code' => $code,
            'name' => $name,
            'name_contact' => $name_contact,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'identity' => $identity,
            'phone_1' => $phone_1,
            'phone_2' => $phone_2,
            'email' => $email,
            'email_copy' => $email_copy,
            'agency' => $agency,
            'account' => $account,
            'bank' => $bank,
            'address' => $address,
            'zipcode' => $zipcode,
            'city' => $city,
            'state' => $state,
            'complement' => $complement,
            'observation' => $observation,
            'sac_type' => $sac_type,
            'authorized' => $authorized,
            'remittance' => $remittance,
        ]);
    }

    public function sacAuthorizedEdit_do(Request $request) {

        if ($request->id == 0) {

            $authorized = new SacAuthorized;

            if (SacAuthorized::where('identity', $request->identity)->count() > 0) {

                $request->session()->put('error', 'Autorizada/Credenciada já cadastrada nesse CPF ou CNPJ');
                return redirect()->back();
            }

        } else {

            $authorized = SacAuthorized::find($request->id);

            if (!$authorized) {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        $authorized->name = $request->name;
        $authorized->code = $request->code;
        $authorized->name_contact = $request->name_contact;
        if ($request->password) {
            $authorized->password = Hash::make($request->password);
        }
        $authorized->is_active = $request->is_active;
        $authorized->latitude = $request->latitude;
        $authorized->longitude = $request->longitude;
        $authorized->identity = $request->identity;
        $authorized->phone_1 = $request->phone_1;
        $authorized->phone_2 = $request->phone_2;
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $authorized->email = $request->email;
        }
        if (filter_var($request->email_copy, FILTER_VALIDATE_EMAIL)) {
            $authorized->email_copy = $request->email_copy;
        }
        $authorized->account = $request->account;
        $authorized->agency = $request->agency;
        $authorized->bank = $request->bank;
        $authorized->type = $request->type;
        $authorized->type_people = $request->type_people;
        $authorized->city = $request->city;
        $authorized->state = $request->state;
        $authorized->address = $request->address;
        $authorized->zipcode = $request->zipcode;
        $authorized->complement = $request->complement;
        $authorized->observation = $request->observation;
        $authorized->only_repair_sell = $request->only_repair_sell;
        $authorized->is_remittance = $request->remittance == 1 ? 1 : 0;

        $authorized->save();

        $authorized_type = collect($request->sac_type);

        if ($request->id != 0) {

            $sacAuthorizedType = SacAuthorizedType::where('id_authorized', $request->id);
            $sac_type = $sacAuthorizedType->pluck('id_sac_type');

            if (!$sac_type->isEmpty()) {

                $type_to_delete = $sac_type->diff($authorized_type);
                $authorized_type = $authorized_type->diff(collect($sac_type));
                $sacAuthorizedType->whereIn('id_sac_type', $type_to_delete)->delete();
            }

        }

        foreach ($authorized_type as $r_code) {

            $sacAuthorizedType = new SacAuthorizedType;
            $sacAuthorizedType->id_authorized = $authorized->id;
            $sacAuthorizedType->id_sac_type = $r_code;
            $sacAuthorizedType->save();
        }



        if ($request->id == 0) {
            LogSystem("Colaborador criou nova autorizada para sac ". $authorized->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Nova autorizada criada com sucesso!");
        }
        else {
            LogSystem("Colaborador atualizou autorizada para sac ". $authorized->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Autorizada atualizada com sucesso!");
        }

        return redirect('/sac/authorized/edit/'. $request->id);
    }

    public function sacAuthorizedAll(Request $request) {


        if (!empty($request->input('authorized'))) {
            $request->session()->put('sacf_authorized', $request->input('authorized'));
        } else {
            $request->session()->forget('sacf_authorized');
        }
        if (!empty($request->input('status'))) {
            $request->session()->put('sacf_status', $request->input('status'));
        } else {
            $request->session()->forget('sacf_status');
        }
        if (!empty($request->input('rate'))) {
            $request->session()->put('sacf_rate', $request->input('rate'));
        } else {
            $request->session()->forget('sacf_rate');
        }
        if (!empty($request->input('manifest'))) {
            $request->session()->put('sacf_manifest', $request->input('manifest'));
        } else {
            $request->session()->forget('sacf_manifest');
        }
        if (!empty($request->input('state'))) {
            $request->session()->put('sacf_state', $request->input('state'));
        } else {
            $request->session()->forget('sacf_state');
        }
        if (!empty($request->input('city'))) {
            $request->session()->put('sacf_city', $request->input('city'));
        } else {
            $request->session()->forget('sacf_city');
        }
		if (!empty($request->input('type'))) {
            $request->session()->put('sacf_type', $request->input('type'));
        } else {
            $request->session()->forget('sacf_type');
        }
        if (!empty($request->input('remittance'))) {
            $request->session()->put('sacf_remittance', $request->input('remittance'));
        } else {
            $request->session()->forget('sacf_remittance');
        }		


        $authorized = DB::table('sac_authorized');

        if (!empty($request->session()->get('sacf_authorized'))) {
            $authorized->where('id', $request->session()->get('sacf_authorized'));
        }
        if (!empty($request->session()->get('sacf_status'))) {
            if ($request->session()->get('sacf_status') == 1) {
                $authorized->where('is_active', 1);
            } else if ($request->session()->get('sacf_status') == 2) {
                $authorized->where('is_active', 0);
            }
        }
        if (!empty($request->session()->get('sacf_rate'))) {
            if ($request->session()->get('sacf_rate') == 1) {
                $authorized->where('rate', '>', 0.00)->orderBy('rate', 'DESC');
            } else if ($request->session()->get('sacf_rate') == 2) {
                $authorized->where('rate', '>', 0.00)->orderBy('rate', 'ASC');
            }
        } else if (!empty($request->session()->get('sacf_manifest'))) {
            if ($request->session()->get('sacf_manifest') == 1) {
                $authorized->orderBy('live', 'DESC');
            } else if ($request->session()->get('sacf_manifest') == 2) {
                $authorized->orderBy('live', 'ASC');
            }
        } else {
            $authorized->orderBy('id', 'DESC');
        }
        if (!empty($request->session()->get('sacf_state'))) {
            $authorized->where('state', $request->session()->get('sacf_state'));
        }
        if (!empty($request->session()->get('sacf_city'))) {
            $authorized->where('city', 'like', '%'.$request->session()->get('sacf_city').'%');
        }
		if (!empty($request->session()->get('sacf_type'))) {
            $authorized->where('type', $request->session()->get('sacf_type'));
        }
        if (!empty($request->session()->get('sacf_remittance'))) {
			$value = $request->session()->get('sacf_remittance') == 99 ? 0 : 1;
            $authorized->where('is_remittance', $value);
        }

        return view('gree_i.sac.authorized_list', [
            'authorized' => $authorized->paginate(10),
        ]);
    }

    public function sacAuthorizedHistoric_do(Request $request) {

        if ($request->id == 0) {
            $note = new SacAuthorizedHistoric;
        } else {
            $note = SacAuthorizedHistoric::find($request->id);
            if(!$note) {
                $request->session()->put('error', 'Nota não encontrada!');
                return redirect()->back();
            }
        }
        $note->authorized_id = $request->authorized_id;
        $note->description = $request->description;
        $note->priority = $request->priority;
        $note->r_code = $request->session()->get('r_code');
        $note->save();
        LogSystem("Colaborador criou uma nova nota para o histórico da autorizada (SAC - Edição da autorizada): ID #". $request->authorized_id, $request->session()->get('r_code'));
        $request->session()->put('success', 'Nota cadastrada com sucesso!');
        return redirect('/sac/authorized/edit/'.$request->authorized_id.'');
    }

    public function sacAuthorizedHistoricDelete(Request $request, $id) {
        $note = SacAuthorizedHistoric::find($id);
        if ($note) {
            SacAuthorizedHistoric::where('id', $id)->delete();
            $request->session()->put('success', 'Nota excluída com sucesso!');
            return redirect()->back();
        }
        else {
            $request->session()->put('success', 'Nota não encontra!');
            return redirect()->back();
        }
    }

    public function sacAuthorizedHistoric(Request $request) {

        $note = SacAuthorizedHistoric::where('authorized_id', $request->id)->latest('id')->first();
        if($note) {
            return response()->json([
                'note' => $note,
                'first_name' => $note->owner()->first_name,
                'last_name' => $note->owner()->last_name
            ], 200);
        }
        else {
            return response()->json([
                'note' => $note
            ], 200);
        }
    }

    public function nothing(Request $request) {
        if ($request->session()->get('r_code')) {
            return redirect('/news');
        } else {
            return redirect('/404');
        }
    }

    public function financyLendingDashboard(Request $request) {

        $last_year = date('Y') - 1;
        if (!empty($request->input('r_code'))) {
            $request->session()->put('lendingdf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('lendingdf_r_code');
        }

        if ($request->session()->get('r_code') != '0004' and $request->session()->get('r_code') != '0005' and $request->session()->get('r_code') != '1842') {
            $request->session()->put('lendingdf_r_code', $request->session()->get('r_code'));
        }

        if ($request->session()->get('lendingdf_r_code')) {
            $cfy_actual = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = financyLending::where('is_paid', 1)
                    ->where('r_code', $request->session()->get('lendingdf_r_code'))
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y'))
                    ->sum('amount');

                $total = $total == null ? 0 : $total;
                $cfy_actual->push(number_format($total, 2, '.', ''));
            }

            $used_credit = UserFinancy::where('r_code', $request->session()->get('lendingdf_r_code'))
                ->sum('used_credit');

            $cfy_last = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = financyLending::where('is_paid', 1)
                    ->where('r_code', $request->session()->get('lendingdf_r_code'))
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', $last_year)
                    ->sum('amount');

                $total = $total == null ? 0 : $total;
                $cfy_last->push(number_format($total, 2, '.', ''));
            }

            $lending = financyLending::where('is_paid', 1)
                ->where('r_code', $request->session()->get('lendingdf_r_code'))
                ->whereYear('created_at', date('Y'))
                ->sum('amount');

            $compareLendingYear = new SacChart;
            $compareLendingYear->labels(['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']);
            $compareLendingYear->dataset('2019', 'line', $cfy_last)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(71, 148, 207, 0.2)");
            $compareLendingYear->dataset('2020', 'line', $cfy_actual)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(110, 174, 224)");

        } else {

            $cfy_actual = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = financyLending::where('is_paid', 1)
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', date('Y'))
                    ->sum('amount');

                $total = $total == null ? 0 : $total;
                $cfy_actual->push(number_format($total, 2, '.', ''));
            }

            $used_credit = UserFinancy::sum('used_credit');

            $cfy_last = collect([]);
            for ($i=1; $i <= 12; $i++) {
                $total = financyLending::where('is_paid', 1)
                    ->whereMonth('created_at', $i)
                    ->whereYear('created_at', $last_year)
                    ->sum('amount');
                $total = $total == null ? 0 : $total;
                $cfy_last->push(number_format($total, 2, '.', ''));
            }

            $lending = financyLending::where('is_paid', 1)
                ->whereYear('created_at', date('Y'))
                ->sum('amount');

            $compareLendingYear = new SacChart;
            $compareLendingYear->labels(['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']);
            $compareLendingYear->dataset('2019', 'line', $cfy_last)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(71, 148, 207, 0.2)");
            $compareLendingYear->dataset('2020', 'line', $cfy_actual)
                ->color("rgb(71, 148, 207)")
                ->backgroundcolor("rgb(110, 174, 224)");

        }

        $userall = Users::all();

        return view('gree_i.lending.financy_dashboard', [
            'compareLendingYear' => $compareLendingYear,
            'used_credit' => $used_credit,
            'lending' => $lending,
            'userall' => $userall
        ]);
    }

    public function financyLendingEdit(Request $request) {

        $ac_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();

        $trips = Trips::where('r_code', $request->session()->get('r_code'))->get();

        $already_loan = financyLending::where('r_code', $request->session()->get('r_code'))
            ->where(function ($q) {
                $q->orWhere(function ($query) {
                    $query->where('mng_approv', 0)
                        ->where('mng_reprov', 0)
                        ->where('financy_approv', 0)
                        ->where('financy_reprov', 0)
                        ->where('pres_approv', 0)
                        ->where('pres_reprov', 0);
                })->orWhere(function ($query) {
                    $query->where('mng_approv', 1)
                        ->where('mng_reprov', 0)
                        ->where('financy_approv', 0)
                        ->where('financy_reprov', 0)
                        ->where('pres_approv', 0)
                        ->where('pres_reprov', 0);
                })->orWhere(function ($query) {
                    $query->where('mng_approv', 1)
                        ->where('mng_reprov', 0)
                        ->where('financy_approv', 1)
                        ->where('financy_reprov', 0)
                        ->where('pres_approv', 0)
                        ->where('pres_reprov', 0);
                });

            })->first();

        return view('gree_i.lending.financy_new_lending', [
            'ac_bank' => $ac_bank,
            'trips' => $trips,
            'already_loan' => $already_loan,
        ]);
    }

    public function financyLendingApprovView(Request $request, $id) {

        $lending = financyLending::find($id);
        if ($lending->pres_approv == 1) {

            $request->session()->put('error','Solicitação já foi aprovada!');
            return redirect('/news');
        }
        $ac_bank = UserFinancy::where('r_code', $lending->r_code)->first();
        $user = Users::where('r_code', $lending->r_code)->first();

        if ($lending->mng_approv == 1 and $lending->financy_approv == 0 and $lending->financy_reprov == 0) {
            $is_financy = 1;
        } else {
            $is_financy = 0;
        }
        $receiver = empty($lending->financy_receiver) ? "" : $lending->financy_receiver;

        $has_suspended = $lending->has_suspended;

        $userall = Users::all();

        return view('gree_i.lending.financy_approv_view_lending', [
            'ac_bank' => $ac_bank,
            'has_suspended' => $has_suspended,
            'receiver' => $receiver,
            'is_financy' => $is_financy,
            'lending' => $lending,
            'user' => $user,
            'userall' => $userall,
            'id' => $id,
            'url' => '/financy/lending/analyze_do'
        ]);
    }

    public function financyLendingEdit_do(Request $request) {

        try {

            $ac_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();
            $attach_type = 0;
            $attach_url = "";

            if ($ac_bank) {
                $lending = new FinancyLending;
                $lending->code = getCodeModule('lending');
                $lending->r_code = $request->session()->get('r_code');
                $lending->description = $request->description;
                $lending->p_method = $request->p_method;
                $lending->amount = $request->total;
                $lending->agency = $ac_bank->agency;
                $lending->account = $ac_bank->account;
                $lending->bank = $ac_bank->bank;
                $lending->identity = $ac_bank->identity;
                $lending->has_analyze = 1;
                $lending->save();

                $finance_lending = FinancyLending::with('user.immediates', 'user.financy')->find($lending->id);
                if (!$finance_lending)
                    return redirect()->back()->with('error', 'Solicitação de empréstimo não encontrada.');

                if ($request->type_data == 1) {

                    $module_attach = new FinancyLendingAttach;
                    $module_attach->financy_lending_id = $lending->id;
                    $module_attach->is_module = 1;
                    $module_attach->module_type = 1;
                    $module_attach->id_module = $request->trip;
                    $module_attach->save();

                    $attach_type = 1;
                    $attach_url = $request->trip;

                } else if ($request->type_data == 99) {

                    $module_attach = new FinancyLendingAttach;
                    $module_attach->financy_lending_id = $lending->id;
                    $module_attach->is_file = 1;
                    $attach_type = 2;

                    if ($request->hasFile('file')) {
                        $file = $request->file;
                        $extension = $request->file->extension();
                        if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                            $validator = Validator::make(
                                [
                                    'file' => $file,
                                ],
                                [
                                    'file' => 'required|max:1000',
                                ]
                            );

                            if ($validator->fails()) {

                                $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                                return Redirect('/financy/lending/new');
                            } else {

                                $imagem = date('YmdHis') .'-'. $lending->id .'.'. $extension;

                                $request->file->storeAs('/', $imagem, 's3');
                                $url = Storage::disk('s3')->url($imagem);
                                $module_attach->url_file = $url;
                                $attach_url = $url;
                            }

                        } else {

                            $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                            return Redirect('/financy/lending/new');
                        }
                    }

                    $module_attach->save();
                }

                $request->merge(['attach_url' => $attach_url]);
                $lending_analyze = new App\Services\Departaments\Administration\Lending\Lending($finance_lending, $request);
                $do_analyze = new App\Services\Departaments\ProcessAnalyze($lending_analyze);
                $do_analyze->eventStart();
                
                LogSystem("Colaborador criou novo pedido de empréstimo ID ". $lending->id, $request->session()->get('r_code'));
                $request->session()->put('success', "Novo pedido de empréstimo realizado com sucesso!");
                return redirect('/financy/lending/my');

            } else {
                return redirect('/financy/lending/new');
            }
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }    
    }
	
	public function financyLendingAnalyze_do(Request $request) {

        try {

            $lending = FinancyLending::with('rtd_analyze.users', 'user', 'financy_lending_attach')->find($request->id);
            
            if(!$lending)
                return redirect()->back()->with('error', 'Empréstimo não encontrado!');

            $lending_analyze = new App\Services\Departaments\Administration\Lending\Lending($lending, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($lending_analyze);

            $actions = [
                1 => 'eventApprov',
                2 => 'eventReprov',
                3 => 'eventSuspended',
                4 => 'eventRevert'
            ];
            
            $method = $actions[$lending_analyze->request->type];
            $result = $do_analyze->$method();

            return redirect('/financy/lending/approv')->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
	
    public function financyLendingMy(Request $request) {

        $ac_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();

        $lending = FinancyLending::where('r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC')->paginate(10);

        return view('gree_i.lending.financy_my_lending', [
            'ac_bank' => $ac_bank,
            'lending' => $lending
        ]);
    }

    public function financyLendingApprov(Request $request) {

        $lending = FinancyLending::where('has_analyze', 1)->ValidAnalyzeProccess($request->session()->get('r_code'))->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'is_entry_exit',
            'request_r_code'
        ]);
        
        $array_input = putSession($request, $array_input);
        $filter_session = getSessionFilters();

        if($filter_session[0]->isNotEmpty()){

            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."code") {
                    $entry_exit->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."is_entry_exit") {
                    $entry_exit->where('is_entry_exit', $value_filter);
                }
                if($name_filter == $filter_session[1]."request_r_code") {
                    $entry_exit->where('request_r_code', 'like', '%'.$value_filter.'%');
                }
            }
        }

        $ac_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();
        $userall = Users::all();

        return view('gree_i.lending.financy_approv_lending', [
            'ac_bank' => $ac_bank,
            'lending' => $lending->paginate(10),
            'userall' => $userall,
        ]);
    }

    public function financyModuleView(Request $request, $id, $type) {

        if ($type == 1) {
            $trips = DB::table('trip_plan')
                ->leftJoin('trip_peoples', 'trip_plan.id', '=', 'trip_peoples.trip_plan_id')
                ->select(DB::raw('SUM(trip_plan.dispatch) as dispatch, count(trip_peoples.id) as peoples, trip_plan.*'))
                ->where('trip_plan.trip_id', $id)
                ->where('trip_plan.is_cancelled', 0)
                ->GroupBy('trip_plan.id')
                ->get();

            return view('gree_i.lending.financy_module_view', ['trips' => $trips, 'planid' => $id, 'perm' => $type]);
        } else {
            return "N/A";
        }
    }

    public function financyLendingReceiver(Request $request) {

        $debit = UserDebtors::orderBy('id', 'DESC')->paginate(10);

        return view('gree_i.lending.financy_debit_lending', [
            'debit' => $debit
        ]);

    }

    public function financyLendingLimit(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('lendingf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('lendingf_r_code');
        }

        $limit = UserFinancy::orderBy('id', 'DESC');

        if (!empty($request->session()->get('lendingf_r_code'))) {
            $limit->where('r_code', $request->session()->get('lendingf_r_code'));
        }

        $userall = Users::all();

        return view('gree_i.lending.financy_limit_lending', [
            'limit' => $limit->paginate(10),
            'userall' => $userall
        ]);

    }

    public function financyLendingLimit_do(Request $request) {

        $user_financy = UserFinancy::find($request->id);

        if ($user_financy) {

            if ($request->has('obs')) {
                $user_financy->obs = $request->obs;
                LogSystem("Colaborador adicionou uma observação ao ID: ". $request->id, $request->session()->get('r_code'));
            }

            if ($request->has('limit')) {
                $user_financy->limit_credit = $request->limit;
                LogSystem("Colaborador aumentou o limite em: ". $request->limit ." do ID: ". $request->id, $request->session()->get('r_code'));
            }


            $user_financy->save();
            return redirect()->back()->with('success', 'informações foram atualizadas com sucesso!');
        } else {

            return redirect()->back()->with('error', 'Não foi possível encontrar os dados da conta vinculada.');
        }

    }

    public function financyLendingAll(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('lendingf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('lendingf_r_code');
        }
        if (!empty($request->input('id'))) {
            $request->session()->put('lendingf_id', $request->input('id'));
        } else {
            $request->session()->forget('lendingf_id');
        }

        $lending = FinancyLending::orderByRaw('has_analyze DESC, id DESC');

        if (!empty($request->session()->get('lendingf_r_code'))) {
            $lending->where('r_code', $request->session()->get('lendingf_r_code'));
        }

        if (!empty($request->session()->get('lendingf_id'))) {
            $lending->where('code', 'like', '%'. $request->session()->get('lendingf_id') .'%');
        }

        $userall = Users::all();

        return view('gree_i.lending.financy_all_lending', [
            'lending' => $lending->paginate(10),
            'userall' => $userall
        ]);

    }

    public function financyLendingExportView(Request $request) {

        $userid = $request->session()->get('r_code');
        $manager = UserOnPermissions::where('perm_id', 9)
            ->where('user_r_code', $userid)
            ->where('grade', 99)
            ->orWhere(function ($query) use ($userid) {
                $query->where('user_r_code', $userid)
                    ->where('can_approv', 1)
                    ->where('perm_id', 9);
            })
            ->first();

        $colab = Users::all();

        return view('gree_i.lending.financy_export_lending', [
            'manager' => $manager,
            'colab' => $colab
        ]);
    }

    public function financyPermModule(Request $request) {

        $mdl = $request->mdl;

        $selected = [];
        $mudules = [
            1 => UserOnPermissions::where('perm_id', 9)->whereIn('grade',[11,12,99])->get(), // Empréstimo
            2 => UserOnPermissions::where('perm_id', 12)->whereIn('grade',[11,12,99])->get(), // Reembolso
            3 => UserOnPermissions::where('perm_id', 11)->whereIn('grade',[11,12,99])->get(), // Pagamento
            4 => UserOnPermissions::where('perm_id', 22)->whereIn('grade',[11,12,99])->get(), // Prestação de Contas
        ];

        if(array_key_exists($mdl, $mudules)){
            $permissoes = $mudules[$mdl];
            $permissoes->load('user');
            $selected = $permissoes->pluck('user');
        }else{
            abort(404);
        }

        $colab = Users::all();

        return view('gree_i.financy.perm_user', [
            'mdl' => $mdl,
            'colab' => $colab,
            'selected' => $selected
        ]);
    }

    public function financyPermModule_do(Request $request) {

        $perm_id = 0;
        if ($request->mdl == 1) {
            $perm_id = 9;
        } else if ($request->mdl == 2) {
            $perm_id = 12;
        } else if ($request->mdl == 3) {
            $perm_id = 11;
        } else if ($request->mdl == 4) {
            $perm_id = 22;
        }else{
            abort(400);
        }

        $this->updatePermModule($request->r_code, $perm_id);

        $request->session()->put('success', 'Atualização feita com sucesso!');
        return redirect()->back();
    }

    private function updatePermModule($r_code, $perm_id){
        if (count($r_code) > 0) {

            $perms = UserOnPermissions::where('perm_id', $perm_id)->where('grade', 99)->get();

            if (count($perms) > 0) {

                foreach ($perms as $key) {
                    $key->grade = 1;
                    $key->save();
                }
            }

            foreach ($r_code as $key) {

                $urs = UserOnPermissions::where('perm_id', $perm_id)->where('user_r_code', $key)->first();
                if ($urs) {

                    if ($urs->grade < 11) {
                        $urs->grade = 99;
                        $urs->save();
                    }

                }

            }

        }
    }

    public function financyLendingExport(Request $request) {

        $manager = UserOnPermissions::where('perm_id', 9)
            ->where('user_r_code', $request->session()->get('r_code'))
            ->where('grade', 99)
            ->orWhere(function ($query) use ($request) {
                $query->where('user_r_code', $request->session()->get('r_code'))
                    ->where('perm_id', 9)
                    ->where('can_approv', 1);
            })
            ->first();

        if (isset($manager)) {
            $r_code = $request->Input('r_code');
        } else {
            $r_code = $request->session()->get('r_code');
        }
        $status = $request->Input('status');
        $start_date = $request->Input('start_date');
        $end_date = $request->Input('end_date');

        LogSystem("Colaborador exportou dados da tabela emprestimos", $request->session()->get('r_code'));

        return Excel::download(new LendingExport($r_code, $status, $start_date, $end_date), 'Lendings'. date('Y-m-d H.s') .'.xlsx');

    }

    public function financyLendingBank(Request $request) {

        $rcode = $request->r_code ? $request->r_code : $request->session()->get('r_code');
        $ac_bank = UserFinancy::where('r_code', $rcode)->first();

        if ($ac_bank) {

            $ac_bank->agency = $request->agency;
            $ac_bank->account = $request->account;
            $ac_bank->bank = $request->bank;
            $ac_bank->identity = $request->identity;
            $ac_bank->save();

        } else {

            $ac_bank = new UserFinancy;
            $ac_bank->agency = $request->agency;
            $ac_bank->account = $request->account;
            $ac_bank->bank = $request->bank;
            $ac_bank->identity = $request->identity;
            $ac_bank->r_code = $rcode;
            $grade = Users::where('r_code', $rcode)
                ->first();

            if ($grade) {
                switch ($grade->sector_id) {
                    case 1:
                        $ac_bank->limit_credit = 5000.00;
                        break;
                    default:
                        $ac_bank->limit_credit = 2000.00;
                        break;
                }
            } else {
                $ac_bank->limit_credit = 2000.00;
            }

            $ac_bank->save();

        }

        return response()->json([
            'success' => true,
            'limit' => $ac_bank->limit_credit
        ]);
    }

    public function financyPaymentEdit(Request $request, $id) {

        if ($id == 0) {

            $request_category = 7;
            $sector = Sector::find($request->session()->get('sector'));
            $request_r_code = $request->session()->get('r_code');
            $nf_nmb = 'CONTABILIZADO';
            $description = 'REEMBOLSO DE DESPESAS COMERCIAIS';
            $recipient = '';
            $recipient_r_code = '';
            $receiver = '';
            $accounting = '';
            $supervisor = '';
            $total = '';
            $liquid = '';
            $date_end = '';
            $optional = '';
            $agency = '';
            $account = '';
            $bank = '';
            $identity = '';
            $cnpj = '';
            $payment_method = '';
            $is_paid = 0;
            $pres_approv = 0;
            $created_at = date('d/m/Y');

            $files = '';

        } else {

            $r_payment = FinancyRPayment::find($id);

            $request_category = $r_payment->request_category;
            $user = Users::where('r_code', $r_payment->request_r_code)->first();
            $sector = Sector::find($user->sector_id);
            $request_r_code = $r_payment->request_r_code;
            $nf_nmb = $r_payment->nf_nmb;
            $description = $r_payment->description;
            $recipient = $r_payment->recipient;
            $recipient_r_code = $r_payment->recipient_r_code;
            $total = $r_payment->amount_gross;
            $liquid = $r_payment->amount_liquid;

            $old_date = str_replace("-", "/", $r_payment->due_date);
            $due_date = date('d/m/Y', strtotime($old_date));
            $date_end = $due_date;

            $receiver = $r_payment->financy_receiver;
            $accounting = $r_payment->financy_accounting;
            $supervisor = $r_payment->financy_supervisor;

            $optional = $r_payment->optional;
            $agency = $r_payment->agency;
            $account = $r_payment->account;
            $bank = $r_payment->bank;
            $identity = $r_payment->identity;
            $cnpj = $r_payment->cnpj;
            $payment_method = $r_payment->p_method;
            $is_paid = $r_payment->is_paid;
            $pres_approv = $r_payment->pres_approv;
            $created_at = date('d/m/Y', strtotime($r_payment->created_at));

            $files = FinancyRPaymentAttach::where('financy_r_payment_id', $r_payment->id)->get();
        }

        $userall = Users::all();

        return view('gree_i.payment.payment_edit', [
            'id' => $id,
            'receiver' => $receiver,
            'accounting' => $accounting,
            'supervisor' => $supervisor,
            'pres_approv' => $pres_approv,
            'is_paid' => $is_paid,
            'request_category' => $request_category,
            'sector' => $sector,
            'request_r_code' => $request_r_code,
            'nf_nmb' => $nf_nmb,
            'description' => $description,
            'recipient' => $recipient,
            'recipient_r_code' => $recipient_r_code,
            'total' => $total,
            'liquid' => $liquid,
            'date_end' => $date_end,
            'optional' => $optional,
            'agency' => $agency,
            'account' => $account,
            'bank' => $bank,
            'identity' => $identity,
            'cnpj' => $cnpj,
            'payment_method' => $payment_method,
            'created_at' => $created_at,
            'files' => $files,
            'userall' => $userall,
            'sector_name' => __('layout_i.'. $sector->name .'')
        ]);
    }

    public function financyPaymentEdit_do(Request $request) {
        $files = $request->file('files');

        if ($request->id == 0) {
            LogSystem("Colaborador criou nova solicitação de pagamento.", $request->session()->get('r_code'));
            $r_payment = new FinancyRPayment;
        } else {
            LogSystem("Colaborador editou a solicitação de pagamento identificada por: ". $request->id, $request->session()->get('r_code'));
            $r_payment = FinancyRPayment::find($request->id);
        }

        if ($request->id == 0) {
            $r_payment->request_r_code = $request->session()->get('r_code');
            $r_payment->code = getCodeModule('payment');
        }
        $r_payment->nf_nmb = $request->nf_nmb;
        $r_payment->request_category = $request->request_category;
        $r_payment->description = $request->desc_request;
        $r_payment->has_analyze = 1;
        if ($request->recipient == 99) {
            $r_payment->recipient = $request->recipient_other;
            $r_payment->recipient_r_code = "";
        } else {
            $rec = Users::where('r_code', $request->recipient)->first();
            $r_payment->recipient_r_code = $rec->r_code;
            $r_payment->recipient = $rec->first_name .' '. $rec->last_name;
        }

        $source = array('.', ',');
        $replace = array('', '.');
        $total = str_replace($source, $replace, $request->input('amount-total'));
        $r_payment->amount_gross = $total;

        $old_date = str_replace("/", "-", $request->date_end);
        $due_date = date('Y-m-d', strtotime($old_date));
        $r_payment->due_date = $due_date;
        $total = str_replace($source, $replace, $request->input('amount-liquid'));
        $r_payment->amount_liquid = $total;
        $r_payment->p_method = $request->payment_method;
        $r_payment->optional = $request->optional;

        if ($request->supervisor != "" and $request->supervisor != "undefined") {
            $r_payment->financy_supervisor = $request->supervisor;
        }
        if ($request->accounting != "" and $request->accounting != "undefined") {
            $r_payment->financy_accounting = $request->accounting;
        }
        if ($request->receiver != "" and $request->receiver != "undefined") {
            $r_payment->financy_receiver = $request->receiver;
        }

        $r_payment->agency = $request->agency;
        $r_payment->account = $request->account;
        $r_payment->bank = $request->bank;
        $r_payment->identity = $request->identity;
        $r_payment->cnpj = $request->cnpj;
        $r_payment->save();

        // GEN CODE SEGMENT
        // codeSegmentBase($r_payment->id, 3, $request);

        if ($files) {
            $i = 1;
            FinancyRPaymentAttach::where('financy_r_payment_id', $r_payment->id)->delete();
            foreach ($files as $file_a) {
                $attach = new FinancyRPaymentAttach;
                $attach->name = $file_a->getClientOriginalName();
                $attach->size = $file_a->getSize();
                $attach->financy_r_payment_id = $r_payment->id;

                $img_name = $i .'-'. date('YmdHis') .'.'. $file_a->extension();
                $file_a->storeAs('/', $img_name, 's3');
                $url = Storage::disk('s3')->url($img_name);
                $attach->url = $url;
                $attach->save();
                $i++;
            }
        }

        if ($request->id == 0) {
            $immediate = UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
                ->select('users.*', 'user_immediate.*')
                ->where('user_r_code', $request->session()->get('r_code'))
                ->get();

            $r_user = Users::where('r_code', $request->session()->get('r_code'))->first();

            $pattern = array(
                'id' => $r_payment->id,
                'immediates' => $immediate,
                'title' => 'PEDIDO FOI REALIZADO',
                'description' => '',
                'template' => 'payment.Success',
                'subject' => 'Pedido de pagamento: #'. $r_payment->id,
            );

            SendMailJob::dispatch($pattern, $r_user->email);


            if (count($immediate) > 0) {

                $pattern = array(
                    'id' => $r_payment->id,
                    'sector_id' => $r_user->sector_id,
                    'r_code' => $r_user->r_code,
                    'content' => $r_payment->description,
                    'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                    'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                    'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                    'method' => $r_payment->p_method,
                    'optional' => $r_payment->optional,
                    'title' => 'APROVAÇÃO DE PAGAMENTO',
                    'description' => '',
                    'template' => 'payment.Analyze',
                    'subject' => 'APROVAÇÃO DE PAGAMENTO',
                );

                foreach ($immediate as $key) {

                    $imdt = Users::where('r_code', $key->immediate_r_code)->first();

                    if ($imdt->is_holiday == 1) {
                        $usrhd = \App\Model\UserHoliday::where('user_r_code', $imdt->r_code)->get();
                        foreach($usrhd as $usr) {

                            $imdt = Users::where('r_code', $usr->receiver_r_code)->first();

                            // send email
                            SendMailJob::dispatch($pattern, $imdt->email);
                            App::setLocale($imdt->lang);
                            NotifyUser(__('layout_i.n_payment_001_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_payment_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]), $request->root() .'/financy/payment/request/approv/'. $r_payment->id);
                            App::setLocale($request->session()->get('lang'));
                        }
                    } else {

                        // send email
                        SendMailJob::dispatch($pattern, $imdt->email);
                        App::setLocale($imdt->lang);
                        NotifyUser(__('layout_i.n_payment_001_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_payment_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]), $request->root() .'/financy/payment/request/approv/'. $r_payment->id);
                        App::setLocale($request->session()->get('lang'));
                    }

                }

            }

            LogSystem("Colaborador enviou solicitação de pagamento para análise. identificado por #". $r_payment->id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Nova solicitação de pagamento realizada e enviada para análise.');
            return redirect('/financy/payment/my');
        } else {
            LogSystem("Colaborador editou a solicitação de pagamento. identificado por #". $r_payment->id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Solicitação de pagamento foi atualizada com sucesso!');
            return redirect('/financy/payment/edit/'. $r_payment->id);
        }
    }

    public function financyPaymentSupervisoList(Request $request) {

        $nfs = DB::table('financy_r_payment_nf')
            ->orderBy('financy_r_payment_nf.id', 'DESC')
            ->paginate(10);

        return view('gree_i.payment.payment_antecipated_supervisor', [
            'nfs' => $nfs,
        ]);

    }

    public function financyPaymentSupervisoEdit(Request $request, $id) {

        $perm = UserOnPermissions::where('user_on_permissions.can_approv', 1)
            ->where('user_on_permissions.grade', 12)
            ->where('user_on_permissions.perm_id', 11)
            ->where('user_r_code', $request->session()->get('r_code'))
            ->first();

        if (!$perm) {

            $request->session()->put('error', 'Apenas verificado fiscal tem permissão para ver essa página.');
            return redirect('/news');

        }

        if ($id == 0) {

            $nf = new FinancyRPaymentNf;

            $nf_number = "";
            $nf_attach = "";
            $status = 1;
            $description = "";

        } else {

            $nf = FinancyRPaymentNf::find($id);

            $nf_number = $nf->nf_number;
            $nf_attach = $nf->nf_attach;
            $status = $nf->is_approv == 1 ? 1 : 2;
            $description = $nf->description;
        }

        return view('gree_i.payment.payment_antecipated_supervisor_edit', [
            'id' => $id,
            'nf_number' => $nf_number,
            'nf_attach' => $nf_attach,
            'status' => $status,
            'description' => $description,
        ]);
    }

    public function financyPaymentSupervisoDelete(Request $request) {

        $nf = FinancyRPaymentNf::find($request->id);

        if ($nf) {

            FinancyRPaymentNf::where('id', $request->id)->delete();

            LogSystem("Colaborador deletou a análise de nota fiscal antecipada ID ". $request->id, $request->session()->get('r_code'));
            $request->session()->put('success', "Você deletou a nota fiscal com sucesso!");
            return redirect('/financy/payment/supervisor/approv');

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return redirect('/news');

        }
    }

    public function financyPaymentSupervisoUpdate(Request $request) {

        if ($request->id == 0) {

            $nf = new FinancyRPaymentNf;
        } else {

            $nf = FinancyRPaymentNf::find($request->id);
        }

        $nf->nf_number = $request->nf_number;
        if ($request->hasFile('nf_attach')) {
            $response = $this->uploadS3(1, $request->nf_attach, $request);
            if ($response['success']) {
                $nf->nf_attach = $response['url'];
            } else {
                return Redirect('/financy/payment/supervisor/approv');
            }
        }
        $nf->is_approv = $request->status == 1 ? 1 : 0;
        $nf->is_repprov = $request->status == 2 ? 1 : 0;
        $nf->r_code = $request->session()->get('r_code');
        $nf->description = $request->description;
        $nf->save();


        if ($request->id == 0) {

            LogSystem("Colaborador atualizou a análise de nota fiscal antecipada ID ". $request->id, $request->session()->get('r_code'));
        } else {

            LogSystem("Colaborador criou uma nova análise de nota fiscal antecipada ID ". $request->id, $request->session()->get('r_code'));
        }
        $request->session()->put('success', "Você atualizou sua lista de nota fiscal com sucesso!");
        return redirect('/financy/payment/supervisor/approv');
    }

    public function financyPaymentMy(Request $request) {

        $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
            ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
            ->select('financy_r_payment.*', 'r.first_name as r_first_name', 'r.last_name as r_last_name', 'b.first_name as b_first_name', 'b.last_name as b_last_name')
            ->where('financy_r_payment.request_r_code', $request->session()->get('r_code'))
            ->orderBy('financy_r_payment.id', 'DESC')
            ->paginate(10);

        return view('gree_i.payment.payment_my', [
            'payment' => $payment,
        ]);
    }

    public function financyPaymentTransfer(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('paymentf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('paymentf_r_code');
        }
        if (!empty($request->input('id'))) {
            $request->session()->put('paymentf_id', $request->input('id'));
        } else {
            $request->session()->forget('paymentf_id');
        }


        $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
            ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
            ->select('financy_r_payment.*', 'r.first_name as r_first_name', 'r.last_name as r_last_name', 'b.first_name as b_first_name', 'b.last_name as b_last_name')
            ->where('financy_r_payment.is_approv', 1)
            ->where('financy_r_payment.is_paid', 0)
            ->orderBy('financy_r_payment.due_date', 'ASC')
            ->orderBy('financy_r_payment.amount_liquid', 'DESC');

        if (!empty($request->session()->get('paymentf_r_code'))) {
            $payment->where('r.r_code', $request->session()->get('paymentf_r_code'));
        }
        if (!empty($request->session()->get('paymentf_id'))) {
            $payment->where('financy_r_payment.code', 'like', '%'. $request->session()->get('paymentf_id') .'%');
        }

        $userall = Users::all();
        return view('gree_i.payment.payment_transfer', [
            'payment' => $payment->paginate(10),
            'userall' => $userall,
        ]);

    }

    public function financyPaymentTransfer_do(Request $request) {

        $r_payment = FinancyRPayment::find($request->payment_id);
        $attach = $request->attach;
        $url = "";

        if ($r_payment) {

            if ($request->hasFile('attach')) {
                $extension = $request->attach->extension();
                if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf') {

                    $validator = Validator::make(
                        [
                            'attach' => $attach,
                        ],
                        [
                            'attach' => 'required|max:1000',
                        ]
                    );

                    if ($validator->fails()) {

                        $request->session()->put('error', "Tamanho máximo da imagem é de 1mb, diminua a resolução/tamanho da mesma.");
                        return Redirect('/financy/payment/transfer');
                    } else {

                        $img_name = $r_payment->id .'-'. date('YmdHis') .'.'. $extension;

                        $request->attach->storeAs('/', $img_name, 's3');
                        $url = Storage::disk('s3')->url($img_name);
                        $r_payment->receipt = $url;
                    }

                } else {

                    $request->session()->put('error', "o formato: (". $extension .") da imagem não é suportado em nosso servidor.");
                    return Redirect('/financy/payment/transfer');
                }
            }

            $r_payment->is_paid = 1;           
            $r_payment->save();

            $relation = $r_payment->relationship();

            if ($relation) {
                $relation->is_paid = 1;
                $relation->receipt = $r_payment->receipt;
                $relation->save();

                // UPDATE RECEIPT IN OTHER MODULES
                $rules = new \App\Helpers\ModuleRules(get_class($relation));

                // Adição de model
                $rules->ModuleRulesFinancyPaymentTransfer($request, $r_payment, $relation, $relation);
            }

            
            $col = Users::where('r_code', $r_payment->request_r_code)->first();

            $financeiro_cc = ['joao.rocha@gree-am.com.br','simone@gree-am.com.br'];

            if ($request->amount != '0.00' or !empty($request->amount)) {

                $refund = FinancyRefund::find($r_payment->module_id);

                if ($refund) {

                    if ($refund->lending >= $r_payment->amount_liquid) {

                        $pattern = array(
                            'id' => $r_payment->id,
                            'title' => 'PAGAMENTO #ID '. $r_payment->id .' AVISO',
                            'description' => nl2br("Por conta da sua pendência no valor de: R$ ". number_format($request->amount, 2, ',', '.') ." seu débito com a Gree ficou em: R$ ". number_format($r_payment->amount_liquid, 2, ',', '.')),
                            'template' => 'misc.Default',
                            'subject' => 'Aviso do pagamento: #'. $r_payment->id,
                        );

                    } else {

                        $pattern = array(
                            'id' => $r_payment->id,
                            'payment' => $r_payment,
                            'receipt' => $r_payment->receipt,
                            'user' => $col,
                            'title' => 'TRANSFERÊNCIA REALIZADA',
                            'copys' => ['glauco.leao@gree-am.com.br', 'joao.rocha@gree-am.com.br', 'simone@gree-am.com.br'],
                            'description' => nl2br($request->description),
                            'template' => 'payment.TransferSuccess',
                            'subject' => 'Atualização no pagamento: #'. $r_payment->id,
                        );

                    }

                } else {

                    $pattern = array(
                        'id' => $r_payment->id,
                        'payment' => $r_payment,
                        'receipt' => $r_payment->receipt,
                        'user' => $col,
                        'title' => 'TRANSFERÊNCIA REALIZADA',
                        'copys' => ['glauco.leao@gree-am.com.br', 'joao.rocha@gree-am.com.br', 'simone@gree-am.com.br'],
                        'description' => nl2br($request->description),
                        'template' => 'payment.TransferSuccess',
                        'subject' => 'Atualização no pagamento: #'. $r_payment->id,
                    );

                }

            }

            SendMailJob::dispatch($pattern, $col->email);

            App::setLocale($col->lang);
            NotifyUser(__('layout_i.n_payment_005_title'), $col->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_payment_005', ['amount' => '#'. number_format($r_payment->amount_liquid, 2, ",", "."), 'id' => $r_payment->id]), $request->root() .'/financy/payment/request/print/'. $r_payment->id);
            App::setLocale($request->session()->get('lang'));

            LogSystem("Colaborador transferiu quantia R$ ". $r_payment->amount_liquid . " ID do pagamento ". $r_payment->id, $request->session()->get('r_code'));

            $request->session()->put('success', "Você confirmou a transferência do pagamento com sucesso!");
            return redirect('/financy/payment/transfer');
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return redirect('/news');
        }

    }

    public function financyPaymentAll(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('paymentf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('paymentf_r_code');
        }
        if (!empty($request->input('id'))) {
            $request->session()->put('paymentf_id', $request->input('id'));
        } else {
            $request->session()->forget('paymentf_id');
        }
        if (!empty($request->input('nf'))) {
            $request->session()->put('paymentf_nf', $request->input('nf'));
        } else {
            $request->session()->forget('paymentf_nf');
        }

        $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
            ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
            ->select('financy_r_payment.*', 'r.first_name as r_first_name', 'r.last_name as r_last_name', 'b.first_name as b_first_name', 'b.last_name as b_last_name')
            ->orderByRaw('financy_r_payment.has_analyze DESC, financy_r_payment.id DESC');

        if (!empty($request->session()->get('paymentf_r_code'))) {
            $payment->where('r.r_code', $request->session()->get('paymentf_r_code'));
        }
        if (!empty($request->session()->get('paymentf_id'))) {
            $payment->where('financy_r_payment.code', 'like', '%'. $request->session()->get('paymentf_id') .'%');
        }
        if (!empty($request->session()->get('paymentf_nf'))) {
            $payment->where('financy_r_payment.nf_nmb', $request->session()->get('paymentf_nf'));
        }

        $userall = Users::all();

        return view('gree_i.payment.payment_all', [
            'payment' => $payment->paginate(10),
            'userall' => $userall,
        ]);
    }

    public function financyPaymentExportView(Request $request) {

        $colab = Users::all();

        return view('gree_i.payment.payment_export', [
            'colab' => $colab
        ]);
    }

    public function financyPaymentExport(Request $request) {


        $r_code = $request->Input('r_code');
        $request_category = $request->Input('request_category');
        $start_date = $request->Input('start_date');
        $end_date = $request->Input('end_date');

        LogSystem("Colaborador exportou dados da tabela pagamentos", $request->session()->get('r_code'));

        return Excel::download(new PaymentExport($r_code, $request_category, $start_date, $end_date), 'Payments'. date('Y-m-d H.s') .'.xlsx');

    }

    public function financyPaymentApprov(Request $request) {

        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 11)->first();

        if (!empty($request->input('r_code'))) {
            $request->session()->put('paymentf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('paymentf_r_code');
        }
        if (!empty($request->input('id'))) {
            $request->session()->put('paymentf_id', $request->input('id'));
        } else {
            $request->session()->forget('paymentf_id');
        }
        if (!empty($request->input('nf'))) {
            $request->session()->put('paymentf_nf', $request->input('nf'));
        } else {
            $request->session()->forget('paymentf_nf');
        }

        $imdts = [];
        $hd_imdt = \App\Model\UserHoliday::where('receiver_r_code', $request->session()->get('r_code'))->get();

        foreach ($hd_imdt as $hd_imd) {

            $collect = collect(json_decode($hd_imd->receiver_perm, true));

            if ($collect->where('perm_id', 11)->first()) {
                $imdts = array_merge($imdts, [$hd_imd->user_r_code]);
            }
        }

        if ($permission->grade == 10 or validHoliday(11, 10, null)) {
            $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
                ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
                ->select('financy_r_payment.*', 'r.first_name as r_first_name', 'r.last_name as r_last_name', 'b.first_name as b_first_name', 'b.last_name as b_last_name')
                ->Where(function ($q) use ($request, $imdts) {
                    $q->where('financy_r_payment.mng_reprov', 0)
                        ->where('financy_r_payment.mng_approv', 1)
                        ->where('financy_r_payment.financy_approv', 1)
                        ->where('financy_r_payment.financy_reprov', 0)
                        ->where('financy_r_payment.pres_reprov', 0)
                        ->where('financy_r_payment.pres_approv', 0)
                        ->where('financy_r_payment.has_analyze', 1)
                        ->orWhere(function ($query) use ($request, $imdts) {
                            $query->where(function ($subq) use ($request, $imdts) {
                                $subq->whereExists(function ($subquery) use ($request) {
                                    $subquery->select(DB::raw(1))
                                        ->from('user_immediate')
                                        ->where('user_immediate.immediate_r_code', '=', $request->session()->get('r_code'))
                                        ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                                })->orWhere(function ($subquery1) use ($request, $imdts) {
                                    $subquery1->whereExists(function ($subquery) use ($request, $imdts) {
                                        $subquery->select(DB::raw(1))
                                            ->from('user_immediate')
                                            ->whereIn('user_immediate.immediate_r_code', $imdts)
                                            ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                                    });
                                });
                            })
                                ->where('financy_r_payment.has_analyze', 1)
                                ->where('financy_r_payment.mng_approv', 0)
                                ->where('financy_r_payment.mng_reprov', 0)
                                ->where('financy_r_payment.financy_approv', 0)
                                ->where('financy_r_payment.financy_reprov', 0)
                                ->where('financy_r_payment.pres_approv', 0)
                                ->where('financy_r_payment.pres_reprov', 0);
                        });
                })
                //->groupBy('financy_r_payment.request_r_code')
                ->orderBy('financy_r_payment.id', 'DESC');

        } else if ($permission->grade == 99 or validHoliday(11, 99, null)) {
            $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
                ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
                ->select('financy_r_payment.*', 'r.first_name as r_first_name', 'r.last_name as r_last_name', 'b.first_name as b_first_name', 'b.last_name as b_last_name')
                ->Where(function ($q) use ($request, $imdts) {
                    $q->where('financy_r_payment.mng_approv', 1)
                        ->where('financy_r_payment.mng_reprov', 0)
                        ->where('financy_r_payment.financy_supervisor', '!=', null)
                        ->where('financy_r_payment.financy_accounting', '!=', null)
                        ->where('financy_r_payment.financy_approv', 0)
                        ->where('financy_r_payment.financy_reprov', 0)
                        ->where('financy_r_payment.pres_approv', 0)
                        ->where('financy_r_payment.pres_reprov', 0)
                        ->where('financy_r_payment.has_analyze', 1)
                        ->orWhere(function ($query) use ($request, $imdts) {
                            $query->where(function ($subq) use ($request, $imdts) {
                                $subq->whereExists(function ($subquery) use ($request) {
                                    $subquery->select(DB::raw(1))
                                        ->from('user_immediate')
                                        ->where('user_immediate.immediate_r_code', '=', $request->session()->get('r_code'))
                                        ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                                })->orWhere(function ($subquery1) use ($request, $imdts) {
                                    $subquery1->whereExists(function ($subquery) use ($request, $imdts) {
                                        $subquery->select(DB::raw(1))
                                            ->from('user_immediate')
                                            ->whereIn('user_immediate.immediate_r_code', $imdts)
                                            ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                                    });
                                });
                            })
                                ->where('financy_r_payment.has_analyze', 1)
                                ->where('financy_r_payment.mng_approv', 0)
                                ->where('financy_r_payment.mng_reprov', 0)
                                ->where('financy_r_payment.financy_approv', 0)
                                ->where('financy_r_payment.financy_reprov', 0)
                                ->where('financy_r_payment.pres_approv', 0)
                                ->where('financy_r_payment.pres_reprov', 0);
                        })
                        ->orWhere(function ($query) use ($request) {
                            $query->where('financy_r_payment.mng_approv', 1)
                                ->where('financy_r_payment.mng_reprov', 0)
                                ->where('financy_r_payment.financy_approv', 0)
                                ->where('financy_r_payment.financy_reprov', 0)
                                ->whereRaw('financy_r_payment.due_date < (financy_r_payment.created_at + INTERVAL 7 DAY)')
                                ->where('financy_r_payment.pres_approv', 0)
                                ->where('financy_r_payment.pres_reprov', 0)
                                ->where('financy_r_payment.has_analyze', 1);
                        });
                })
                // ->groupBy('financy_r_payment.request_r_code')
                ->orderBy('financy_r_payment.id', 'DESC');

        } else if ($permission->grade == 11 or validHoliday(11, 11, null)) {
            $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
                ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
                ->selectRaw('financy_r_payment.*, r.first_name as r_first_name, r.last_name as r_last_name, b.first_name as b_first_name, b.last_name as b_last_name')
                ->where('mng_approv', 1)
                ->where('mng_reprov', 0)
                ->whereRaw('financy_r_payment.due_date >= (financy_r_payment.created_at + INTERVAL 7 DAY)')
                ->where('financy_supervisor', '!=', null)
                ->where('financy_accounting', null)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)
                ->where('pres_approv', 0)
                ->where('pres_reprov', 0)
                ->where('has_analyze', 1)
                ->orderBy('id', 'DESC');

        } else if ($permission->grade == 12 or validHoliday(11, 12, null)) {
            $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
                ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
                ->selectRaw('financy_r_payment.*, r.first_name as r_first_name, r.last_name as r_last_name, b.first_name as b_first_name, b.last_name as b_last_name')
                ->where('mng_approv', 1)
                ->where('mng_reprov', 0)
                ->whereRaw('financy_r_payment.due_date >= (financy_r_payment.created_at + INTERVAL 7 DAY)')
                ->where('financy_supervisor', null)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)
                ->where('pres_approv', 0)
                ->where('pres_reprov', 0)
                ->where('has_analyze', 1)
                ->orderBy('id', 'DESC');

        } else {
            $payment = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
                ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
                ->select('financy_r_payment.*', 'r.first_name as r_first_name', 'r.last_name as r_last_name', 'b.first_name as b_first_name', 'b.last_name as b_last_name')
                ->where(function ($subq) use ($request, $imdts) {
                    $subq->whereExists(function ($subquery) use ($request) {
                        $subquery->select(DB::raw(1))
                            ->from('user_immediate')
                            ->where('user_immediate.immediate_r_code', '=', $request->session()->get('r_code'))
                            ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                    })->orWhere(function ($subquery1) use ($request, $imdts) {
                        $subquery1->whereExists(function ($subquery) use ($request, $imdts) {
                            $subquery->select(DB::raw(1))
                                ->from('user_immediate')
                                ->whereIn('user_immediate.immediate_r_code', $imdts)
                                ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                        });
                    });
                })
                ->where('financy_r_payment.has_analyze', 1)
                ->where('financy_r_payment.mng_approv', 0)
                ->where('financy_r_payment.mng_reprov', 0)
                ->where('financy_r_payment.financy_approv', 0)
                ->where('financy_r_payment.financy_reprov', 0)
                ->where('financy_r_payment.pres_approv', 0)
                ->where('financy_r_payment.pres_reprov', 0)
                ->orderBy('financy_r_payment.id', 'DESC');
        }

        if (!empty($request->session()->get('paymentf_r_code'))) {
            $payment->where('r.r_code', $request->session()->get('paymentf_r_code'));
        }
        if (!empty($request->session()->get('paymentf_id'))) {
            $payment->where('financy_r_payment.code', 'like', '%'. $request->session()->get('paymentf_id') .'%');
        }
        if (!empty($request->session()->get('paymentf_nf'))) {
            $payment->where('financy_r_payment.nf_nmb', $request->session()->get('paymentf_nf'));
        }

        $userall = Users::all();


        return view('gree_i.payment.payment_approv', [
            'payment' => $payment->paginate(10),
            'userall' => $userall
        ]);
    }

    public function financyPaymentPrint(Request $request, $id) {

        if ($id == 0) {

            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');

        } else {

            $r_payment = FinancyRPayment::with('users', 'financy_r_payment_attach')->whereHas('users')->where('id', $id)->first();

            if (!$r_payment) {
                $request->session()->put('error', __('layout_i.not_permissions'));
                return \Redirect::route('news');
            }

            $relationship = $r_payment->relationship();
            if ($relationship) {
                $rtd_status = $relationship->rtd_status;
                $mark_position = $rtd_status['versions'][$relationship->version]['mark'];
            } else {
                $rtd_status = $r_payment->rtd_status;
				if (!isset($rtd_status['versions'][$r_payment->version]))
					return redirect()->back()->with('error', 'Solicitação ainda não disponível para uso.');
					
                $mark_position = $rtd_status['versions'][$r_payment->version]['mark'];
            }

            return view('gree_i.payment.payment_print', [
                'relationship' => $relationship,
                'mark_position' => $mark_position,
                'r_payment' => $r_payment,
                'id' => $id,
            ]);
        }
    }

    public function financyPaymentApprovView(Request $request, $id) {
		
		return redirect('/financy/accountability/edit/'.$id);

        if ($id == 0) {

            $request->session()->put('error', __('layout_i.not_permissions'));
            return \Redirect::route('news');

        } else {

            $r_payment = FinancyRPayment::with('users', 'financy_r_payment_attach')->whereHas('users')->where('id', $id)->first();

            if (!$r_payment) {
                $request->session()->put('error', __('layout_i.not_permissions'));
                return \Redirect::route('news');
            }

            $relationship = $r_payment->relationship();
            if ($relationship) {
                $rtd_status = $relationship->rtd_status;
                $mark_position = $rtd_status['versions'][$relationship->version]['mark'];
            } else {
                $rtd_status = $r_payment->rtd_status;
                $mark_position = $rtd_status['versions'][$r_payment->version]['mark'];
            }

            return view('gree_i.payment.payment_approv_view', [
                'relationship' => $relationship,
                'mark_position' => $mark_position,
                'r_payment' => $r_payment,
                'id' => $id,
            ]);
        }
    }

    /**
     * @method="financyPaymentAnalyze"
     * @param $type nome metodo
     * Esta função realiza o processo de analise e aprovação/reprovação deste o gestor ao presidente
     * de acordo com as regras de cada modulo
     */
    public function financyPaymentAnalyze(Request $request, $id, $type) {

        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 11)->first();
        $r_payment = FinancyRPayment::find($id);
        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {
            if ($r_payment) {

                //As regras de Analise do Financeiro Foram encapsuladas na classe: RulesFinancyRPayment
                $financyRules = new \App\Helpers\RulesFinancyRPayment;
                $financyRules = $financyRules->RulesFinancyPaymentAnalyze($request, $r_payment, $type);

                //caso exista um retorno na função, como um redirect
                if($financyRules){
                    return $financyRules;
                }

                //Executa uma determinada Regra de Acordo com o Modulo que gerou o Pagamento
                $moduleRules = new \App\Helpers\ModuleRules($r_payment->module_type);
                $moduleRules = $moduleRules->ModuleRulesfinancyPaymentAnalyze($request, $r_payment, $type);

                if($moduleRules){
                    return $moduleRules;
                }

                $txt_analyze = $type == 1 ? "aprovou" : "reprovou";

                if ($r_payment->module_type == 4)
                    return redirect('/financy/accountability/approv')->with('success', "Você ". $txt_analyze ." a solicitação de prestação de contas com sucesso!");
                else
                    return redirect('/financy/payment/approv')->with('success', "Você ". $txt_analyze ." a solicitação de pagamento com sucesso!");

            } else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }
        } else {
            if ($user->retry > 0) {
                $user->retry = $user->retry - 1;

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Pagamento) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                    return redirect('/logout');
                } else {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->save();

                    $request->session()->put('error', "You missed your secret password, only ". $user->retry ." attempt(s) left.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Pagamento). Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return redirect('/financy/payment/request/approv/'. $id);
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar (Pagamento) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                return redirect('/financy/payment/request/approv/'. $id);
            }
        }


    }
    /*
    public function financyPaymentAnalyze(Request $request, $id, $type) {

        $permission = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))->where('perm_id', 11)->first();
        $r_payment = FinancyRPayment::find($id);
        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {
            if ($r_payment) {
                if ($type == 1) {

                    if ($permission->grade == 10 and $r_payment->financy_approv == 1) {
                        $f_analyze = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)->first();
                        if (!$f_analyze) {
                            $request->session()->put('error', "Financeiro ainda não realizou análise desse pedido.");
                            return redirect('/financy/payment/approv');
                        } else if ($r_payment->pres_approv == 1) {
                            $request->session()->put('error', "Solicitação de pagamento, já foi aprovada!");
                            return redirect('/financy/payment/approv');
                        }
                        $r_payment->pres_approv = 1;
                        $r_payment->financy_approv = 1;
                        $r_payment->mng_approv = 1;
                        $r_payment->has_analyze = 0;

                        $analyze = new FinancyRPaymentPresAnalyze;
                        $analyze->financy_payment_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_approv = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $a_bank = UserFinancy::where('r_code', $r_payment->request_r_code)->first();

                        $r_user = Users::where('r_code', $r_payment->request_r_code)->first();

                        $payment = FinancyRPayment::find($r_payment->id);

                        $pattern = array(
                            'id' => $r_payment->id,
                            'r_p_id' => $r_payment->id,
                            'payment' => $payment,
                            'user' => $r_user,
                            'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                            'title' => 'PEDIDO APROVADO',
                            'description' => '',
                            'template' => 'payment.HasApprov',
                            'subject' => 'Pedido de reembolso aprovado: #'. $r_payment->id,
                        );

                        SendMailJob::dispatch($pattern, $r_user->email);

                        App::setLocale($r_user->lang);
                        NotifyUser(__('layout_i.n_payment_002_title'), $r_user->r_code, 'fa-check', 'text-success', __('layout_i.n_payment_002', ['id' => $r_payment->id]), $request->root() .'/financy/payment/my');
                        App::setLocale($request->session()->get('lang'));

                    } else if ($permission->grade == 99 and $r_payment->mng_approv == 1 or $permission->grade == 11 and $r_payment->mng_approv == 1 or $permission->grade == 12 and $r_payment->mng_approv == 1) {
                        if (FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)->count() == 3) {
                            $request->session()->put('error', "Essa solicitação já foi aprovado pelo financeiro.");
                            return redirect('/financy/payment/approv');
                        }

                        if (!$r_payment->financy_supervisor and $permission->grade == 12 and $r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days'))) {
                            $r_payment->financy_supervisor = $request->session()->get('r_code');

                            $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                ->select('users.*')
                                                ->where('user_on_permissions.can_approv', 1)
                                                ->where('user_on_permissions.grade', 11)
                                                ->where('user_on_permissions.perm_id', 11)
                                                ->get();

                        } else if (!$r_payment->financy_accounting and $r_payment->financy_supervisor != null and $permission->grade == 11 and $r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days'))) {
                            $r_payment->financy_accounting = $request->session()->get('r_code');

                            $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                ->select('users.*')
                                                ->where('user_on_permissions.can_approv', 1)
                                                ->where('user_on_permissions.grade', 99)
                                                ->where('user_on_permissions.perm_id', 11)
                                                ->get();

                        } else {
                            if ($permission->grade != 99 and $permission->can_approv != 1) {
                                $request->session()->put('error', "Apenas o gerente financeiro pode liberar essa solicitação.");
                                return redirect('/financy/payment/approv');
                            }

                            $r_payment->financy_approv = 1;
                            $r_payment->mng_approv = 1;

                            $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                ->select('users.*')
                                                ->where('user_on_permissions.can_approv', 1)
                                                ->where('user_on_permissions.grade', 10)
                                                ->where('user_on_permissions.perm_id', 11)
                                                ->get();

                        }

                        if ($request->receiver != "" and $request->receiver != "undefined") {
                            $r_payment->financy_receiver = $request->receiver;
                        }
                        $analyze = new FinancyRPaymentFnyAnalyze;
                        $analyze->financy_payment_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_approv = 1;
                        $analyze->description = $request->description;
                        $analyze->save();


                        $r_user = Users::where('r_code', $r_payment->request_r_code)->first();

                        $pattern = array(
                            'id' => $r_payment->id,
                            'sector_id' => $r_user->sector_id,
                            'r_code' => $r_user->r_code,
                            'content' => $r_payment->description,
                            'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                            'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                            'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                            'method' => $r_payment->p_method,
                            'optional' => $r_payment->optional,
                            'title' => 'APROVAÇÃO DE PAGAMENTO',
                            'description' => '',
                            'template' => 'payment.Analyze',
                            'subject' => 'APROVAÇÃO DE PAGAMENTO',
                        );

                        foreach ($immediate as $key) {

                            $imdt = Users::where('r_code', $key->r_code)->first();

                            // send email
                            SendMailJob::dispatch($pattern, $imdt->email);

                            App::setLocale($imdt->lang);
                            NotifyUser(__('layout_i.n_payment_001_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_payment_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]), $request->root() .'/financy/payment/my');
                            App::setLocale($request->session()->get('lang'));

                        }
                    } else {
                        if (FinancyRPaymentMngAnalyze::where('financy_payment_id', $id)->count() > 0) {
                            $request->session()->put('error', "Essa solicitação já foi aprovado pelo gestor.");
                            return redirect('/financy/payment/approv');
                        }
                        $r_payment->mng_approv = 1;

                        $analyze = new FinancyRPaymentMngAnalyze;
                        $analyze->financy_payment_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_approv = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $r_user = Users::where('r_code', $r_payment->request_r_code)->first();
                        $pattern = array(
                            'id' => $r_payment->id,
                            'sector_id' => $r_user->sector_id,
                            'r_code' => $r_user->r_code,
                            'content' => $r_payment->description,
                            'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                            'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                            'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                            'method' => $r_payment->p_method,
                            'optional' => $r_payment->optional,
                            'title' => 'APROVAÇÃO DE PAGAMENTO',
                            'description' => '',
                            'template' => 'payment.Analyze',
                            'subject' => 'APROVAÇÃO DE PAGAMENTO',
                        );

                        // VERIFY IF HAS NF PENDING
                        $nf = FinancyRPaymentNf::where('nf_number', $r_payment->nf_nmb)->where('financy_r_payment_id', 0)->first();


                        if ($r_payment->due_date >= date('Y-m-d', strtotime($r_payment->created_at .'+ 7 days'))) {

                            if ($nf) {

                                if ($nf->is_approv == 1) {
                                    $r_payment->financy_supervisor = $nf->r_code;

                                    $analyze = new FinancyRPaymentFnyAnalyze;
                                    $analyze->financy_payment_id = $id;
                                    $analyze->r_code = $nf->r_code;
                                    $analyze->is_approv = 1;
                                    $analyze->description = $nf->description;
                                    $analyze->save();

                                    $nf->financy_r_payment_id = $r_payment->id;
                                    $nf->save();

                                    $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                        ->select('users.*')
                                                        ->where('user_on_permissions.can_approv', 1)
                                                        ->where('user_on_permissions.grade', 11)
                                                        ->where('user_on_permissions.perm_id', 11)
                                                        ->get();
                                } else {
                                    $n_r_payment = FinancyRPayment::find($r_payment->id);
                                    $n_r_payment->financy_reprov = 1;

                                    $analyze = new FinancyRPaymentFnyAnalyze;
                                    $analyze->financy_payment_id = $id;
                                    $analyze->r_code = $nf->r_code;
                                    $analyze->is_reprov = 1;
                                    $analyze->description = $nf->description;
                                    $analyze->save();

                                    $n_r_payment->has_analyze = 0;
                                    $n_r_payment->has_suspended = 0;
                                    $n_r_payment->save();

                                    $nf->financy_r_payment_id = $r_payment->id;
                                    $nf->save();

                                    $immediate = [];

                                    $r_user = Users::where('r_code', $r_payment->request_r_code)->first();
                                    $pattern = array(
                                        'id' => $r_payment->id,
                                        'sector_id' => $r_user->sector_id,
                                        'r_code' => $r_user->r_code,
                                        'content' => $r_payment->description,
                                        'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                                        'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                                        'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                                        'method' => $r_payment->p_method,
                                        'optional' => $r_payment->optional,
                                        'title' => 'PAGAMENTO REPROVADO',
                                        'description' => '',
                                        'template' => 'payment.HasReprov',
                                        'subject' => 'PAGAMENTO REPROVADO',
                                    );

                                    SendMailJob::dispatch($pattern, $r_user->email);
                                    App::setLocale($r_user->lang);
                                    NotifyUser(__('layout_i.n_payment_003_title'), $r_user->r_code, 'fa-times', 'text-danger', __('layout_i.n_payment_003', ['amount' => $r_payment->amout_liquid, 'id' => $r_payment->id]), $request->root() .'/financy/payment/my/');
                                    App::setLocale($request->session()->get('lang'));

                                }
                            } else {

                                $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                ->select('users.*')
                                                ->where('user_on_permissions.can_approv', 1)
                                                ->where('user_on_permissions.grade', 12)
                                                ->where('user_on_permissions.perm_id', 11)
                                                ->get();

                            }

                        } else {
                            if ($nf) {

                                if ($nf->is_repprov == 1) {

                                    $n_r_payment = FinancyRPayment::find($r_payment->id);
                                    $n_r_payment->financy_reprov = 1;

                                    $analyze = new FinancyRPaymentFnyAnalyze;
                                    $analyze->financy_payment_id = $id;
                                    $analyze->r_code = $nf->r_code;
                                    $analyze->is_reprov = 1;
                                    $analyze->description = $nf->description;
                                    $analyze->save();

                                    $n_r_payment->has_analyze = 0;
                                    $n_r_payment->has_suspended = 0;
                                    $n_r_payment->save();

                                    $nf->financy_r_payment_id = $r_payment->id;
                                    $nf->save();

                                    $immediate = [];

                                    $r_user = Users::where('r_code', $r_payment->request_r_code)->first();
                                    $pattern = array(
                                        'id' => $r_payment->id,
                                        'sector_id' => $r_user->sector_id,
                                        'r_code' => $r_user->r_code,
                                        'content' => $r_payment->description,
                                        'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                                        'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                                        'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                                        'method' => $r_payment->p_method,
                                        'optional' => $r_payment->optional,
                                        'title' => 'PAGAMENTO REPROVADO',
                                        'description' => '',
                                        'template' => 'payment.HasReprov',
                                        'subject' => 'PAGAMENTO REPROVADO',
                                    );

                                    SendMailJob::dispatch($pattern, $r_user->email);
                                    App::setLocale($r_user->lang);
                                    NotifyUser(__('layout_i.n_payment_003_title'), $r_user->r_code, 'fa-times', 'text-danger', __('layout_i.n_payment_003', ['amount' => $r_payment->amout_liquid, 'id' => $r_payment->id]), $request->root() .'/financy/payment/my/');
                                    App::setLocale($request->session()->get('lang'));


                                } else {


                                    $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                ->select('users.*')
                                                ->where('user_on_permissions.can_approv', 1)
                                                ->where('user_on_permissions.grade', 99)
                                                ->where('user_on_permissions.perm_id', 11)
                                                ->get();

                                }


                            } else {

                                $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                                                ->select('users.*')
                                                ->where('user_on_permissions.can_approv', 1)
                                                ->where('user_on_permissions.grade', 99)
                                                ->where('user_on_permissions.perm_id', 11)
                                                ->get();

                            }

                        }

                        if (count($immediate) > 0) {
                            foreach ($immediate as $key) {

                                $imdt = Users::where('r_code', $key->r_code)->first();

                                // send email
                                SendMailJob::dispatch($pattern, $imdt->email);

                                App::setLocale($imdt->lang);
                                NotifyUser(__('layout_i.n_payment_001_title'), $imdt->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_payment_001', ['amount' => $r_payment->amount_liquid, 'Name' => $r_user->first_name]), $request->root() .'/financy/payment/request/approv/'. $r_payment->id);
                                App::setLocale($request->session()->get('lang'));

                            }
                        }
                    }
                    $r_payment->has_suspended = 0;
                    $r_payment->save();

                    LogSystem("Colaborador aprovou a solicitação de pagamento identificado por ". $r_payment->id, $request->session()->get('r_code'));
                } else if ($type == 2) {
                    $type_name = "";
                    if ($permission->grade == 10) {
                        $r_payment->pres_reprov = 1;

                        $analyze = new FinancyRPaymentPresAnalyze;
                        $analyze->financy_payment_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_reprov = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $type_name = "PRESIDENTE";

                    } else if ($permission->grade == 99 or $permission->grade == 11 or $permission->grade == 12) {
                        $r_payment->financy_reprov = 1;

                        $analyze = new FinancyRPaymentFnyAnalyze;
                        $analyze->financy_payment_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_reprov = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $type_name = "FINANCEIRO";

                    } else {
                        $r_payment->mng_reprov = 1;

                        $analyze = new FinancyRpaymentMngAnalyze;
                        $analyze->financy_payment_id = $id;
                        $analyze->r_code = $request->session()->get('r_code');
                        $analyze->is_reprov = 1;
                        $analyze->description = $request->description;
                        $analyze->save();

                        $type_name = "GESTOR";
                    }
                    $r_payment->has_analyze = 0;
                    $r_payment->has_suspended = 0;
                    $r_payment->save();

                    $r_user = Users::where('r_code', $r_payment->request_r_code)->first();
                    $pattern = array(
                        'id' => $r_payment->id,
                        'sector_id' => $r_user->sector_id,
                        'r_code' => $r_user->r_code,
                        'content' => $r_payment->description,
                        'liquid' => number_format($r_payment->amount_liquid, 2, ',', '.'),
                        'created_at' => date('d/m/Y', strtotime($r_payment->created_at)),
                        'due_date' => date('d/m/Y', strtotime($r_payment->due_date)),
                        'method' => $r_payment->p_method,
                        'optional' => $r_payment->optional,
                        'title' => 'PAGAMENTO REPROVADO',
                        'description' => '',
                        'template' => 'payment.HasReprov',
                        'subject' => 'PAGAMENTO REPROVADO',
                    );

                    SendMailJob::dispatch($pattern, $r_user->email);
                    App::setLocale($r_user->lang);
                    NotifyUser(__('layout_i.n_payment_003_title'), $r_user->r_code, 'fa-times', 'text-danger', __('layout_i.n_payment_003', ['amount' => $r_payment->amout_liquid, 'id' => $r_payment->id]), $request->root() .'/financy/payment/my/');
                    App::setLocale($request->session()->get('lang'));

                    LogSystem("Colaborador reprovou a solicitação de pagamento identificado por ". $r_payment->id, $request->session()->get('r_code'));
                }


                $txt_analyze = $type == 1 ? "aprovou" : "reprovou";
                $request->session()->put('success', "Você ". $txt_analyze ." a solicitação de pagamento com sucesso!");
                return redirect('/financy/payment/approv');
            } else {
                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }
        } else {
            if ($user->retry > 0) {
                $user->retry = $user->retry - 1;

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Pagamento) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                    return redirect('/logout');
                } else {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->save();

                    $request->session()->put('error', "You missed your secret password, only ". $user->retry ." attempt(s) left.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Pagamento). Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return redirect('/financy/payment/request/approv/'. $id);
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar (Pagamento) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                return redirect('/financy/payment/request/approv/'. $id);
            }
        }


    } */

    public function financyRefundEdit(Request $request, $id) {

        $userall = Users::all();
        if ($request->submit != 'export') {
            $refund = FinancyRefund::find($id);
            if ($refund) {
                if ($refund->recipient_r_code) {
                    $a_bank = UserFinancy::where('r_code', $refund->recipient_r_code)->first();
                } else {
                    $a_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();
                }
            } else {
                $a_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();
            }


            if ($refund) {

                $id = $refund->id;
                $code = $refund->code;
                $receiver = $refund->financy_receiver;
                $itens = FinancyRefundItem::where('financy_refund_id', $refund->id)->get();
                $total_item = number_format($refund->total, 2, ',', '.');
                $total_lending = $refund->lending;
                $lending = number_format($total_lending, 2, ',', '.');
                $total_amount = number_format(abs($total_lending - $refund->total), 2, ',', '.');
				
				$is_financy = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))
                        ->where('perm_id', 18)
                        ->first();
			
				if ($is_financy)
					$is_financy = 1;
				else
					$is_financy = 0;

                $has_analyze = $refund->has_analyze;
                if ($refund->mng_reprov == 0 and $refund->financy_reprov == 0 and $refund->pres_reprov == 0) {
                    if ($refund->mng_approv == 0 and $refund->financy_approv == 0 and $refund->pres_approv == 0) {
                        $has_approv_or_repprov = 0;
                    } else {
                        $has_approv_or_repprov = 1;
                    }
                } else {
                    $has_approv_or_repprov = 1;
                }

                $has_suspended = $refund->has_suspended;

            } else {

                $refund = "";

                $id = 0;
                $code = '';
                $itens = '';
                $receiver = '';
                $total_item = '0,00';
                $lending = '0,00';
                $total_amount = '0,00';
                $has_analyze = 0;
                $has_approv_or_repprov = 0;
                $is_financy = 0;
                $has_suspended = 0;

            }

            return view('gree_i.refund.refund_edit', [
                'userall' => $userall,
                'refund' => $refund,
                'is_financy' => $is_financy,
                'has_suspended' => $has_suspended,
                'receiver' => $receiver,
                'has_approv_or_repprov' => $has_approv_or_repprov,
                'has_analyze' => $has_analyze,
                'a_bank' => $a_bank,
                'id' => $id,
                'code' => $code,
                'itens' => $itens,
                'total_item' => $total_item,
                'lending' => $lending,
                'total_amount' => $total_amount,
            ]);
        } else {

            header('Content-Type: application/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=ReportRefund-'. date('Y-m-d') .'.csv');

            $handle = fopen('php://output', 'w');
            fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

            $data = FinancyRefundItem::leftJoin('financy_refund_item_attach','financy_refund_item.id','=','financy_refund_item_attach.financy_refund_item_id')
                ->select('financy_refund_item.*', 'financy_refund_item_attach.name', 'financy_refund_item_attach.url')
                ->where('financy_refund_id', $request->refund_id)->get();


            fputcsv($handle, array("Tipo", "Descrição", "Cidade", "Moeda", "Total", "Feito em", "--", "Nome do arquivo", "Url do arquivo"), ";");

            foreach ($data as $export) {


                fputcsv($handle, array(refundType($export->type), $export->description, $export->city, currency($export->currency), number_format($export->total, 2, ',', '.'), date('d/m/Y', strtotime($export->date)), "--", $export->name, $export->url), ";");
            }

            fputcsv($handle, array("", "", "", "", "", "", "", "", ""), ";");
            fputcsv($handle, array("", "", "", "", "", "", "", "", ""), ";");

            $refund = FinancyRefund::find($request->refund_id);

            $total_item = number_format($refund->total, 2, ',', '.');
            $total_lending = $refund->lending;
            $lending = number_format($total_lending, 2, ',', '.');
            $total_amount = number_format(abs($total_lending - $refund->total), 2, ',', '.');

            fputcsv($handle, array("", "", "", "", "", "", "", "TOTAL DE DESPESAS:", $total_item), ";");
            fputcsv($handle, array("", "", "", "", "", "", "", "ADIANTAMENTO:", $lending), ";");
            fputcsv($handle, array("", "", "", "", "", "", "", "SALDO A PAGAR (RECEBER):", $total_amount), ";");

            fclose($handle);
        }
    }


    // Use this function to track analyzes
    public function ProcessAnalyzeTrack(Request $request, $type, $id) {

        // Lending = 1
        if ($type == 1) {

            $analyze = array();
            $last_date = date('d-m-Y');

            $module = FinancyLending::find($id);

            if ($module) {

                // BEGIN MANAGER
                // GET REPPROV MANAGER REQUEST
                $data = FinancyLendingMngAnalyze::where('financy_lending_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyLendingMngAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gestor', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV MANAGER REQUEST
                if ($module->mng_approv == 1) {

                    $data = FinancyLendingMngAnalyze::where('financy_lending_id', $id)
                        ->where('is_approv', 1)
                        ->get();

                    if ($data->count()) {

                        foreach ($data as $key) {
                            $last_date = $key->created_at;
                            array_push($analyze, processItemAnalyze($key, $key->description, 'Gestor', 'success', 1));
                        }

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 0 and $module->mng_reprov == 0) {

                    if ($module->dir_r_code) {

                        $users = array();
                        $last_approv = FinancyLendingMngAnalyze::with('users.immediates')
                            ->where('financy_lending_id', $id)
                            ->where('is_approv', 1)
                            ->orderBy('id', 'DESC')
                            ->first();

                        if ($last_approv) {

                            $data = $last_approv->users->immediates[0];
                            $add = array();
                            $add['r_code'] = $data->r_code;
                            $add['name'] = getENameF($data->r_code);
                            array_push($users, $add);

                        } else {
                            $data = UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
                                ->select('users.*', 'user_immediate.*')
                                ->where('users.is_active', 1)
                                ->where('user_r_code', $module->r_code)
                                ->first();

                            $add = array();
                            $add['r_code'] = $data->r_code;
                            $add['name'] = getENameF($data->r_code);
                            array_push($users, $add);
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gestor', $color, $suspended, $module->created_at));

                    } else {

                        $data = UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
                            ->select('users.*', 'user_immediate.*')
                            ->where('users.is_active', 1)
                            ->where('user_r_code', $module->r_code)
                            ->get();

                        if (count($data) > 0) {

                            $users = array();
                            foreach ($data as $key) {
                                if ($key->is_holiday == 1) {

                                    $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                    foreach($usrhd as $usr) {

                                        $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                        $add = array();
                                        $add['r_code'] = $key->r_code;
                                        $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                        array_push($users, $add);
                                    }
                                } else {

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code);
                                    array_push($users, $add);
                                }
                            }

                            $suspended = $module->has_suspended == 1 ? 3 : 4;
                            $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                            $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                            array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gestor', $color, $suspended, $module->created_at));

                        } else {

                            return response()->json([
                                'success' => false,
                                'msg' => 'Não há gestores para aprovar o pedido.',
                            ]);
                        }
                    }

                }
                // END MANAGER

                if ($module->dir_r_code and $module->mng_approv == 1) {
                    // BEGIN DIR
                    // GET REPPROV MANAGER REQUEST
                    $data = FinancyLendingDirAnalyze::where('financy_lending_id', $id)
                        ->where('is_reprov', 1)
                        ->get();

                    if (count($data) > 0) {

                        foreach ($data as $key) {

                            $data_n = FinancyLendingDirAnalyze::find($key->id);

                            array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente', 'danger', 2));
                        }

                        $export_code = $module->id;
                        if (isset($module->code)) {
                            $export_code = $module->code;
                        }

                        return response()->json([
                            'success' => true,
                            'code' => $export_code,
                            'history' => $analyze,
                        ], 200, array(), JSON_PRETTY_PRINT);
                    }

                    // GET APPROV DIRECTION REQUEST
                    if ($module->mng_approv == 1) {

                        $data = FinancyLendingDirAnalyze::where('financy_lending_id', $id)
                            ->where('is_approv', 1)
                            ->first();

                        if ($data) {

                            $last_date = $data->created_at;
                            array_push($analyze, processItemAnalyze($data, $data->description, 'Gerente', 'success', 1));

                        }

                    }

                    // WHO WILL APPROVE?
                    if ($module->dir_approv == 0 and $module->dir_approv == 0 and $module->has_analyze == 1) {

                        $data = collect([$module->dir_r_code]);

                        if (count($data) > 0) {

                            $users = array();
                            foreach ($data as $key) {
                                $add = array();
                                $add['r_code'] = $key;
                                $add['name'] = getENameF($key);
                                array_push($users, $add);
                            }

                            $suspended = $module->has_suspended == 1 ? 3 : 4;
                            $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                            $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                            array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gerente', $color, $suspended, $module->created_at));

                        } else {

                            return response()->json([
                                'success' => false,
                                'msg' => 'Não há diretores para aprovar o pedido.',
                            ]);
                        }

                    }
                    // END DIRECTION
                }

                // BEGIN FINANCY MANAGER
                // GET REPPROV FINANCY MANAGER REQUEST
                $data = FinancyLendingFnyAnalyze::where('financy_lending_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyLendingFnyAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente financeiro', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV FINANCY MANAGER REQUEST
                if ($module->financy_approv == 1) {

                    $data = FinancyLendingFnyAnalyze::where('financy_lending_id', $id)
                        ->where('is_approv', 1)
                        ->orderby('id', 'DESC')
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Gerente financeiro', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->financy_reprov == 0 and $module->financy_approv == 0) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 99)
                        ->where('user_on_permissions.perm_id', 9)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gerente financeiro', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há gerente do financeiro para aprovar o pedido.',
                        ]);
                    }

                }
                // END FINANCY MANAGER

                // BEGIN PRESIDENT
                // GET REPPROV PRESIDENT REQUEST
                $data = FinancyLendingPresAnalyze::where('financy_lending_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyLendingFnyAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Presidente', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV PRESIDENT REQUEST
                if ($module->financy_approv == 1) {

                    $data = FinancyLendingPresAnalyze::where('financy_lending_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Presidente', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->financy_approv == 1 and $module->pres_approv == 0 and $module->pres_repprov == 0) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 10)
                        ->where('user_on_permissions.perm_id', 9)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Presidente', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há presidente para aprovar o pedido.',
                        ]);
                    }

                }
                // END PRESIDENT

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Pedido não existe no banco de dados.'
                ]);

            }


            // Refund = 2
        } else if ($type == 2) {

            $analyze = array();
            $last_date = date('d-m-Y');

            $module = FinancyRefund::find($id);

            if ($module) {

                // BEGIN MANAGER
                // GET REPPROV MANAGER REQUEST
                $data = FinancyRefundMngAnalyze::where('financy_refund_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRefundMngAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gestor', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV MANAGER REQUEST
                $data = FinancyRefundMngAnalyze::where('financy_refund_id', $id)
                    ->where('is_approv', 1)
                    ->get();

                if (count($data) > 0) {
                    foreach ($data as $key) {
                        $last_date = $key->created_at;
                        array_push($analyze, processItemAnalyze($key, $key->description, 'Gestor', 'success', 1));
                    }
                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 0 and $module->mng_reprov == 0 and $module->has_analyze == 1) {

                    if ($module->dir_r_code) {

                        $users = array();
                        $last_approv = FinancyRefundMngAnalyze::with('users.immediates')->where('financy_refund_id', $id)
                            ->where('is_approv', 1)
                            ->orderBy('id', 'DESC')
                            ->first();

                        if ($last_approv) {

                            $data = $last_approv->users->immediates[0];
                            $add = array();
                            $add['r_code'] = $data->r_code;
                            $add['name'] = getENameF($data->r_code);
                            array_push($users, $add);
                        } else {
                            $data = UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
                                ->select('users.*', 'user_immediate.*')
                                ->where('users.is_active', 1)
                                ->where('user_r_code', $module->request_r_code)
                                ->first();

                            if ($data) {
                                $add = array();
                                $add['r_code'] = $data->r_code;
                                $add['name'] = getENameF($data->r_code);
                                array_push($users, $add);
                            } else {

                                Log::info('Imediato não registrado para o solicitante: '. $module->request_r_code .' Reembolso: '. $module->id);
                                return response()->json([
                                    'success' => false,
                                    'msg' => 'Não foi possível mostrar o processo, falei com a TI.',
                                ]);
                            }

                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gestor', $color, $suspended, $module->created_at));

                    } else {
                        $data = UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
                            ->select('users.*', 'user_immediate.*')
                            ->where('users.is_active', 1)
                            ->where('user_r_code', $module->request_r_code)
                            ->get();

                        if (count($data) > 0) {

                            $users = array();
                            foreach ($data as $key) {
                                if ($key->is_holiday == 1) {

                                    $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                    foreach($usrhd as $usr) {

                                        $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                        $add = array();
                                        $add['r_code'] = $key->r_code;
                                        $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                        array_push($users, $add);
                                    }
                                } else {

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code);
                                    array_push($users, $add);
                                }
                            }

                            $suspended = $module->has_suspended == 1 ? 3 : 4;
                            $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                            $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                            array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gestor', $color, $suspended, $module->created_at));

                        } else {

                            return response()->json([
                                'success' => false,
                                'msg' => 'Não há gestores para aprovar o pedido.',
                            ]);
                        }
                    }
                }
                // END MANAGER

                if ($module->dir_r_code and $module->mng_approv == 1) {
                    // BEGIN DIR
                    // GET REPPROV MANAGER REQUEST
                    $data = FinancyRefundDirAnalyze::where('financy_refund_id', $id)
                        ->where('is_reprov', 1)
                        ->get();

                    if (count($data) > 0) {

                        foreach ($data as $key) {

                            $data_n = FinancyRefundDirAnalyze::find($key->id);

                            array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente', 'danger', 2));
                        }

                        $export_code = $module->id;
                        if (isset($module->code)) {
                            $export_code = $module->code;
                        }

                        return response()->json([
                            'success' => true,
                            'code' => $export_code,
                            'history' => $analyze,
                        ], 200, array(), JSON_PRETTY_PRINT);
                    }

                    // GET APPROV DIRECTION REQUEST
                    if ($module->mng_approv == 1) {

                        $data = FinancyRefundDirAnalyze::where('financy_refund_id', $id)
                            ->where('is_approv', 1)
                            ->first();

                        if ($data) {

                            $last_date = $data->created_at;
                            array_push($analyze, processItemAnalyze($data, $data->description, 'Gerente', 'success', 1));

                        }

                    }

                    // WHO WILL APPROVE?
                    if ($module->dir_approv == 0 and $module->dir_approv == 0 and $module->has_analyze == 1) {

                        $data = collect([$module->dir_r_code]);

                        if (count($data) > 0) {

                            $users = array();
                            foreach ($data as $key) {
                                $add = array();
                                $add['r_code'] = $key;
                                $add['name'] = getENameF($key);
                                array_push($users, $add);
                            }

                            $suspended = $module->has_suspended == 1 ? 3 : 4;
                            $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                            $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                            array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gerente', $color, $suspended, $module->created_at));

                        } else {

                            return response()->json([
                                'success' => false,
                                'msg' => 'Não há diretores para aprovar o pedido.',
                            ]);
                        }

                    }
                    // END DIRECTION
                }

                // BEGIN FINANCY SUPERVISOR
                // GET REPPROV FINANCY SUPERVISOR REQUEST
                $data = FinancyRefundFnyAnalyze::where('financy_refund_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRefundFnyAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Físcal', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV FINANCY SUPERVISOR REQUEST
                if ($module->financy_supervisor != null) {

                    $data = FinancyRefundFnyAnalyze::where('financy_refund_id', $id)
                        ->where('is_approv', 1)
                        ->where('r_code', $module->financy_supervisor)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Físcal', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if (
                    $module->mng_approv == 1 and
                    $module->financy_supervisor == null
                    and $module->financy_reprov == 0
                    and $module->has_analyze == 1
                    and $module->financy_approv == 0
                    and (!$module->dir_r_code or $module->dir_r_code and $module->dir_approv == 1)
                ) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 12)
                        ->where('user_on_permissions.perm_id', 12)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Físcal', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há verificador físcal para aprovar o pedido.',
                        ]);
                    }

                }
                // END FINANCY ACCOUNTING

                // BEGIN FINANCY MANAGER
                // GET REPPROV FINANCY MANAGER REQUEST
                $data = FinancyRefundFnyAnalyze::where('financy_refund_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRefundFnyAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente financeiro', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV FINANCY MANAGER REQUEST
                if ($module->financy_approv == 1) {

                    $data = FinancyRefundFnyAnalyze::where('financy_refund_id', $id)
                        ->where('is_approv', 1)
                        ->orderby('id', 'DESC')
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Gerente financeiro', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->financy_supervisor != null and $module->financy_reprov == 0 and $module->has_analyze == 1 and $module->financy_approv == 0) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 99)
                        ->where('user_on_permissions.perm_id', 12)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gerente financeiro', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há gerente do financeiro para aprovar o pedido.',
                        ]);
                    }

                }
                // END FINANCY MANAGER

                // BEGIN PRESIDENT
                // GET REPPROV PRESIDENT REQUEST
                $data = FinancyRefundPresAnalyze::where('financy_refund_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRefundFnyAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Presidente', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV PRESIDENT REQUEST
                if ($module->financy_approv == 1) {

                    $data = FinancyRefundPresAnalyze::where('financy_refund_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Presidente', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->financy_supervisor != null and $module->financy_approv == 1 and $module->has_analyze == 1) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 10)
                        ->where('user_on_permissions.perm_id', 12)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Presidente', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há presidente para aprovar o pedido.',
                        ]);
                    }

                }
                // END PRESIDENT

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Pedido não existe no banco de dados.'
                ]);

            }

            // Payment
        } else if ($type == 3) {

            $analyze = array();
            $last_date = date('d-m-Y');

            $module = FinancyRPayment::find($id);

            if ($module) {

                // BEGIN MANAGER
                // GET REPPROV MANAGER REQUEST
                $data = FinancyRPaymentMngAnalyze::where('financy_payment_id', $id)
                    ->where('is_reprov', 1)
                    ->get();

                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRPaymentMngAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gestor', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV MANAGER REQUEST
                if ($module->mng_approv == 1) {

                    $data = FinancyRPaymentMngAnalyze::where('financy_payment_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Gestor', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 0 and $module->mng_reprov == 0 and $module->has_analyze == 1) {

                    $data = UserImmediate::leftJoin('users', 'user_immediate.immediate_r_code', '=', 'users.r_code')
                        ->select('users.*', 'user_immediate.*')
                        ->where('users.is_active', 1)
                        ->where('user_r_code', $module->request_r_code)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }


                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gestor', $color, $suspended, $module->created_at));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há gestores para aprovar o pedido.',
                        ]);
                    }

                }
                // END MANAGER

                // BEGIN FINANCY SUPERVISOR
                // GET REPPROV FINANCY SUPERVISOR REQUEST
                $data = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRPaymentFnyAnalyze::find($key->id);

                        $is_manager = UserOnPermissions::where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 99)
                            ->where('user_on_permissions.perm_id', 11)
                            ->where('user_r_code', $key->r_code)
                            ->first();

                        if ($is_manager) {

                            array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente', 'danger', 2));
                        } else {

                            array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Fiscal', 'danger', 2));
                        }
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV FINANCY SUPERVISOR REQUEST
                if ($module->financy_supervisor != null) {

                    $data = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)
                        ->where('is_approv', 1)
                        ->where('r_code', $module->financy_supervisor)
                        ->first();
                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Fiscal', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->financy_supervisor == null and $module->financy_reprov == 0 and $module->has_analyze == 1 and $module->financy_approv == 0 and $module->due_date >= date('Y-m-d', strtotime($module->created_at .'+ 7 days'))) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 12)
                        ->where('user_on_permissions.perm_id', 11)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Fiscal', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há verificador fiscal para aprovar o pedido.',
                        ]);
                    }

                }
                // END FINANCY SUPERVISOR

                // BEGIN FINANCY ACCOUNTING
                // GET REPPROV FINANCY ACCOUNTING REQUEST
                $data = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRPaymentFnyAnalyze::find($key->id);

                        $is_manager = UserOnPermissions::where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 99)
                            ->where('user_on_permissions.perm_id', 11)
                            ->where('user_r_code', $key->r_code)
                            ->first();

                        if ($is_manager) {

                            array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente', 'danger', 2));
                        } else {

                            array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Fiscal', 'danger', 2));
                        }

                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV FINANCY ACCOUNT REQUEST
                if ($module->financy_accounting != null) {

                    $data = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)
                        ->where('is_approv', 1)
                        ->where('r_code', $module->financy_accounting)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Contábil', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->financy_supervisor != null and $module->financy_accounting == null and $module->financy_reprov == 0 and $module->has_analyze == 1 and $module->financy_approv == 0 and $module->due_date >= date('Y-m-d', strtotime($module->created_at .'+ 7 days'))) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 11)
                        ->where('user_on_permissions.perm_id', 12)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Contábil', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há verificador contábil para aprovar o pedido.',
                        ]);
                    }

                }
                // END FINANCY ACCOUNTING

                // BEGIN FINANCY MANAGER
                // GET REPPROV FINANCY MANAGER REQUEST
                $data = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRPaymentFnyAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente financeiro', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV FINANCY MANAGER REQUEST
                if ($module->financy_approv == 1) {

                    $data = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $id)
                        ->where('is_approv', 1)
                        ->orderby('id', 'DESC')
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Gerente financeiro', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                // if ($module->mng_approv == 1 and $module->financy_accounting != null and $module->financy_supervisor != null and $module->financy_reprov == 0 and $module->financy_approv == 0 and $module->has_analyze == 1 or $module->financy_approv == 0 and $module->financy_reprov == 0 and $module->has_analyze == 1 and $module->mng_approv == 1 and $module->due_date < date('Y-m-d', strtotime($module->created_at .'+ 7 days'))) {
                if (
                    $module->mng_approv == 1 and $module->financy_reprov == 0 and $module->financy_approv == 0 and $module->has_analyze == 1 and
                    (
                        ($module->financy_accounting != null and $module->financy_supervisor != null) or $module->due_date < date('Y-m-d', strtotime($module->created_at .'+ 7 days'))
                    )

                ) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 99)
                        ->where('user_on_permissions.perm_id', 11)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gerente financeiro', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há gerente do financeiro para aprovar o pedido.',
                        ]);
                    }

                }
                // END FINANCY MANAGER

                // BEGIN PRESIDENT
                // GET REPPROV PRESIDENT REQUEST
                $data = FinancyRPaymentPresAnalyze::where('financy_payment_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = FinancyRPaymentPresAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Presidente', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV PRESIDENT REQUEST
                if ($module->financy_approv == 1) {

                    $data = FinancyRPaymentPresAnalyze::where('financy_payment_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Presidente', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->financy_accounting != null and $module->financy_approv == 1 and $module->has_analyze == 1) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 10)
                        ->where('user_on_permissions.perm_id', 12)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Presidente', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há presidente para aprovar o pedido.',
                        ]);
                    }

                }
                // END PRESIDENT
            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Pedido não existe no banco de dados.'
                ]);

            }

            // TI DEVELOPER
        } else if ($type == 4) {
            $analyze = array();
            $last_date = date('d-m-Y');

            $module = TiBacklog::find($id);

            if ($module) {

                // BEGIN MANAGER
                // GET REPPROV MANAGER REQUEST
                $data = TiBacklogMngAnalyze::where('ti_backlog_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = TiBacklogMngAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gerente', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);


                }

                // GET APPROV MANAGER REQUEST
                if ($module->mng_approv == 1) {

                    $data = TiBacklogMngAnalyze::where('ti_backlog_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Gerente', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 0 and $module->mng_reprov == 0) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 8)
                        ->where('user_on_permissions.perm_id', 4)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gerente', $color, $suspended, $module->created_at));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há gerentes para aprovar o pedido.',
                        ]);
                    }

                }
                // END MANAGER


                // BEGIN DIRECTOR
                // GET REPPROV DIRECTOR REQUEST
                $data = TiBacklogDirAnalyze::where('ti_backlog_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = TiBacklogDirAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Diretor', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV DIRECTOR REQUEST
                if ($module->mng_approv == 1) {

                    $data = TiBacklogDirAnalyze::where('ti_backlog_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Gerente', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->mng_approv == 1 and $module->dir_approv == 0) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.grade', 9)
                        ->where('user_on_permissions.perm_id', 4)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gerente', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há diretor para aprovar o pedido.',
                        ]);
                    }

                }
                // END DIRECTOR

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Pedido não existe no banco de dados.'
                ]);

            }

            // QRCODE
        } else if ($type == 5) {
            $analyze = array();
            $last_date = date('d-m-Y');

            $module = Qrcode::find($id);

            if ($module) {

                // BEGIN ANALIST
                // GET REPPROV ANALIST REQUEST
                $data = QrcodeAnalistAnalyze::where('qrcode_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = QrcodeAnalistAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Analista', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);


                }

                // GET APPROV ANALIST REQUEST
                if ($module->analist_approv == 1) {

                    $data = QrcodeAnalistAnalyze::where('qrcode_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Analista', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->analist_approv == 0 and $module->analist_reprov == 0) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.grade', '!=', 99)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.perm_id', 19)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Analista', $color, $suspended, $module->created_at));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há analistas para aprovar o pedido.',
                        ]);
                    }

                }
                // END ANALIST


                // BEGIN MANAGER
                // GET REPPROV MANAGER REQUEST
                $data = QrcodeMngAnalyze::where('qrcode_id', $id)
                    ->where('is_reprov', 1)
                    ->get();
                if (count($data) > 0) {

                    foreach ($data as $key) {

                        $data_n = QrcodeMngAnalyze::find($key->id);

                        array_push($analyze, processItemAnalyze($data_n, $data_n->description, 'Gestor', 'danger', 2));
                    }

                    $export_code = $module->id;
                    if (isset($module->code)) {
                        $export_code = $module->code;
                    }

                    return response()->json([
                        'success' => true,
                        'code' => $export_code,
                        'history' => $analyze,
                    ], 200, array(), JSON_PRETTY_PRINT);
                }

                // GET APPROV MANAGER REQUEST
                if ($module->mng_approv == 1) {

                    $data = QrcodeMngAnalyze::where('qrcode_id', $id)
                        ->where('is_approv', 1)
                        ->first();

                    if ($data) {

                        $last_date = $data->created_at;
                        array_push($analyze, processItemAnalyze($data, $data->description, 'Gestor', 'success', 1));

                    }

                }

                // WHO WILL APPROVE?
                if ($module->analist_approv == 1 and $module->mng_approv == 0) {

                    $data = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                        ->select('users.*')
                        ->where('users.is_active', 1)
                        ->where('user_on_permissions.grade', 99)
                        ->where('user_on_permissions.can_approv', 1)
                        ->where('user_on_permissions.perm_id', 19)
                        ->get();

                    if (count($data) > 0) {

                        $users = array();
                        foreach ($data as $key) {
                            if ($key->is_holiday == 1) {

                                $usrhd = \App\Model\UserHoliday::where('user_r_code', $key->r_code)->get();
                                foreach($usrhd as $usr) {

                                    $key = Users::where('r_code', $usr->receiver_r_code)->first();

                                    $add = array();
                                    $add['r_code'] = $key->r_code;
                                    $add['name'] = getENameF($key->r_code) .' <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Permissão concebida pelo colaborador: ('. $usr->user_r_code .'), através das férias."></i>';

                                    array_push($users, $add);
                                }
                            } else {

                                $add = array();
                                $add['r_code'] = $key->r_code;
                                $add['name'] = getENameF($key->r_code);
                                array_push($users, $add);
                            }
                        }

                        $suspended = $module->has_suspended == 1 ? 3 : 4;
                        $color = $module->has_suspended == 1 ? 'warning' : 'primary';
                        $spd_txt = $module->suspended_question == '' ? 'Por motivos interno, seu pedido foi suspenso. Aguarde a retomada.' : $module->suspended_question;
                        array_push($analyze, processItemAnalyze($users, $spd_txt, 'Gestor', $color, $suspended, $last_date));

                    } else {

                        return response()->json([
                            'success' => false,
                            'msg' => 'Não há gestor para aprovar o pedido.',
                        ]);
                    }

                }
                // END DIRECTOR

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Pedido não existe no banco de dados.'
                ]);

            }
        } else if ($type == 6) {
            $analyze = array();
            $module = FinancyAccountability::find($id);

            if ($module) {
                $analyze_json = $this->ProcessAnalyzeTrack($request, 3, $module->payment_request_id);
                $analyze = $analyze_json->getData();

                if (!$analyze->success) {

                    return $analyze_json;
                }
                $analyze->code = "#".$analyze->code."<br><small>ORIGEM: #".$module->code."</small>";

                $analyze_json->setData($analyze);

                return $analyze_json;

            } else {

                return response()->json([
                    'success' => false,
                    'msg' => 'Pedido não existe no banco de dados.2'
                ]);

            }

        }else {

            return response()->json([
                'success' => false,
                'msg' => 'Tipo do pedido é inválido.'
            ]);
        }

        $export_code = $module->id;
        if (isset($module->code)) {
            $export_code = $module->code;
        }

        return response()->json([
            'success' => true,
            'code' => $export_code,
            'history' => $analyze,
        ], 200, array(), JSON_PRETTY_PRINT);


    }

    public function suspendedRequest(Request $request, $type, $id) {

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {
            if ($type == 1) {

                $module = FinancyLending::find($id);

                if ($module) {

                    $module->has_suspended = 1;
                    $module->suspended_question = $request->r_val_3;
                    $module->save();


                    $user_request = Users::where('r_code', $module->r_code)->first();

                    $pattern = array(
                        'id' => $id,
                        'user' => $user,
                        'title' => 'EMPRÉSTIMO #'. $module->code .' FOI SUSPENSO',
                        'description' => nl2br("Olá! Seu pedido ficará suspenso pelo seguinte motivo: ". $request->r_val_3 ." \n Link para ver mais detalhes: <a href'". $request->root() ."/financy/lending/all'>". $request->root() ."/financy/lending/all</a>"),
                        'template' => 'misc.DefaultWithPeoples',
                        'subject' => 'Atualização do empréstimo: #'. $module->code,
                    );

                    SendMailJob::dispatch($pattern, $user_request->email);

                    if ($request->people) {

                        $people = Users::where('r_code', $request->people)->first();

                        if ($request->people) {

                            $pattern = array(
                                'id' => $id,
                                'title' => 'EMPRÉSTIMO #'. $module->code .' AVISO',
                                'description' => nl2br($request->r_val_3 ."\n Link para ver mais detalhes: <a href'". $request->root() ."/financy/lending/all'>". $request->root() ."/financy/lending/all</a>"),
                                'template' => 'misc.Default',
                                'subject' => 'Aviso do empréstimo: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $user_request->email);
                        }
                    }

                    $request->session()->put('success', 'A solicitação foi suspensa com sucesso!');
                    return Redirect('/financy/lending/all');

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            } else if ($type == 2) {

                $module = FinancyRefund::find($id);

                if ($module) {

                    $module->has_suspended = 1;
                    $module->suspended_question = $request->r_val_3;
                    $module->save();

                    $user_request = Users::where('r_code', $module->request_r_code)->first();

                    $pattern = array(
                        'id' => $id,
                        'user' => $user,
                        'title' => 'REEMBOLSO #'. $module->code .' FOI SUSPENSO',
                        'description' => nl2br("Olá! Seu pedido ficará suspenso pelo seguinte motivo: ". $request->r_val_3 ." \n Link para ver mais detalhes: <a href='". $request->root() ."/financy/refund/all'>". $request->root() ."/financy/refund/all</a>"),
                        'template' => 'misc.DefaultWithPeoples',
                        'subject' => 'Atualização do reembolso: #'. $module->code,
                    );

                    SendMailJob::dispatch($pattern, $user_request->email);

                    if ($request->people) {

                        $people = Users::where('r_code', $request->people)->first();

                        if ($request->people) {

                            $pattern = array(
                                'id' => $id,
                                'title' => 'REEMBOLSO #'. $module->code .' AVISO',
                                'description' => nl2br($request->r_val_3 ."\n Link para ver mais detalhes: <a href='". $request->root() ."/financy/refund/all'>". $request->root() ."/financy/refund/all</a>"),
                                'template' => 'misc.Default',
                                'subject' => 'Aviso do reembolso: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $user_request->email);
                        }
                    }

                    $request->session()->put('success', 'A solicitação foi suspensa com sucesso!');
                    return Redirect('/financy/refund/all');

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            } else if ($type == 3) {

                $module = FinancyRPayment::find($id);

                if ($module) {

                    $module->has_suspended = 1;
                    $module->suspended_question = $request->r_val_3;
                    $module->save();

                    $user_request = Users::where('r_code', $module->request_r_code)->first();

                    $pattern = array(
                        'id' => $id,
                        'user' => $user,
                        'title' => 'PAGAMENTO #'. $module->code .' FOI SUSPENSO',
                        'description' => nl2br("Olá! Seu pedido ficará suspenso pelo seguinte motivo: ". $request->r_val_3 ." \n Link para ver mais detalhes: <a href='". $request->root() ."/financy/refund/all'>". $request->root() ."/financy/refund/all</a>"),
                        'template' => 'misc.DefaultWithPeoples',
                        'subject' => 'Atualização do pagamento: #'. $module->code,
                    );

                    SendMailJob::dispatch($pattern, $user_request->email);

                    if ($request->people) {

                        $people = Users::where('r_code', $request->people)->first();

                        if ($request->people) {

                            $pattern = array(
                                'id' => $id,
                                'title' => 'PAGAMENTO #'. $module->code .' AVISO',
                                'description' => nl2br($request->r_val_3 ."\n Link para ver mais detalhes: <a href='". $request->root() ."/financy/refund/all'>". $request->root() ."/financy/refund/all</a>"),
                                'template' => 'misc.Default',
                                'subject' => 'Aviso do pagamento: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $user_request->email);
                        }
                    }

                    $request->session()->put('success', 'A solicitação foi suspensa com sucesso!');
                    return Redirect('/financy/payment/all');

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            } else if ($type == 4) {

                $module = TiBacklog::find($id);

                if ($module) {

                    $module->has_suspended = 1;
                    $module->suspended_question = $request->r_val_3;
                    $module->save();


                    $user_request = Users::where('r_code', $module->request_r_code)->first();

                    $pattern = array(
                        'id' => $id,
                        'user' => $user,
                        'title' => 'TAREFA #ID '. $id .' FOI SUSPENSO',
                        'description' => nl2br("Olá! Seu pedido ficará suspenso pelo seguinte motivo: ". $request->r_val_3 ." \n Link para ver mais detalhes: <a href'". $request->root() ."/ti/developer/all'>". $request->root() ."/ti/developer/all</a>"),
                        'template' => 'misc.DefaultWithPeoples',
                        'subject' => 'Atualização da tarefa: #'. $id,
                    );

                    SendMailJob::dispatch($pattern, $user_request->email);

                    if ($request->people) {

                        $people = Users::where('r_code', $request->people)->first();

                        if ($request->people) {

                            $pattern = array(
                                'id' => $id,
                                'title' => 'EMPRÉSTIMO #ID '. $id .' AVISO',
                                'description' => nl2br($request->r_val_3 ."\n Link para ver mais detalhes: <a href'". $request->root() ."/financy/lending/all'>". $request->root() ."/financy/lending/all</a>"),
                                'template' => 'misc.Default',
                                'subject' => 'Aviso do empréstimo: #'. $id,
                            );

                            SendMailJob::dispatch($pattern, $user_request->email);
                        }
                    }

                    $request->session()->put('success', 'A solicitação foi suspensa com sucesso!');
                    return Redirect('/ti/developer/approv');

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            }
        } else {
            if ($user->retry > 0) {
                $user->retry = $user->retry - 1;

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Reembolso) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                    return redirect('/logout');
                } else {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->save();

                    $request->session()->put('error', "You missed your secret password, only ". $user->retry ." attempt(s) left.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar. Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return redirect('/news');
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                return redirect('/news');
            }
        }

    }

    public function retrocRequest(Request $request, $type, $id) {

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {
            if ($type == 1) {

                $module = FinancyLending::find($id);

                if ($module) {

                    // STEPS RETROC
                    if ($request->step == 1) {
                        // GESTOR
                        $module->mng_approv = 0;
                        $module->financy_approv = 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyLendingFnyAnalyze::where('financy_lending_id', $module->id)->delete();
                        FinancyLendingMngAnalyze::where('financy_lending_id', $module->id)->delete();

                        $immediate = UserImmediate::leftJoin('user_on_permissions', 'user_immediate.immediate_r_code', '=', 'user_on_permissions.user_r_code')
                            ->leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_immediate.user_r_code', $module->r_code)
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.perm_id', 9)
                            ->groupBy('user_immediate.immediate_r_code')
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'EMPRÉSTIMO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/lending/approv'>". $request->root() ."/financy/lending/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do empréstimo: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    } else if ($request->step == 2) {
                        // GERENTE
                        $module->mng_approv = 1;
                        $module->financy_approv = 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyLendingFnyAnalyze::where('financy_lending_id', $module->id)->delete();

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 99)
                            ->where('user_on_permissions.perm_id', 9)
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'EMPRÉSTIMO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/lending/approv'>". $request->root() ."/financy/lending/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do empréstimo: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    }
                    $module->save();

                    $user_request = Users::where('r_code', $module->r_code)->first();

                    $pattern = array(
                        'id' => $id,
                        'user' => $user,
                        'title' => 'EMPRÉSTIMO #'. $module->code .' ATUALIZAÇÃO',
                        'description' => nl2br("Olá! Seu pedido teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes: <a href'". $request->root() ."/financy/lending/my'>". $request->root() ."/financy/lending/my</a>"),
                        'template' => 'misc.DefaultWithPeoples',
                        'subject' => 'Atualização do empréstimo: #'. $module->code,
                    );

                    SendMailJob::dispatch($pattern, $user_request->email);

                    $request->session()->put('success', 'A etapa da solicitação foi retrocedida com sucesso!');
                    return Redirect('/financy/lending/approv');

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            } else if ($type == 2) {

                $module = FinancyRefund::find($id);

                if ($module) {

                    // STEPS RETROC
                    if ($request->step == 1) {
                        // SOLICITANTE
                        $module->has_analyze = 0;
                        $module->mng_approv = 0;
                        $module->financy_approv = 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyRefundFnyAnalyze::where('financy_refund_id', $module->id)->delete();
                        FinancyRefundMngAnalyze::where('financy_refund_id', $module->id)->delete();

                    } else if ($request->step == 2) {
                        // GESTOR
                        $module->mng_approv = 0;
                        $module->financy_approv = 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyRefundFnyAnalyze::where('financy_refund_id', $module->id)->delete();
                        FinancyRefundMngAnalyze::where('financy_refund_id', $module->id)->delete();

                        $immediate = UserImmediate::leftJoin('user_on_permissions', 'user_immediate.immediate_r_code', '=', 'user_on_permissions.user_r_code')
                            ->leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_immediate.user_r_code', $module->request_r_code )
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.perm_id', 12)
                            ->groupBy('user_immediate.immediate_r_code')
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'REEMBOLSO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/refund/approv'>". $request->root() ."/financy/refund/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do reembolso: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    } else if ($request->step == 3) {
                        // FISCAL
                        $module->mng_approv = 1;
                        $module->financy_approv = 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyRefundFnyAnalyze::where('financy_refund_id', $module->id)->delete();

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 11)
                            ->where('user_on_permissions.perm_id', 12)
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'REEMBOLSO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/refund/approv'>". $request->root() ."/financy/lending/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do reembolso: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    } else if ($request->step == 4) {
                        // GERENTE
                        $module->mng_approv = 1;
                        $module->financy_approv = 0;

                        $ag = FinancyRefundFnyAnalyze::where('financy_refund_id', $module->id)->orderBy('id', 'DESC')->first();
                        if ($ag) {
                            $ag = FinancyRefundFnyAnalyze::where('id', $ag->id)->delete();
                        }

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 99)
                            ->where('user_on_permissions.perm_id', 12)
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'REEMBOLSO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/refund/approv'>". $request->root() ."/financy/refund/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do reembolso: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    }

                    $module->save();

                    $user_request = Users::where('r_code', $module->request_r_code)->first();

                    $pattern = array(
                        'id' => $id,
                        'user' => $user,
                        'title' => 'REEMBOLSO #'. $module->code .' ATUALIZAÇÃO',
                        'description' => nl2br("Olá! Seu pedido teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes: <a href'". $request->root() ."/financy/refund/my'>". $request->root() ."/financy/refund/my</a>"),
                        'template' => 'misc.DefaultWithPeoples',
                        'subject' => 'Atualização do reembolso: #'. $module->code,
                    );

                    SendMailJob::dispatch($pattern, $user_request->email);

                    $request->session()->put('success', 'A etapa da solicitação foi retrocedida com sucesso!');
                    return Redirect('/financy/refund/approv');

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            } else if ($type == 3) {

                $module = FinancyRPayment::find($id);

                if ($module) {

                    // STEPS RETROC
                    if ($request->step == 1) {
                        // SOLICITANTE
                        $module->has_analyze = 0;
                        $module->mng_approv = 0;
                        $module->financy_approv == 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyRPaymentFnyAnalyze::where('financy_payment_id', $module->id)->delete();
                        FinancyRPaymentMngAnalyze::where('financy_payment_id', $module->id)->delete();

                    } else if ($request->step == 2) {
                        // GESTOR
                        $module->mng_approv = 0;
                        $module->financy_approv = 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyRPaymentFnyAnalyze::where('financy_payment_id', $module->id)->delete();
                        FinancyRPaymentMngAnalyze::where('financy_payment_id', $module->id)->delete();

                        $immediate = UserImmediate::leftJoin('user_on_permissions', 'user_immediate.immediate_r_code', '=', 'user_on_permissions.user_r_code')
                            ->leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_immediate.user_r_code', $module->request_r_code )
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.perm_id', 11)
                            ->groupBy('user_immediate.immediate_r_code')
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'PAGAMENTO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/payment/approv'>". $request->root() ."/financy/payment/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do pagamento: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    } else if ($request->step == 3) {
                        // FISCAL
                        $module->mng_approv = 1;
                        $module->financy_approv = 0;
                        $module->financy_supervisor = null;
                        $module->financy_accounting = null;

                        FinancyRPaymentFnyAnalyze::where('financy_payment_id', $module->id)->delete();

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 11)
                            ->where('user_on_permissions.perm_id', 11)
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'PAGAMENTO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/payment/approv'>". $request->root() ."/financy/payment/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do pagamento: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    } else if ($request->step == 4) {
                        // CONTÁBIL
                        $module->mng_approv = 1;
                        $module->financy_approv = 0;
                        $module->financy_accounting = null;

                        if (FinancyRPaymentFnyAnalyze::where('financy_payment_id', $module->id)->count() == 2) {
                            $ag = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $module->id)->orderBy('id', 'DESC')->first();
                            if ($ag) {
                                $ag = FinancyRPaymentFnyAnalyze::where('id', $ag->id)->delete();
                            }
                        } else {
                            FinancyRPaymentFnyAnalyze::where('financy_payment_id', $module->id)->skip(1)->delete();

                        }

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 12)
                            ->where('user_on_permissions.perm_id', 11)
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'REEMBOLSO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/payment/approv'>". $request->root() ."/payment/lending/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do reembolso: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    } else if ($request->step == 5) {
                        // GERENTE
                        $module->mng_approv = 1;
                        $module->financy_approv = 0;

                        $ag = FinancyRPaymentFnyAnalyze::where('financy_payment_id', $module->id)->orderBy('id', 'DESC')->first();
                        if ($ag) {
                            $ag = FinancyRPaymentFnyAnalyze::where('id', $ag->id)->delete();
                        }

                        $immediate = UserOnPermissions::leftJoin('users', 'user_on_permissions.user_r_code', '=', 'users.r_code')
                            ->select('users.*')
                            ->where('user_on_permissions.can_approv', 1)
                            ->where('user_on_permissions.grade', 99)
                            ->where('user_on_permissions.perm_id', 11)
                            ->get();

                        foreach ($immediate as $key) {

                            $pattern = array(
                                'id' => $id,
                                'user' => $user,
                                'title' => 'PAGAMENTO #'. $module->code .' ATUALIZAÇÃO',
                                'description' => nl2br("Olá! A seguinte solicitação teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes e avaliar novamente: <a href'". $request->root() ."/financy/payment/approv'>". $request->root() ."/financy/payment/approv</a>"),
                                'template' => 'misc.DefaultWithPeoples',
                                'subject' => 'Atualização do pagamento: #'. $module->code,
                            );

                            SendMailJob::dispatch($pattern, $key->email);
                        }
                    }

                    $module->save();
                    $user_request = Users::where('r_code', $module->request_r_code)->first();

                    $pattern = array(
                        'id' => $id,
                        'user' => $user,
                        'title' => 'PAGAMENTO #'. $module->code .' ATUALIZAÇÃO',
                        'description' => nl2br("Olá! Seu pedido teve um retrocesso no fluxo pelo seguinte motivo: ". $request->r_val_4 ." \n Link para ver mais detalhes: <a href'". $request->root() ."/financy/payment/my'>". $request->root() ."/financy/payment/my</a>"),
                        'template' => 'misc.DefaultWithPeoples',
                        'subject' => 'Atualização do pagamento: #'. $module->code,
                    );

                    SendMailJob::dispatch($pattern, $user_request->email);

                    $request->session()->put('success', 'A etapa da solicitação foi retrocedida com sucesso!');
                    return Redirect('/financy/payment/approv');

                } else {

                    App::setLocale($request->session()->get('lang'));
                    $request->session()->put('error', __('layout_i.not_permissions'));
                    return Redirect('/news');
                }

            }
        } else {
            if ($user->retry > 0) {
                $user->retry = $user->retry - 1;

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Reembolso) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                    return redirect('/logout');
                } else {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->save();

                    $request->session()->put('error', "You missed your secret password, only ". $user->retry ." attempt(s) left.");
                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar. Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return redirect('/news');
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                return redirect('/news');
            }
        }

    }
	
	private function quantationBRxUSD($currency, $date, $day = 0) {

			if ($currency == 'USD') {

				$get_cotattion = json_decode(file_get_contents('https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarDia(dataCotacao=@dataCotacao)?@dataCotacao=%27'.date('m-d-Y', strtotime('- '.$day.' days', strtotime($date))).'%27&$format=json'), TRUE);
				
				if (isset($get_cotattion['value'][0])) 
					return ['result' => $get_cotattion['value'][0]['cotacaoCompra']];
				else
					$day++;
				
				do {
					
					$response = $this->quantationBRxUSD($currency, $date, $day);
					
					$day++;
				} while (!isset($response['result']));
				
				return ['result' => $response['result']];

			} else {

				$content = @file_get_contents("https://api.exchangeratesapi.io/v1/". date('Y-m-d', strtotime($date)) ."?access_key=6056e41532fdac37f01a77d8ac0a8d32&symbols=BRL&base=". $currency ."");

				if($content === FALSE) {
					return ['result' => 0];
				}
				$get_cotattion = json_decode($content, TRUE);

				return ['result' => $get_cotattion['rates']['BRL']];
			}
	}

    public function financyRefundEdit_do(Request $request) {
        $receipt = $request->file('receipt');
        $other = $request->file('other');

        // JSON RESPONSE FILES
        $receipt_url = "";
        $receipt_name = "";
        $other_url = "";
        $other_name = "";
        $is_financy = 0;

        if ($request->id == 0) {
            LogSystem("Colaborador criou novo reembolso.", $request->session()->get('r_code'));
            $refund = new FinancyRefund;
            $refund->request_r_code = $request->session()->get('r_code');
            $refund->code = getCodeModule('refund');
        } else {
            LogSystem("Colaborador o reembolso identificada por: ". $request->id, $request->session()->get('r_code'));
            $refund = FinancyRefund::find($request->id);

			$is_financy = UserOnPermissions::where('user_r_code', $request->session()->get('r_code'))
                        ->where('perm_id', 18)
                        ->first();
			
			if ($is_financy)
				$is_financy = 1;
        }

        if ($request->item_id == 0) {
            LogSystem("Colaborador criou novo item de reembolso.", $request->session()->get('r_code'));
            $refund_item = new FinancyRefundItem;
        } else {
            LogSystem("Colaborador editou item de reembolso identificada por: ". $request->id, $request->session()->get('r_code'));
            $refund_item = FinancyRefundItem::find($request->item_id);
        }

        $refund->description = $request->squestion;
        $refund->recipient_r_code = $request->srecipient_r_code;
        $refund->save();

        if ($is_financy == 1 and $refund->has_analyze == 1) {
            $source = array('.', ',');
            $replace = array('', '.');
            $total = str_replace($source, $replace, $request->input('total'));

            $date = date('Y-m-d', strtotime($refund_item->date));

            if ($refund_item->old_total == 0.00) {
                $refund_item->old_total = $refund_item->total;
                $refund_item->total = $total;
                if ($total == $refund_item->old_total) {
                    $refund_item->old_total = 0.00;
                }
            } else {
                $refund_item->total = $total;
                if ($total == $refund_item->old_total) {
                    $refund_item->old_total = 0.00;
                }
            }
        } else {
            $refund_item->financy_refund_id = $refund->id;
            $refund_item->currency = $request->currency;
            $refund_item->type = $request->type;
            $refund_item->description = $request->description;
            $refund_item->peoples = $request->peoples;
            $refund_item->city = $request->city;
            if ($request->input('total')) {
                $source = array('.', ',');
                $replace = array('', '.');
                $total = str_replace($source, $replace, $request->input('total'));

                $refund_item->total = $total;
            }
            $old_date = str_replace("/", "-", $request->date);
            $date = date('Y-m-d', strtotime($old_date));
            $refund_item->date = $date;
        }

        $currency = $is_financy == 1 ? $refund_item->currency : $request->currency;
        if ($currency > 1) {

			$response = $this->quantationBRxUSD(currency($request->currency), $date);
			if ($response['result'] == 0)
				return redirect()->back()->with('error', 'Data informada não tem um valor de cotação!');		

			$refund_item->quotation = $response['result'];
            
        } else {
            $refund_item->quotation = 0.00;
        }

        $refund_item->save();


        if ($request->hasFile('receipt')) {
            $attach = new FinancyRefundItemAttach;

            $attach->name = $receipt->getClientOriginalName();
            $attach->size = $receipt->getSize();
            $attach->financy_refund_item_id = $refund_item->id;

            $response = $this->uploadS3(1, $request->receipt, $request);
            if ($response['success']) {
                $attach->url = $response['url'];
                $receipt_url = $response['url'];
                $attach->save();
            }

            $receipt_name = $receipt->getClientOriginalName();

        }

        if ($request->hasFile('other')) {
            $attach = new FinancyRefundItemAttach;
            $attach->name = $other->getClientOriginalName();
            $attach->size = $other->getSize();
            $attach->financy_refund_item_id = $refund_item->id;

            $response = $this->uploadS3(2, $request->other, $request);
            if ($response['success']) {
                $attach->url = $response['url'];
                $other_url = $response['url'];
                $attach->save();
            }

        }

        $refund = FinancyRefund::find($refund->id);
        $itens = FinancyRefundItem::where('financy_refund_id', $refund->id)->get();
        $gen_total = 0.00;
        $multiply = 0.00;
        foreach ($itens as $item) {

            if ($item->currency > 1) {

				$response = $this->quantationBRxUSD(currency($item->currency), $item->date);
				if ($response['result'] == 0)
					return redirect()->back()->with('error', 'Data informada não tem um valor de cotação!');
				$multiply = $response['result'];

                $gen_total = $gen_total + round($multiply * $item->total, 2);

            } else {
                $gen_total = $gen_total + $item->total;
            }
        }
        $refund->total = $gen_total;
        $refund->save();

        /* if ($request->id == 0) {

            // GEN CODE SEGMENT
            codeSegmentBase($refund->id, 2, $request);
        } */

        $total_lending = $refund->lending;

        if ($request->item_id == 0) {
            $text_message = "Item adicionado com sucesso";
        } else {
            $text_message = "Item editado com sucesso";
        }

        return redirect('/financy/refund/edit/'. $refund->id)->with('success', $text_message);
    }

    public function financyRefundLending_do(Request $request) {

        if ($request->id == 0) {
            LogSystem("Colaborador criou novo reembolso.", $request->session()->get('r_code'));
            $refund = new FinancyRefund;
            $refund->request_r_code = $request->session()->get('r_code');
            $refund->code = getCodeModule('refund');
        } else {
            LogSystem("Colaborador editou o reembolso identificada por: ". $request->id, $request->session()->get('r_code'));
            $refund = FinancyRefund::find($request->id);
        }

        $source = array('.', ',');
        $replace = array('', '.');
        $total = str_replace($source, $replace, $request->input('total'));
        $refund->lending = $total;
        $refund->save();

        /* if ($request->id == 0) {

            // GEN CODE SEGMENT
            codeSegmentBase($refund->id, 2, $request);
        } */

        return response()->json([
            'success' => true,
            'refund_id' => intval($refund->id),
            'lending' => number_format($total, 2, ',', '.'),
            'total_des' =>  number_format($refund->total, 2, ',', '.'),
            'total' => number_format(abs($total - $refund->total), 2, ',', '.'),
        ]);

    }

    public function financyRefundMy(Request $request) {

        $refund = FinancyRefund::where('request_r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC')->paginate(10);
        $a_bank = UserFinancy::where('r_code', $request->session()->get('r_code'))->first();

        return view('gree_i.refund.refund_my', [
            'refund' => $refund,
            'a_bank' => $a_bank,
        ]);
    }

    public function financyRefundAll(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('refundf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('refundf_r_code');
        }
        if (!empty($request->input('id'))) {
            $request->session()->put('refundf_id', $request->input('id'));
        } else {
            $request->session()->forget('refundf_id');
        }

        $refund = FinancyRefund::leftJoin('users','financy_refund.request_r_code','=','users.r_code')
            ->select('financy_refund.*', 'users.first_name', 'users.last_name', 'users.picture', 'users.r_code')
            ->orderByRaw('financy_refund.has_analyze DESC, financy_refund.id DESC');

        if (!empty($request->session()->get('refundf_r_code'))) {
            $refund->where('users.r_code', 'like', '%'. $request->session()->get('refundf_r_code') .'%');
        }

        if (!empty($request->session()->get('refundf_id'))) {
            $refund->where('financy_refund.code', $request->session()->get('refundf_id'));
        }

        $userall = Users::all();

        return view('gree_i.refund.refund_all', [
            'userall' => $userall,
            'refund' => $refund->paginate(10),
        ]);
    }

    public function financyRefundApprov(Request $request) {

        if (!empty($request->input('r_code'))) {
            $request->session()->put('refundf_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('refundf_r_code');
        }
        if (!empty($request->input('id'))) {
            $request->session()->put('refundf_id', $request->input('id'));
        } else {
            $request->session()->forget('refundf_id');
        }

        $refund = FinancyRefund::with(
            'users.immediates',
            'financy_refund_items'
        )->where('has_analyze', 1)
            ->ValidAnalyzeProccess($request->session()->get('r_code'))
            ->orderBy('id', 'DESC');

        if (!empty($request->session()->get('refundf_r_code'))) {
            $refund->whereHas('users', function($q) use ($request) {
               $q->where('r_code', 'like', '%'. $request->session()->get('refundf_r_code') .'%');
            });

        }

        if (!empty($request->session()->get('refundf_id'))) {
            $refund->where('code', $request->session()->get('refundf_id'));
        }

        $userall = Users::all();


        return view('gree_i.refund.refund_approv', [
            'refund' => $refund->paginate(10),
            'userall' => $userall
        ]);
    }

    public function financyRefundSendAnalyze(Request $request, $id) {

        try {

            $refund = FinancyRefund::with('users.immediates', 'financy_refund_items')->find($id);

            if(!$refund)
                return redirect()->back()->with('error', 'Não foi possível encontrar a solicitação enviada para aprovação.');

            $refund->recipient_r_code = $request->srecipient_r_code;
            $refund->description = $request->desc;
            $refund->save();

            $solicitation = new App\Services\Departaments\Administration\Refund\Refund($refund, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($solicitation);
            $do_analyze->eventStart();

            return redirect('/financy/refund/my')->with('success', 'Solicitação enviada para aprovação');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function financyRefundAnalyze(Request $request) {

        try {

            $refund = FinancyRefund::with(
                'users.immediates',
                'financy_refund_items',
                'rtd_analyze.users'
            )->find($request->id);

            if(!$refund)
                return redirect()->back()->with('error', 'Solicitação não encontrada!');

            $solicitation = new App\Services\Departaments\Administration\Refund\Refund($refund, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($solicitation);

            $actions = [
                1 => 'eventApprov',
                2 => 'eventReprov',
                3 => 'eventSuspended',
                4 => 'eventRevert'
            ];

            $method = $actions[$solicitation->request->type];
            $result = $do_analyze->$method();

            return redirect('/financy/refund/approv')->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function financyRefundCorrection(Request $request, $id) {
        $refund = FinancyRefund::find($id);
        $itens = FinancyRefundItem::where('financy_refund_id', $id)->get();


        if ($refund) {

            $f_mng = Users::where('r_code', $request->session()->get('r_code'))->first();

            if (Hash::check($request->password, $f_mng->password)) {

                $user = Users::where('r_code', $refund->request_r_code)->first();
                $pattern = array(
                    'id' => $refund->id,
                    'sector_id' => $user->sector_id,
                    'itens' => $itens,
                    'r_code' => $refund->request_r_code,
                    'lending' => number_format($refund->lending, 2, ',', '.'),
                    'total_des' =>  number_format($refund->total, 2, ',', '.'),
                    'total' => number_format(abs($refund->lending - $refund->total), 2, ',', '.'),
                    'created_at' => date('d/m/Y', strtotime($refund->created_at)),
                    'title' => 'AVISO DE CORREÇÃO',
                    'description' => '',
                    'template' => 'refund.Correction',
                    'subject' => 'AVISO DE CORREÇÃO',
                );

                // send email
                SendMailJob::dispatch($pattern, $user->email);
                LogSystem("Colaborador enviou o reembolso para correção. Identificado por ". $refund->id, $request->session()->get('r_code'));

                App::setLocale($user->lang);
                NotifyUser(__('layout_i.n_refund_005_title'), $user->r_code, 'fa-exclamation', 'text-info', __('layout_i.n_refund_005'), $request->root() .'/financy/refund/edit/'. $refund->id);
                App::setLocale($request->session()->get('lang'));

                $request->session()->put('success', "Solicitante irá receber a notificação sobre a correção!");
                return redirect('/financy/refund/approv');

            } else {
                if ($f_mng->retry > 0) {
                    $f_mng->retry = $f_mng->retry - 1;

                    if ($f_mng->retry == 0) {

                        $f_mng->retry_time = date('Y-m-d H:i:s');
                        $f_mng->save();

                        $request->session()->put('error', "You have often erred in your secret password and been blocked, talk to administration.");
                        // Write Log
                        LogSystem("Colaborador errou sua senha secreta para aprovar (Reembolso) muitas vezes e foi bloqueado no sistema.", $f_mng->r_code);
                        return redirect('/financy/refund/edit/'. $id);
                    } else {

                        $f_mng->retry_time = date('Y-m-d H:i:s');
                        $f_mng->save();

                        $request->session()->put('error', "You missed your secret password, only ". $f_mng->retry ." attempt(s) left.");
                        // Write Log
                        LogSystem("Colaborador errou sua senha secreta para aprovar (Reembolso). Restou apenas ". $f_mng->retry ." tentativa(s).", $f_mng->r_code);
                        return redirect('/financy/refund/edit/'. $id);
                    }
                } else {

                    // Write Log
                    LogSystem("Colaborador está tentando aprovar (Reembolso) com sua senha secreta, mesmo já tendo sido bloqueado!", $f_mng->r_code);
                    return redirect('/financy/refund/edit/'. $id);
                }
            }
        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }
    }

    public function financyRefundItemDelete(Request $request) {


        $refund_item = FinancyRefundItem::find($request->item_id);

        if ($refund_item) {

            FinancyRefundItem::where('id', $request->item_id)->delete();

            $refund = FinancyRefund::find($refund_item->financy_refund_id);

            $itens = FinancyRefundItem::where('financy_refund_id', $refund->id)->get();
            $gen_total = 0.00;
            foreach ($itens as $item) {

                if ($item->currency > 1) {

					$response = $this->quantationBRxUSD(currency($item->currency), $item->date);
					if ($response['result'] == 0)
						return redirect()->back()->with('error', 'Data informada não tem um valor de cotação!');					

					$multiply = $response['result'];

                    $gen_total = $gen_total + round($multiply * $item->total, 2);

                } else {
                    $gen_total = $gen_total + $item->total;
                }
            }
            $refund->total = $gen_total;
            $refund->save();

            $total_lending = $refund->lending;

            return response()->json([
                'success' => true,
                'lending' => number_format($total_lending, 2, ',', '.'),
                'total_des' =>  number_format($refund->total, 2, ',', '.'),
                'total' => number_format(abs($total_lending - $refund->total), 2, ',', '.'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'Item não existe na base de dados.'
            ]);
        }

    }

    public function miscTaskDayStart(Request $request) {

        $task = TaskDay::where('r_code', $request->session()->get('r_code'))
            ->where('description', '=', '')
            ->where('is_cancelled', 0)
            ->where('end_date', '=', '0000-00-00 00:00:00')
            ->first();

        if ($task) {

            return response()->json([
                'success' => true,
                'task_id' => $task->id,
                'task_start' => date('Y-m-d H:i:s', strtotime($task->start_date)),
                'task_end' => date('Y-m-d H:i:s', strtotime($task->end_date)),
            ]);

        } else {

            if ($request->session()->get('r_code')) {

                $task = new TaskDay;
                $task->start_date = date('Y-m-d') .' '. $request->start_date;
                $task->r_code = $request->session()->get('r_code');
                $task->save();

                LogSystem("Colaborador iniciou seu home-office.", $request->session()->get('r_code'));

                return response()->json([
                    'success' => true,
                    'task_id' => $task->id,
                    'task_start' => date('Y-m-d H:i:s', strtotime($task->start_date)),
                    'task_end' => date('Y-m-d H:i:s', strtotime($task->end_date)),
                ]);

            } else {
                return response()->json([
                    'success' => false
                ]);
            }

        }


    }

    public function miscTaskDayReport(Request $request) {

        $attach = $request->attach;
        $task = TaskDay::where('r_code', $request->session()->get('r_code'))
            ->where('id', $request->task_id)
            ->first();

        $usr = Users::where('r_code', $request->session()->get('r_code'))->first();

        if ($task) {

            $task->description = $request->description;
            if ($usr->gree_id == 3) {

                $end = date('H:i', strtotime('+1 hour', strtotime($request->end_date)));
            } else {
                $end = date('H:i', strtotime($request->end_date));
            }


            /*if (date('Y-m-d') != date('Y-m-d', strtotime($task->start_date))) {

                if ($request->extrah == 1) {
                    $task->end_date = date('Y-m-d', strtotime($task->start_date)) .' '. date('H:i', strtotime('19:00'));
                    $task->hour_extra = $request->extrah;
                    $task->is_cancelled = 1;
                    $task->save();

                } else {
                    $task->end_date = date('Y-m-d', strtotime($task->start_date)) .' '. date('H:i', strtotime('17:45'));
                    $task->is_cancelled = 1;
                    $task->save();
                }

                $request->session()->put('error', 'Infelizmente seu relatório não foi salvo, pois já passou o dia de envio. Fale com o seu gestor.');
                return Redirect('/home-office');

            } else*/ if (date('H:i', strtotime('19:00')) <= $end and $request->extrah == 1) {
                $task->end_date = date('Y-m-d') .' '. date('H:i', strtotime('19:00'));

            } else if (date('H:i', strtotime('17:45')) <= $end and $request->extrah == 0) {
                $task->end_date = date('Y-m-d') .' '. date('H:i', strtotime('17:45'));

            } else {
                $task->end_date = date('Y-m-d') .' '. $request->end_date;
            }

            if ($request->hasFile('attach')) {
                $extension = $request->attach->extension();
                if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf' or $extension == 'csv' or $extension == 'xlsx') {

                    $validator = Validator::make(
                        [
                            'attach' => $attach,
                        ],
                        [
                            'attach' => 'required|max:10000',
                        ]
                    );

                    if ($validator->fails()) {

                        $request->session()->put('error', __('project_i.msg_1'));
                        return Redirect('/home-office');
                    } else {

                        $img_name = $request->session()->get('r_code') .'-'. date('YmdHis') .'.'. $extension;
                        $request->attach->storeAs('/', $img_name, 's3');
                        $url = Storage::disk('s3')->url($img_name);
                        $task->attach = $url;
                    }

                } else {

                    $request->session()->put('error', __('project_i.msg_2'));
                    return Redirect('/home-office');
                }
            }

            $task->hour_extra = $request->extrah;
            $task->save();

            $tasks = json_decode($request->tasks, true);

            if (count($tasks) > 0) {

                foreach ($tasks as $obj) {

                    $add = new App\Model\TaskDayItem;
                    $add->task_day_id = $task->id;
                    $add->subject = $obj['subject'];
                    $add->task = $obj['task'];
                    $add->result = $obj['result'];
                    $add->save();
                }

            }

            $immediates = DB::table('user_immediate')
                ->leftJoin('users','user_immediate.immediate_r_code','=','users.r_code')
                ->select('users.r_code', 'users.email')
                ->where('user_immediate.user_r_code', $request->session()->get('r_code'))
                ->get();

            $has_r = 0;
            $has_a = 0;
            if ($immediates) {

                foreach ($immediates as $key) {
                    $pattern = array(
                        'r_code' => $request->session()->get('r_code'),
                        'picture' => $request->session()->get('picture'),
                        'attach' => $task->attach,
                        'tasks' => $tasks,
                        'start_date' => $task->start_date,
                        'end_date' => $task->end_date,
                        'title' => 'RELATÓRIO DIÁRIO (HOME OFFICE)',
                        'description' => $task->description,
                        'template' => 'misc.TaskReport',
                        'subject' => 'Relatório diário (SP): '. $request->session()->get('first_name') .' "'. $task->start_date .' -> '. $task->end_date .'"',
                    );

                    if ($key->r_code == 0005) {
                        $has_r = 1;
                    } else if ($key->r_code == 1842) {
                        $has_a = 1;
                    }
                    SendMailJob::dispatch($pattern, $key->email);
                }

            }

            $pattern = array(
                'r_code' => $request->session()->get('r_code'),
                'picture' => $request->session()->get('picture'),
                'attach' => $task->attach,
                'tasks' => $tasks,
                'start_date' => $task->start_date,
                'end_date' => $task->end_date,
                'title' => 'RELATÓRIO DIÁRIO (HOME OFFICE)',
                'description' => $task->description,
                'template' => 'misc.TaskReport',
                'subject' => 'Relatório diário (SP): '. $request->session()->get('first_name') .' "'. $task->start_date .' -> '. $task->end_date .'"',
            );

            if ($has_r == 0) {
                $user = Users::where('r_code', '0005')->first();
                SendMailJob::dispatch($pattern, $user->email);
            }
            if ($has_a == 0) {
                $user = Users::where('r_code', '1842')->first();
                SendMailJob::dispatch($pattern, $user->email);
            }

            LogSystem("Colaborador encerrou seu home-office.", $request->session()->get('r_code'));
            $request->session()->put('success', 'Relatório diário concluído com sucesso!');
            return Redirect('/home-office');

        } else {

            return redirect('/home-office');

        }

    }

    public function homeOffice(Request $request) {

        $task = TaskDay::where('r_code', $request->session()->get('r_code'))
            ->where('description', '=', '')
            ->where('is_cancelled', 0)
            ->where('end_date', '=', '0000-00-00 00:00:00')
            ->first();

        if ($task) {

            /*if (date('Y-m-d') != date('Y-m-d', strtotime($task->start_date))) {

				$task->is_cancelled = 1;
				$task->save();

				return view('gree_i.homeoffice.homeoffice_cron', [
					'task_id' => 0,
					'task_start' => 0,
					'task_end' => 0,
				]);

			}*/

            return view('gree_i.homeoffice.homeoffice_cron', [
                'task_id' => $task->id,
                'task_start' => date('Y-m-d H:i:s', strtotime($task->start_date)),
                'task_end' => date('Y-m-d H:i:s', strtotime($task->end_date)),
            ]);

        } else {

            return view('gree_i.homeoffice.homeoffice_cron', [
                'task_id' => 0,
                'task_start' => 0,
                'task_end' => 0,
            ]);
        }
    }

    public function homeOfficeMy(Request $request) {

        $task = TaskDay::with('itens')->where('r_code', $request->session()->get('r_code'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('gree_i.homeoffice.homeoffice_my', [
            'task' => $task
        ]);
    }

    public function homeOfficeData(Request $request) {

        $start = date('Y-m-d 17:45:00');
        $end = date('Y-m-d 19:00:00');

        // SAVE FILTERS
        if (!empty($request->input('r_code'))) {
            $request->session()->put('Homeofficef_r_code', $request->input('r_code'));
        } else {
            $request->session()->forget('Homeofficef_r_code');
        }
        if (!empty($request->input('start_date'))) {
            $request->session()->put('Homeofficef_start_date', $request->input('start_date'));
        } else {
            $request->session()->forget('Homeofficef_start_date');
        }
        if (!empty($request->input('end_date'))) {
            $request->session()->put('Homeofficef_end_date', $request->input('end_date'));
        } else {
            $request->session()->forget('Homeofficef_end_date');
        }

        $task = TaskDay::with('itens')->orderBy('updated_at', 'DESC');

        $active = TaskDay::where('end_date', '0000-00-00 00:00:00')
            ->where('is_cancelled', 0)
            ->count();

        $active_total = UserOnPermissions::where('perm_id', 10)
            ->count();

        $cancel = TaskDay::where('is_cancelled', 1)
            ->count();


        if (!empty($request->session()->get('Homeofficef_r_code'))) {
            $task->where('r_code', $request->session()->get('Homeofficef_r_code'));
        }
        if (!empty($request->session()->get('Homeofficef_start_date'))) {
            $start = date('Y-m-d 17:45:00', strtotime($request->session()->get('Homeofficef_start_date')));
            $end = date('Y-m-d 19:00:00', strtotime($request->session()->get('Homeofficef_start_date')));
            $task->where('start_date', '>=', $request->session()->get('Homeofficef_start_date'));
        }
        if (!empty($request->session()->get('Homeofficef_end_date'))) {
            $task->where('start_date', '<=', $request->session()->get('Homeofficef_end_date'));
        }

        $hour = DB::table('task_day')
            ->where('end_date', '!=', '0000-00-00 00:00:00')
            ->where('is_cancelled', 0)
            ->where('hour_extra', 1)
            ->whereBetween('end_date', [$start, $end])
            ->select(DB::raw("SUM(time_to_sec(timediff(end_date, '". $start ."')) / 3600) as result"))
            ->get(['result']);

        $userall = Users::all();

        return view('gree_i.homeoffice.homeoffice_data', [
            'active' => $active,
            'active_total' => $active_total,
            'cancel' => $cancel,
            'hour' => $hour,
            'userall' => $userall,
            'task' => $task->paginate(10)
        ]);
    }

    public function homeOfficeOnline(Request $request) {

        $active = TaskDay::where('end_date', '0000-00-00 00:00:00')
            ->where('is_cancelled', 0)
            ->paginate(10);

        return view('gree_i.homeoffice.homeoffice_online', [
            'active' => $active
        ]);
    }

    public function hourExtraNew(Request $request) {

        return view('gree_i.hourextra.new');
    }

    public function hourExtraNew_do(Request $request) {

        $item = new App\Model\RhHourExtra;
        $item->r_code = $request->session()->get('r_code');
        $item->start_date = date('Y-m-d H:i:s', strtotime($request->start_date .' '. $request->start_hour));
        $item->end_date = date('Y-m-d H:i:s', strtotime($request->start_date .' '. $request->end_hour));
        $item->description = $request->description;

        if ($request->hasFile('attach')) {
            $extension = $request->attach->extension();
            if ($extension == 'jpg' or $extension == 'png' or $extension == 'gif' or $extension == 'jpeg' or $extension == 'pdf' or $extension == 'csv' or $extension == 'xlsx') {

                $validator = Validator::make(
                    [
                        'attach' => $request->attach,
                    ],
                    [
                        'attach' => 'required|max:10000',
                    ]
                );

                if ($validator->fails()) {

                    $request->session()->put('error', __('project_i.msg_1'));
                    return Redirect('/hour-extra/my');
                } else {

                    $img_name = $request->session()->get('r_code') .'-'. date('YmdHis') .'.'. $extension;
                    $request->attach->storeAs('/', $img_name, 's3');
                    $url = Storage::disk('s3')->url($img_name);
                    $item->attach = $url;
                }

            } else {

                $request->session()->put('error', __('project_i.msg_2'));
                return Redirect('/hour-extra/my');
            }
        }

        $item->save();

        $immediates = DB::table('user_immediate')
            ->leftJoin('users','user_immediate.immediate_r_code','=','users.r_code')
            ->select('users.r_code', 'users.email')
            ->where('user_immediate.user_r_code', $request->session()->get('r_code'))
            ->get();

        $start_date = new \DateTime($item->start_date);
        $since_start = $start_date->diff(new \DateTime($item->end_date));

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if ($immediates) {

            foreach ($immediates as $key) {

                $pattern = array(
                    'title' => 'APROVAÇÃO DE HORA EXTRA',
                    'description' => nl2br("Esta solicitação se encontra em processo de aprovação, para aprovar e analisar<br>suas informações  e documentos entre no painel."),
                    'item' => $item,
                    'since_start' => $since_start,
                    'user' => $user,
                    'template' => 'hourextra.requestApprov',
                    'subject' => 'Pedido de aprovação de Hora extra',
                );

                NotifyUser('Pedido de aprovação de Hora extra #'. $item->id, $key->r_code, 'fa-exclamation', 'text-info', 'Necessitam da sua aprovação para uma solicitação de hora extra, clique aqui para visualizar.', $request->root() .'/hour-extra/approv');
                SendMailJob::dispatch($pattern, $key->email);

            }

        }

        return Redirect('/hour-extra/my')->with('success', 'Solicitação criada com sucesso!');
    }

    public function hourExtraMy(Request $request) {

        $itens = App\Model\RhHourExtra::with('manager')
            ->where('r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC');

        if ($request->start_date)
            $request->session()->put('filter_start_date', $request->start_date);
        else
            $request->session()->forget('filter_start_date');

        if ($request->end_date)
            $request->session()->put('filter_end_date', $request->start_date);
        else
            $request->session()->forget('filter_end_date');

        if ($request->session()->get('filter_start_date'))
            $itens->where('start_date', '>=', $request->session()->get('filter_start_date'));

        if ($request->session()->get('filter_end_date'))
            $itens->where('start_date', '<=', date('Y-m-d 23:59', strtotime($request->session()->get('filter_end_date'))));

        if ($request->has('export')) {

            $heading = array('Colaborador', 'Matricula', 'Iniciou em', 'Concluiu em', 'Horas trabalhadas', 'Aprovado por', 'Observação do gestor');
            $rows = array();

            foreach ($itens->get() as $key) {
                $line = array();

                $start_date = new \DateTime($key->start_date);
                $since_start = $start_date->diff(new \DateTime($key->end_date));

                $manager = '';
                if ($key->manager)
                    $manager = $key->manager->first_name .' '. $key->manager->last_name;

                $line[0] = $key->user->first_name .' '. $key->user->last_name;
                $line[1] = $key->r_code;
                $line[2] = $key->start_date;
                $line[3] = $key->end_date;
                $line[4] = $since_start->h .':'. $since_start->i .':'. $since_start->s .'';
                $line[5] = $manager;
                $line[6] = $key->mng_obs;

                array_push($rows, $line);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'HorasExtrasGree|'. date('Y-m-d H:i:s') .'.xlsx');
        } else {

            return view('gree_i.hourextra.my', [
                'itens' => $itens->paginate(10),
            ]);
        }

    }

    public function hourExtraAll(Request $request) {

        $itens = App\Model\RhHourExtra::with('manager', 'user')->orderBy('id', 'DESC');

        if ($request->start_date)
            $request->session()->put('filter_start_date', $request->start_date);
        else
            $request->session()->forget('filter_start_date');

        if ($request->end_date)
            $request->session()->put('filter_end_date', $request->start_date);
        else
            $request->session()->forget('filter_end_date');

        if (!empty($request->input('r_code')))
            $request->session()->put('userf_r_code', $request->input('r_code'));
        else
            $request->session()->forget('userf_r_code');


        if ($request->session()->get('filter_start_date'))
            $itens->where('start_date', '>=', $request->session()->get('filter_start_date'));

        if ($request->session()->get('filter_end_date'))
            $itens->where('start_date', '<=', date('Y-m-d 23:59', strtotime($request->session()->get('filter_end_date'))));

        if (!empty($request->session()->get('userf_r_code')))
            $itens->where('r_code', $request->session()->get('userf_r_code'));

        $userall = Users::all();

        if ($request->has('export')) {

            $heading = array('Colaborador', 'Matricula', 'Iniciou em', 'Concluiu em', 'Horas trabalhadas', 'Aprovado por', 'Observação do gestor');
            $rows = array();

            foreach ($itens->get() as $key) {
                $line = array();

                $start_date = new \DateTime($key->start_date);
                $since_start = $start_date->diff(new \DateTime($key->end_date));

                $manager = '';
                if ($key->manager)
                    $manager = $key->manager->first_name .' '. $key->manager->last_name;

                $line[0] = $key->user->first_name .' '. $key->user->last_name;
                $line[1] = $key->r_code;
                $line[2] = $key->start_date;
                $line[3] = $key->end_date;
                $line[4] = $since_start->h .':'. $since_start->i .':'. $since_start->s .'';
                $line[5] = $manager;
                $line[6] = $key->mng_obs;

                array_push($rows, $line);
            }

            return Excel::download(new DefaultExport($heading, $rows), 'HorasExtrasGree|'. date('Y-m-d H:i:s') .'.xlsx');
        } else {

            return view('gree_i.hourextra.all', [
                'itens' => $itens->paginate(10),
                'userall' => $userall,
            ]);
        }
    }

    public function hourExtraApprov(Request $request) {

        $itens = App\Model\RhHourExtra::with('manager', 'user')
            ->where('is_cancelled', 0)
            ->InAnalyzes($request->session()->get('r_code'))->orderBy('id', 'DESC');

        if ($request->start_date)
            $request->session()->put('filter_start_date', $request->start_date);
        else
            $request->session()->forget('filter_start_date');

        if ($request->end_date)
            $request->session()->put('filter_end_date', $request->start_date);
        else
            $request->session()->forget('filter_end_date');

        if (!empty($request->input('r_code')))
            $request->session()->put('userf_r_code', $request->input('r_code'));
        else
            $request->session()->forget('userf_r_code');


        if ($request->session()->get('filter_start_date'))
            $itens->where('start_date', '>=', $request->session()->get('filter_start_date'));

        if ($request->session()->get('filter_end_date'))
            $itens->where('start_date', '<=', date('Y-m-d 23:59', strtotime($request->session()->get('filter_end_date'))));

        if (!empty($request->session()->get('userf_r_code')))
            $itens->where('r_code', $request->session()->get('userf_r_code'));

        $userall = Users::all();

        return view('gree_i.hourextra.approv', [
            'itens' => $itens->paginate(10),
            'userall' => $userall,
        ]);
    }

    public function hourExtraCancel(Request $request, $id) {

        $task = App\Model\RhHourExtra::find($id);

        if ($task) {

            $task->is_cancelled = 1;
            $task->save();

            return redirect()->back()->with('success', 'Você cancelou a solicitação de hora extra!');

        } else {

            return redirect()->back()->with('error', 'Hora extra não foi encontrado.');
        }
    }

    public function hourExtraApprov_do(Request $request, $id) {

        $item = App\Model\RhHourExtra::with('user')->where('id', $id)->first();

        if ($item) {

            $item->is_approv = $request->analyze == 1 ? 1 : 0;
            $item->is_reprov = $request->analyze == 2 ? 1 : 0;
            $item->mng_obs = $request->obs;
            $item->mng_r_code = $request->session()->get('r_code');
            $item->save();

            if ($request->analyze == 1) {

                $pattern = array(
                    'title' => 'HORA EXTRA APROVADO',
                    'description' => nl2br("Sua solicitação de hora extra do dia: ". date('d/m/Y', strtotime($item->start_date)) .", foi aprovada! para mais informações acesse:<br> <a href='". $request->root() ."/hour-extra/my'>". $request->root() ."/hour-extra/my</a>."),
                    'template' => 'misc.Default',
                    'subject' => 'Hora extra aprovada #'. $item->id,
                );

                NotifyUser('Hora extra aprovada #'. $item->id, $item->r_code, 'fa-check', 'text-info', 'Sua solicitação de hora extra, foi aprovada! Clique aqui para visualizar.', $request->root() .'/hour-extra/my');
                SendMailJob::dispatch($pattern, $item->user->email);
            } else {

                $pattern = array(
                    'title' => 'HORA EXTRA REPROVADO',
                    'description' => nl2br("Sua solicitação de hora extra do dia: ". date('d/m/Y', strtotime($item->start_date)) .", foi reprovado! para mais informações acesse:<br> <a href='". $request->root() ."/hour-extra/my'>". $request->root() ."/hour-extra/my</a>."),
                    'template' => 'misc.Default',
                    'subject' => 'Hora extra reprovado #'. $item->id,
                );

                NotifyUser('Hora extra reprovado #'. $item->id, $item->r_code, 'fa-times', 'text-info', 'Sua solicitação de hora extra, foi reprovado! Clique aqui para visualizar.', $request->root() .'/hour-extra/my');
                SendMailJob::dispatch($pattern, $item->user->email);
            }

            return redirect()->back()->with('success', 'Você realizou análise da hora extra com sucesso!');

        } else {

            return redirect()->back()->with('error', 'Hora extra não foi encontrado.');
        }
    }

    public function chatMain(Request $request) {

        $rcode = $request->session()->get('r_code');
        $chat_pms = ChatPm::where('r_code_1', $rcode)->orWhere('r_code_2', $rcode)->get();
        $r_code_1 = $chat_pms->where('r_code_1', '!=', $rcode)->pluck('r_code_1');
        $r_code_2 = $chat_pms->where('r_code_2', '!=', $rcode)->pluck('r_code_2');
        $peoples = $r_code_1->merge($r_code_2);
        $usersall = Users::where('is_active', 1)
            ->where('r_code', '!=', $rcode)
            ->orderBy('users.status', 'DESC')
            ->orderBy('users.first_name', 'ASC')
            ->get();

        $users = $usersall->whereNotIn('r_code', $peoples);
        $chats = Users::selectRaw("*, (SELECT count(*)
     	        FROM chat_message
     	        WHERE chat_message.s_r_code = users.r_code
				AND chat_message.r_r_code = $rcode
     	        AND chat_message.has_read = 0) as total")
            ->whereIn('r_code', $peoples)
            ->orderByRaw("users.is_active DESC, total DESC")
            ->get();

        $group = ChatGroup::Where(function($q) use ($request){
            $q->where('only_sector', 1)->where('sector_id', $request->session()->get('sector'))
                ->where('only_group', 0);
        })->orWhere(function($q1) use ($rcode) {
            $q1->where('only_group', 1)->where('group_r_code', 'LIKE', '%'.$rcode.'%')
                ->where('only_sector', 0);
        })->orWhere(function($q2) use ($rcode) {
            $q2->where('only_group', 0)->where('only_sector', 0);
        })->get();

        return view('gree_i.chat.main', [
            'group' => $group,
            'chats' => $chats,
            'users' => $users,
        ]);
    }

    public function chatMessages(Request $request) {

        $pmid = 0;
        if ($request->type == 'single') {
            $pm = ChatPm::where('r_code_1', $request->session()->get('r_code'))
                ->where('r_code_2', $request->r_code)
                ->orWhere(function ($query) use ($request) {
                    $query->where('r_code_2', $request->session()->get('r_code'))
                        ->where('r_code_1', $request->r_code);
                })
                ->first();

            $message = array();

            if ($pm) {
                $data = ChatMessage::where('pm_id', $pm->id)
                    ->where('has_read', 0)
                    ->get();

                foreach ($data as $key) {
                    $ms = ChatMessage::find($key->id);
                    $ms->has_read = 1;
                    $ms->save();
                }

                $pmid = $pm->id;
                ChatMessage::where('pm_id', $pm->id)->chunk(100, function($data) use (&$message)
                {
                    foreach ($data as $key)
                    {
                        $row = array();
                        $user_s = Users::where('r_code', $key->s_r_code)->first();
                        $user_r = Users::where('r_code', $key->r_r_code)->first();
                        $row['s_r_code'] = $key->s_r_code;
                        $row['r_r_code'] = $key->r_r_code;
                        $row['name_s'] = getENameF($user_s->r_code);
                        $row['name_r'] = getENameF($user_r->r_code);
                        $row['picture_s'] = $user_s->picture != "" ? $user_s->picture : "/media/avatars/avatar10.jpg";
                        $row['picture_r'] = $user_r->picture != "" ? $user_r->picture : "/media/avatars/avatar10.jpg";
                        $row['msg'] = $key->message;
                        $row['attach'] = $key->attach;
                        $row['sector_s'] = sectorName($user_s->sector_id);
                        $row['sector_r'] = sectorName($user_r->sector_id);
                        $row['time'] = date('d/m/Y H:i:s', strtotime($key->created_at));
                        $row['date'] = $key->created_at;

                        array_push ($message, $row);

                    }
                });

            }
        } else {

            $message = array();
            ChatMessage::where('group_id', $request->g_id)->chunk(100, function($data) use (&$message)
            {
                foreach ($data as $key)
                {
                    $row = array();
                    $user_s = Users::where('r_code', $key->s_r_code)->first();
                    $row['s_r_code'] = $key->s_r_code;
                    $row['name_s'] = getENameF($user_s->r_code);
                    $row['picture_s'] = $user_s->picture != "" ? $user_s->picture : "/media/avatars/avatar10.jpg";
                    $row['msg'] = $key->message;
                    $row['attach'] = $key->attach;
                    $row['sector_s'] = sectorName($user_s->sector_id);
                    $row['time'] = date('h:i A', strtotime($key->created_at));
                    $row['date'] = $key->created_at;

                    array_push ($message, $row);

                }
            });
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'pm_id' => $pmid,
        ], 200, array(), JSON_PRETTY_PRINT);
    }

    public function chatNewVersion(Request $request) {

        $params = [
            'version' => 2,
        ];

        // FIRE EVENT
        event(new EventSocket($params, 'newVersion'));

        return response()->json([
            'success' => true,
            'version' => 2,
        ]);

    }

    public function chatNewMessage(Request $request) {
        $pmid = 0;
        $attach = $request->file('attach');
        $attach = $request->attach;

        $pm = ChatPm::where('r_code_1', $request->session()->get('r_code'))
            ->where('r_code_2', $request->receiver)
            ->orWhere(function ($query) use ($request) {
                $query->where('r_code_2', $request->session()->get('r_code'))
                    ->where('r_code_1', $request->receiver);
            })
            ->first();
        if (!$pm) {
            $pm = new ChatPm;
            $pm->r_code_1 = $request->session()->get('r_code');
            $pm->r_code_2 = $request->receiver;
            $pm->save();
        } else {
            $pm->updated_at = date('Y-m-d H:i:s');
            $pm->save();
        }
        $pmid = $pm->id;

        $message = new ChatMessage;
        $message->s_r_code = $request->session()->get('r_code');
        $message->message = $request->msg;
        $message->r_r_code = $request->receiver;
        $message->pm_id = $pmid;
        $message->group_id = $request->group;

        if ($request->hasFile('attach')) {
            $extension = $request->attach->extension();
            $img_name = $message->id .'-'. date('YmdHis') .'.'. $extension;
            $request->attach->storeAs('/', $img_name, 's3');
            $url = Storage::disk('s3')->url($img_name);
            $message->attach = $url;
        }

        $message->save();

        $total = ChatMessage::where('chat_message.s_r_code', $request->session()->get('r_code'))
            ->where('chat_message.has_read', 0)
            ->where('chat_message.r_r_code', $request->receiver)
            ->count();

        $params = [
            'name' => getENameF($request->session()->get('r_code')),
            'msg' => $request->msg,
            'msg_total' => $total,
            'receiver' => $request->receiver,
            'attach' => $message->attach,
            'id' => $request->session()->get('r_code'),
            'sector' => sectorName($request->session()->get('sector')),
            'picture' => $request->session()->get('picture'),
            'time' => date('h:i A', strtotime($message->created_at)),
            'date' => $message->created_at,
            'pm_id' => $pmid,
        ];

        // FIRE EVENT
        event(new EventSocket($params, 'newMessage'));

        $user = Users::where('r_code', $request->receiver)->first();
        if ($user) {
            if ($user->token) {
                $fields = array
                (
                    'notification' => array(
                        'title' => $request->session()->get('first_name'),
                        'body' => $request->msg,
                        'click_action' => 'https://gree-app.com.br/chat/main',
                        'icon' => $request->session()->get('picture')
                    ),
                    'to'  => $user->token,
                );

                sendPushNotification($fields);
            }

            if ($user->token_mobile) {
                $fields = array
                (
                    'notification' => array(
                        'title' => $request->session()->get('first_name'),
                        'body' => $request->msg,
                        'click_action' => 'https://gree-app.com.br/chat/main',
                        'icon' => $request->session()->get('picture')
                    ),
                    'to'  => $user->token_mobile,
                );

                sendPushNotification($fields);
            }
        }

        return response()->json([
            'success' => true,
            'pm_id' => $pmid,
        ]);

    }

    public function chatStatus(Request $request, $r_code, $status) {
        $user = Users::where('r_code', $r_code)->first();

        if ($user) {

            $user->status = $status;
            $user->save();
        } else {

            return;
        }

    }

    public function SurveyList(Request $request) {

        $survey = DB::table('survey')
            ->orderBy('id', 'DESC');

        return view('gree_i.survey.survey_list', [
            'survey' => $survey->paginate(10),
        ]);
    }

    public function SurveyEdit(Request $request, $id) {

        if ($id == 0) {

            $name = "";
            $description = "";
            $is_active = 0;
            $questions = "";

        } else {

            $survey = Survey::find($id);

            if ($survey) {

                $name = $survey->name;
                $description = $survey->description;
                $is_active = $survey->is_active;
                $questions = SurveyQuestions::where('survey_id', $survey->id)->get();

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.survey.survey_edit', [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'is_active' => $is_active,
            'questions' => $questions,
        ]);
    }

    public function SurveyEdit_do(Request $request) {

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
        $survey->save();

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
                    $item->survey_id = $survey->id;
                    $item->save();
                }
            }
        }

        $request->session()->put('success', "Lista de pesquisa atualizada com sucesso!");
        return redirect('/survey/all');
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

        $answer = DB::table('user_survey')
            ->leftJoin('users','user_survey.user_r_code','=','users.r_code')
            ->leftJoin('survey','user_survey.survey_id','=','survey.id')
            ->select('user_survey.*', 'users.first_name', 'users.last_name', 'users.picture', 'users.r_code', 'survey.name')
            ->orderBy('user_survey.id', 'DESC');

        if (!empty($request->session()->get('surveyf_r_code'))) {
            $answer->where('users.r_code', $request->session()->get('surveyf_r_code'));
        }

        $userall = Users::all();

        return view('gree_i.survey.answers', [
            'userall' => $userall,
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
            $survey = UserSurvey::where('user_r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC')->first();
            if ($survey) {
                if (date('Y-m-d', strtotime($survey->created_at)) >= date('Y-m-d')) {
                    $request->session()->put('s_report', 1);
                } else {

                    $survey = new UserSurvey;
                    $survey->user_r_code = $request->session()->get('r_code');
                    $survey->save();
                    $request->session()->put('s_report', 1);

                    $questions = array();
                    $q_survey = SurveyQuestions::where('survey_id', $request->survey_id)->count();
                    $q_total = $q_survey + 1;
                    $resp = 0;

                    for ($i = 1; $i < $q_total; $i++) {
                        $save_q = new UserSurveyAnswer;
                        $save_q->question_id = $request->input('question_'. $i .'_id');
                        $save_q->user_answer_id = $survey->id;
                        $save_q->user_r_code = $request->session()->get('r_code');
                        $save_q->answer_option = $request->input('question_'. $i .'');
                        $save_q->answer_obs = $request->input('question_'. $i .'_input');
                        $save_q->save();

                        $f_question = SurveyQuestions::find($request->input('question_'. $i .'_id'));

                        if ($resp == 0 and $f_question->is_notify == 1) {
                            $resp = $request->input('question_'. $i .'') == 'Sim' ? 1 : 0;
                        }

                        $push = array();
                        $push['radio'] = $request->input('question_'. $i .'');
                        $push['input'] = $request->input('question_'. $i .'_input');
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

                    if ($resp == 1) {
                        $notify = SurveyNotifyUser::where('survey_id', $request->survey_id)->get();

                        foreach ($notify as $key) {
                            $user = Users::where('r_code', $key->user_r_code)->first();
                            SendMailJob::dispatch($pattern, $user->email);
                        }
                    }



                }
            } else {
                $questions = array();
                $q_survey = SurveyQuestions::where('survey_id', $request->survey_id)->count();
                $q_total = $q_survey + 1;
                $resp = 0;

                $survey = new UserSurvey;
                $survey->user_r_code = $request->session()->get('r_code');
                $survey->save();
                $request->session()->put('s_report', 1);

                for ($i = 1; $i < $q_total; $i++) {
                    $save_q = new UserSurveyAnswer;
                    $save_q->question_id = $request->input('question_'. $i .'_id');
                    $save_q->user_answer_id = $survey->id;
                    $save_q->user_r_code = $request->session()->get('r_code');
                    $save_q->answer_option = $request->input('question_'. $i .'');
                    $save_q->answer_obs = $request->input('question_'. $i .'_input');
                    $save_q->save();

                    $f_question = SurveyQuestions::find($request->input('question_'. $i .'_id'));

                    if ($resp == 0 and $f_question->is_notify == 1) {
                        $resp = $request->input('question_'. $i .'') == 'Sim' ? 1 : 0;
                    }

                    $push = array();
                    $push['radio'] = $request->input('question_'. $i .'');
                    $push['input'] = $request->input('question_'. $i .'_input');
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

                if ($resp == 1) {
                    $notify = SurveyNotifyUser::where('survey_id', 1)->get();

                    foreach ($notify as $key) {
                        $user = Users::where('r_code', $key->user_r_code)->first();
                        SendMailJob::dispatch($pattern, $user->email);
                    }
                }
            }
        }
    }

    public function main(Request $request) {


        return view('gree_i.layout');
    }

    public function greeInterno(Request $request) {


        return view('gree_i.backdoor');
    }

    public function greeInternoLatLong(Request $request) {
        $data = new Guests;
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->ip_external = $request->ip_external;
        $data->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function idlEngEditProduct(Request $request, $id) {

        $unity = ProductAirUnity::all();
        $voltage = Voltages::all();
        $product_category = ProductCategory::where('is_active', 1)->get();

        if ($id == 0) {

            $sales_code = [];
            $import = 1;
            $code_unity = [];
            $model = "";
            $dcr = "";
            $bar_code = "";
            $serial_number = "";
            $ncm = "";
            $unity_selected = 0;
            $height = "";
            $net_weight = "";
            $gross_weight = "";
            $length = 0.00;
			$length_box = 0.00;
            $width = 0.00;
			$width_box = 0.00;
            $height = 0.00;
			$height_box = 0.00;
            $description = "";
            $observation = "";
            $exploded_view = "";
            $electric_circuit = "";
            $manual = "";
            $datasheet = "";
            $parts = "";
            $product_category_id = 0;
            $product_sub_level_1_id = 0;
            $product_sub_level_2_id = 0;
            $product_sub_level_3_id = 0;
            $residential = 0;
            $commercial = 1;

            $pv = [];

        } else {

            $product = ProductAir::find($id);

            if ($product) {

				$sales_code = [];
                if(!empty($product->sales_code)) {
                    $sales_code = explode(',', $product->sales_code);
                }
                $import = $product->import;
				$code_unity = [];
                if(!empty($product->code_unity)) {
                    $code_unity = explode(',', $product->code_unity);
                }
                
                $model = $product->model;
                $dcr = $product->dcr;
                $bar_code = $product->bar_code;
                $serial_number = $product->serial_number;
                $ncm = $product->ncm;
                $unity_selected = $product->unity;
                $height = $product->height;
                $net_weight = $product->net_weight;
                $gross_weight = $product->gross_weight;
                $length = $product->length;
                $width = $product->width;
                $height = $product->height;
				$length_box = $product->length_box;
                $width_box = $product->width_box;
                $height_box = $product->height_box;
                $description = $product->description;
                $observation = $product->observation;

                $exploded_view = $product->exploded_view;
                $electric_circuit = $product->electric_circuit;
                $manual = $product->manual;
                $datasheet = $product->datasheet;
                $parts = $product->parts;
                $residential = $product->residential;
                $commercial = $product->commercial;

                $product_category_id = $product->product_category_id;
                $product_sub_level_1_id = ProductSubLevel1::find($product->product_sub_level_1_id);
                if (!$product_sub_level_1_id) {
                    $product_sub_level_1_id = 0;
                }
                $product_sub_level_2_id = ProductSubLevel2::find($product->product_sub_level_2_id);
                if (!$product_sub_level_2_id) {
                    $product_sub_level_2_id = 0;
                }
                $product_sub_level_3_id = ProductSubLevel3::find($product->product_sub_level_3_id);
                if (!$product_sub_level_3_id) {
                    $product_sub_level_3_id = 0;
                }

                $pv = ProductControl::where('product_category_id', $product->product_category_id)
                    ->where('product_id', $product->id)
                    ->first();

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.engineering.product_edit', [
            'id' => $id,
            'sales_code' => $sales_code,
            'import' => $import,
            'code_unity' => $code_unity,
            'model' => $model,
            'dcr' => $dcr,
            'bar_code' => $bar_code,
            'serial_number' => $serial_number,
            'ncm' => $ncm,
            'unity_selected' => $unity_selected,
            'height' => $height,
            'net_weight' => $net_weight,
            'gross_weight' => $gross_weight,
            'length' => $length,
            'width' => $width,
            'height' => $height,
			'length_box' => $length_box,
            'width_box' => $width_box,
            'height_box' => $height_box,
            'description' => $description,
            'observation' => $observation,
            'exploded_view' => $exploded_view,
            'electric_circuit' => $electric_circuit,
            'manual' => $manual,
            'datasheet' => $datasheet,
            'parts' => $parts,
            'residential' => $residential,
            'commercial' => $commercial,
            'pv' => $pv,
            'product_category' => $product_category,
            'unity' => $unity,
            'voltage' => $voltage,
            'product_category_id' => $product_category_id,
            'product_sub_level_1_id' => $product_sub_level_1_id,
            'product_sub_level_2_id' => $product_sub_level_2_id,
            'product_sub_level_3_id' => $product_sub_level_3_id,
        ]);
    }

    public function idlEngAllProduct(Request $request) {

        // SAVE FILTERS
        if (!empty($request->input('product_category'))) {
            $request->session()->put('pf_product_category', $request->input('product_category'));
        } else {
            $request->session()->forget('pf_product_category');
        }
        if (!empty($request->input('product_sub_level_1'))) {
            $request->session()->put('pf_product_sub_level_1', $request->input('product_sub_level_1'));
        } else {
            $request->session()->forget('pf_product_sub_level_1');
        }
        if (!empty($request->input('product_sub_level_2'))) {
            $request->session()->put('pf_product_sub_level_2', $request->input('product_sub_level_2'));
        } else {
            $request->session()->forget('pf_product_sub_level_2');
        }
        if (!empty($request->input('product_sub_level_3'))) {
            $request->session()->put('pf_product_sub_level_3', $request->input('product_sub_level_3'));
        } else {
            $request->session()->forget('pf_product_sub_level_3');
        }

        if (!empty($request->input('sales_code'))) {
            $request->session()->put('pf_sales_code', $request->input('sales_code'));
        } else {
            $request->session()->forget('pf_sales_code');
        }
        if (!empty($request->input('code_unity'))) {
            $request->session()->put('pf_code_unity', $request->input('code_unity'));
        } else {
            $request->session()->forget('pf_code_unity');
        }
        if (!empty($request->input('model'))) {
            $request->session()->put('pf_model', $request->input('model'));
        } else {
            $request->session()->forget('pf_model');
        }
        if (!empty($request->input('n_serie'))) {
            $request->session()->put('pf_n_serie', $request->input('n_serie'));
        } else {
            $request->session()->forget('pf_n_serie');
        }
        if (!empty($request->input('bar_code'))) {
            $request->session()->put('pf_bar_code', $request->input('bar_code'));
        } else {
            $request->session()->forget('pf_bar_code');
        }

        $products = ProductAir::orderBy('id', 'DESC');
        $product_category = ProductCategory::where('is_active', 1)->get();
        $product_category_id = "";
        $product_sub_level_1_id = "";
        $product_sub_level_2_id = "";
        $product_sub_level_3_id = "";

        if (!empty($request->session()->get('pf_sales_code'))) {
			$products->whereRaw("FIND_IN_SET('".$request->session()->get('pf_sales_code')."', sales_code)");
        }
        if (!empty($request->session()->get('pf_code_unity'))) {
            $products->whereRaw("FIND_IN_SET('".$request->session()->get('pf_code_unity')."', code_unity)");
        }
        if (!empty($request->session()->get('pf_model'))) {
            $products->where('model', 'like', '%'. $request->session()->get('pf_model') .'%');
        }
        if (!empty($request->session()->get('pf_product_category'))) {
            $product_category_id = $request->session()->get('pf_product_category');
            $products->where('product_category_id', $request->session()->get('pf_product_category'));
        }
        if (!empty($request->session()->get('pf_product_sub_level_1'))) {
            $product_sub_level_1_id = ProductSubLevel1::find($request->session()->get('pf_product_sub_level_1'));
            $products->where('product_sub_level_1_id', $request->session()->get('pf_product_sub_level_1'));
        }
        if (!empty($request->session()->get('pf_product_sub_level_2'))) {
            $product_sub_level_2_id = ProductSubLevel2::find($request->session()->get('pf_product_sub_level_2'));
            $products->where('product_sub_level_2_id', $request->session()->get('pf_product_sub_level_2'));
        }
        if (!empty($request->session()->get('pf_product_sub_level_3'))) {
            $product_sub_level_3_id = ProductSubLevel3::find($request->session()->get('pf_product_sub_level_3'));
            $products->where('product_sub_level_3_id', $request->session()->get('pf_product_sub_level_3'));
        }
        if (!empty($request->session()->get('pf_n_serie'))) {
            $products->where('serial_number', $request->session()->get('pf_n_serie'));
        }
        if (!empty($request->session()->get('pf_bar_code'))) {
            $products->where('pf_bar_code', $request->session()->get('pf_bar_code'));
        }

        return view('gree_i.engineering.product_list', [
            'products' => $products->paginate(10),
            'product_category' => $product_category,
            'product_category_id' => $product_category_id,
            'product_sub_level_1_id' => $product_sub_level_1_id,
            'product_sub_level_2_id' => $product_sub_level_2_id,
            'product_sub_level_3_id' => $product_sub_level_3_id,
        ]);
    }

    public function idlEngEditProduct_do(Request $request) {

        if ($request->id == 0) {
            $product = new ProductAir;
        } else {
            $product = ProductAir::find($request->id);
        }

		$product->sales_code = $request->sales_code ? implode(",", $request->sales_code) : null;
        $product->import = $request->import;
		$product->code_unity = $request->code_unity ? implode(",", $request->code_unity) : null;
        $product->model = $request->model;
        $product->unity = $request->unity;
        $product->dcr = $request->dcr;
        $product->bar_code = $request->bar_code;
        $product->serial_number = $request->serial_number;
        $product->ncm = $request->ncm;
        $product->net_weight = $request->net_weight;
        $product->gross_weight = $request->gross_weight;
        $product->length = $request->length;
        $product->width = $request->width;
        $product->height = $request->height;
		$product->length_box = $request->length_box;
        $product->width_box = $request->width_box;
        $product->height_box = $request->height_box;
        $product->description = $request->description;
        $product->observation = $request->observation;
        $product->product_category_id = $request->product_category;
        $product->product_sub_level_1_id = $request->product_sub_level_1 != null ? $request->product_sub_level_1 : 0;
        $product->product_sub_level_2_id = $request->product_sub_level_2 != null ? $request->product_sub_level_2 : 0;
        $product->product_sub_level_3_id = $request->product_sub_level_3 != null ? $request->product_sub_level_3 : 0;

        $product->residential = ($request->residential == "") ? 0 : 1;
        $product->commercial = ($request->commercial == "") ? 0 : 1;

        if ($request->hasFile('exploded_view')) {
            $response = $this->uploadS3(1, $request->exploded_view, $request);
            if ($response['success']) {
                $product->exploded_view = $response['url'];
            } else {
                return Redirect('/engineering/product/all');
            }
        }
        if ($request->hasFile('electric_circuit')) {
            $response = $this->uploadS3(2, $request->electric_circuit, $request);
            if ($response['success']) {
                $product->electric_circuit = $response['url'];
            } else {
                return Redirect('/engineering/product/all');
            }
        }
        if ($request->hasFile('manual')) {
            $response = $this->uploadS3(3, $request->manual, $request);
            if ($response['success']) {
                $product->manual = $response['url'];
            } else {
                return Redirect('/engineering/product/all');
            }
        }
        if ($request->hasFile('datasheet')) {
            $response = $this->uploadS3(4, $request->datasheet, $request);
            if ($response['success']) {
                $product->datasheet = $response['url'];
            } else {
                return Redirect('/engineering/product/all');
            }
        }
        if ($request->hasFile('parts')) {
            $response = $this->uploadS3(5, $request->parts, $request);
            if ($response['success']) {
                $product->parts = $response['url'];
            } else {
                return Redirect('/engineering/product/all');
            }
        }


        $product->save();

        if ($request->voltage) {
            $pc_add = ProductControl::where('product_id', $product->id)->first();
            if (!$pc_add) {
                $pc_add = new ProductControl;
            }
            $pc_add->product_id = $product->id;
            $pc_add->product_category_id = $product->product_category_id;
            $pc_add->voltage_id = $request->voltage;
            $pc_add->save();
        }

        if ($request->id == 0) {
            LogSystem("Colaborador criou um novo produto", $request->session()->get('r_code'));
            $request->session()->put('success', "Novo produto criada com sucesso!");

        } else {
            LogSystem("Colaborador atualizou o balanço de produto", $request->session()->get('r_code'));
            $request->session()->put('success', "Produto atualizado com sucesso!");
        }

        return Redirect('/engineering/product/all');

    }

    public function idlEngEditProduct_status(Request $request) {

        $product = ProductAir::find($request->id);
        if ($product) {

            $product->is_active = $request->status;
            $product->save();

            if ($request->status == 0) {
                LogSystem("Colaborador desativou o produto: ". $product->model, $request->session()->get('r_code'));
                $request->session()->put('success', "Produto foi desativado com sucesso!");

            } else {
                LogSystem("Colaborador reativou o produto: ". $product->model, $request->session()->get('r_code'));
                $request->session()->put('success', "Produto reativado com sucesso!");
            }

            return Redirect('/engineering/product/all');

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }


    public function idlEngEditPart(Request $request, $id) {

        $product_category = ProductCategory::where('is_active', 1)->get();

        if ($id == 0) {

            $product = "";
            $code = "";
            $description = "";
            $ncm = "";
            $cest = "";
            $amount = "";
            $warehouse = "";
            $observation = "";
            $product_category_id = 0;

        } else {

            $part = Parts::find($id);

            if ($part) {

                $product = DB::table('product_parts')
                    ->leftJoin('product_control', 'product_parts.product_control_id', '=', 'product_control.id')
                    ->leftJoin('product_air', 'product_control.product_id', '=', 'product_air.id')
                    ->leftJoin('voltages', 'product_control.voltage_id', '=', 'voltages.id')
                    ->select('product_control.*', 'voltages.name as voltages_name', 'product_air.model')
                    ->where('product_parts.part_id', $id)
                    ->get();

                $code = $part->code;
                $description = $part->description;
                $ncm = $part->ncm;
                $cest = $part->cest;
                $amount = $part->amount;
                $warehouse = $part->warehouse;
                $observation = $part->observation;
                $product_category_id = $part->product_category_id;

            } else {

                App::setLocale($request->session()->get('lang'));
                $request->session()->put('error', __('layout_i.not_permissions'));
                return Redirect('/news');
            }

        }

        return view('gree_i.engineering.part_edit', [
            'id' => $id,
            'product' => $product,
            'code' => $code,
            'description' => $description,
            'ncm' => $ncm,
            'cest' => $cest,
            'amount' => $amount,
            'warehouse' => $warehouse,
            'observation' => $observation,
            'product_category_id' => $product_category_id,
            'product_category' => $product_category,

        ]);
    }

    public function idlEngEditPart_do(Request $request) {

        if ($request->id == 0) {
            $part = new Parts;
        } else {
            $part = Parts::find($request->id);
        }

        $part->code = $request->code;
        $part->description = $request->description;
        $part->ncm = $request->ncm;
        $part->cest = $request->cest;
        $part->amount = $request->amount;
        $part->warehouse = $request->warehouse;
        $part->observation = $request->observation;
        $part->product_category_id = $request->product_category;
        $part->save();

        $i = 0;
        if ($request->product) {
            ProductParts::where('part_id', $part->id)->delete();
            foreach ($request->product as $key) {
                $pc_add = new ProductParts;
                $pc_add->part_id = $part->id;
                $pc_add->product_control_id = $request->product[$i];
                $pc_add->save();

                $i++;
            }
        }

        if ($request->id == 0) {
            LogSystem("Colaborador criou uma nova peça", $request->session()->get('r_code'));
            $request->session()->put('success', "Nova peça criada com sucesso!");

        } else {
            LogSystem("Colaborador atualizou o balanço de peças", $request->session()->get('r_code'));
            $request->session()->put('success', "Peça atualizado com sucesso!");
        }

        return Redirect('/engineering/part/all');

    }

    public function idlEngAllPart(Request $request) {

        // SAVE FILTERS
        if (!empty($request->input('product_category'))) {
            $request->session()->put('pf_product_category', $request->input('product_category'));
        } else {
            $request->session()->forget('pf_product_category');
        }
        if (!empty($request->input('product_sub_level_1'))) {
            $request->session()->put('pf_product_sub_level_1', $request->input('product_sub_level_1'));
        } else {
            $request->session()->forget('pf_product_sub_level_1');
        }
        if (!empty($request->input('product_sub_level_2'))) {
            $request->session()->put('pf_product_sub_level_2', $request->input('product_sub_level_2'));
        } else {
            $request->session()->forget('pf_product_sub_level_2');
        }
        if (!empty($request->input('product_sub_level_3'))) {
            $request->session()->put('pf_product_sub_level_3', $request->input('product_sub_level_3'));
        } else {
            $request->session()->forget('pf_product_sub_level_3');
        }

        if (!empty($request->input('code'))) {
            $request->session()->put('pf_code', $request->input('code'));
        } else {
            $request->session()->forget('pf_code');
        }
        if (!empty($request->input('description'))) {
            $request->session()->put('pf_description', $request->input('description'));
        } else {
            $request->session()->forget('pf_description');
        }
        if (!empty($request->input('ncm'))) {
            $request->session()->put('pf_ncm', $request->input('pf_ncm'));
        } else {
            $request->session()->forget('pf_ncm');
        }
        if (!empty($request->input('cest'))) {
            $request->session()->put('pf_cest', $request->input('cest'));
        } else {
            $request->session()->forget('pf_cest');
        }
        if (!empty($request->input('warehouse'))) {
            $request->session()->put('pf_warehouse', $request->input('warehouse'));
        } else {
            $request->session()->forget('pf_warehouse');
        }
        if (!empty($request->input('model'))) {
            $request->session()->put('pf_model', $request->input('model'));
        } else {
            $request->session()->forget('pf_model');
        }

        $parts = Parts::with('ProductControl.ProductAir')->orderBy('parts.id', 'DESC');

        $product_category = ProductCategory::where('is_active', 1)->get();
        $product_category_id = "";
        $product_sub_level_1_id = "";
        $product_sub_level_2_id = "";
        $product_sub_level_3_id = "";

        if (!empty($request->session()->get('pf_code'))) {
            $parts->where('code', 'like', '%'. $request->session()->get('pf_code') .'%')
                ->orWhere(function ($query) use ($request) {
                    $query->where('observation', 'like', '%'. $request->session()->get('pf_code') .'%');
                });
        }
        if (!empty($request->session()->get('pf_ncm'))) {
            $parts->where('ncm', 'like', '%'. $request->session()->get('pf_ncm') .'%');
        }
        if (!empty($request->session()->get('pf_cest'))) {
            $parts->where('cest', 'like', '%'. $request->session()->get('pf_cest') .'%');
        }
        if (!empty($request->session()->get('pf_cest'))) {
            $parts->where('cest', 'like', '%'. $request->session()->get('pf_cest') .'%');
        }
        if (!empty($request->session()->get('pf_cest'))) {
            $parts->where('warehouse', 'like', '%'. $request->session()->get('pf_cest') .'%');
        }
        if (!empty($request->session()->get('pf_model'))) {
            $parts->ProductControlFilter(1, $request->session()->get('pf_model'));
        }
        if (!empty($request->session()->get('pf_description'))) {
            $parts->where('description', 'like', '%'. $request->session()->get('pf_description') .'%');
        }
        if (!empty($request->session()->get('pf_product_category'))) {
            $product_category_id = $request->session()->get('pf_product_category');
            $parts->where('product_category_id', $request->session()->get('pf_product_category'));
        }
        if (!empty($request->session()->get('pf_product_sub_level_1'))) {
            $product_sub_level_1_id = ProductSubLevel1::find($request->session()->get('pf_product_sub_level_1'));
            $parts->ProductControlFilter(2, $request->session()->get('pf_product_sub_level_1'));
        }
        if (!empty($request->session()->get('pf_product_sub_level_2'))) {
            $product_sub_level_2_id = ProductSubLevel2::find($request->session()->get('pf_product_sub_level_2'));
            $parts->ProductControlFilter(3, $request->session()->get('pf_product_sub_level_2'));
        }
        if (!empty($request->session()->get('pf_product_sub_level_3'))) {
            $product_sub_level_3_id = ProductSubLevel3::find($request->session()->get('pf_product_sub_level_3'));
            $parts->ProductControlFilter(4, $request->session()->get('pf_product_sub_level_3'));
        }

        return view('gree_i.engineering.part_list', [
            'parts' => $parts->paginate(10),
            'product_category' => $product_category,
            'product_category_id' => $product_category_id,
            'product_sub_level_1_id' => $product_sub_level_1_id,
            'product_sub_level_2_id' => $product_sub_level_2_id,
            'product_sub_level_3_id' => $product_sub_level_3_id,
        ]);
    }

    public function idlEngEditPart_status(Request $request) {

        $part = Parts::find($request->id);
        if ($part) {

            $part->is_active = $request->status;
            $part->save();

            if ($request->status == 0) {
                LogSystem("Colaborador desativou a peça: ". $part->code, $request->session()->get('r_code'));
                //Parts::where('id', $request->id)->delete();
                $request->session()->put('success', "Peça foi desativado com sucesso!");

            } else {
                LogSystem("Colaborador reativou o peça: ". $part->code, $request->session()->get('r_code'));
                $request->session()->put('success', "Peça reativado com sucesso!");
            }

            return Redirect('/engineering/part/all');

        } else {

            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/news');
        }

    }

    public function idlEngImportPart() {

        $unity = ProductAirUnity::all();
        $voltage = Voltages::all();
        $product_category = ProductCategory::where('is_active', 1)->get();

        $product_sub_level_1_id = 0;
        $product_sub_level_2_id = 0;
        $product_sub_level_3_id = 0;

        return view('gree_i.engineering.part_import', [
            'product_category' => $product_category,
            'unity' => $unity,
            'voltage' => $voltage,
            'product_sub_level_1_id' => $product_sub_level_1_id,
            'product_sub_level_2_id' => $product_sub_level_2_id,
            'product_sub_level_3_id' => $product_sub_level_3_id,
        ]);
    }

    public function idlEngImportPart_do(Request $request) {

        set_time_limit(360);
        if ($request->hasFile('attach')) {
            $extension = $request->attach->extension();

            $validator = Validator::make(
                [
                    'file'      => $request->attach,
                    'extension' => strtolower($request->attach->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required|max:1024',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]
            );

            if ($validator->fails()) {

                $request->session()->put('error', "Tamanho do arquivo nÃ£o pode exceder 1MB!");
                return Redirect('/engineering/part/import');
            } else {

                try {
                    Excel::import(new PartsImport($request), $request->file('attach'));

                    LogSystem("Colaborador importou peças", $request->session()->get('r_code'));
                    $request->session()->put('success', "Peças importadas com sucesso!");
                }
                catch (ValidationException $e) {
                    $request->session()->put('error', $e->failures());
                }
                return Redirect('/engineering/part/import');
            }
        }
    }

    public function idlEngAllType(Request $request) {

        if (!empty($request->input('type_line'))) {
            $request->session()->put('sacf_type_line', $request->input('type_line'));
        } else {
            $request->session()->forget('sacf_type_line');
            if($request->session()->get('filter_line') == 2){
                $request->session()->put('sacf_type_line', 2);
            }
        }

        $list = SacType::orderBy('id', 'DESC');

        if (!empty($request->session()->get('sacf_type_line'))) {

            if($request->session()->get('sacf_type_line') == 1) {
                $list->where('type_line', 1);
            }
            else {
                $list->where('type_line', 2);
            }
        }

        return view('gree_i.engineering.type', [
            'list' => $list->paginate(10),
        ]);
    }

    public function idlEngEditType(Request $request) {

        $id = $request->Input('id');
        $type = $request->Input('type');
        $type_line = $request->Input('type_line');

        $list = SacType::find($id);

        if ($list) {

            $list->name = $type;
            $list->type_line = $type_line;
            $list->save();

            LogSystem("Tipo atualizado: ". $list->name, $request->session()->get('r_code'));
        } else {

            if (SacType::where('name', $type)->first()) {

                $request->session()->put('error', "Tipo já existe na lista.");
                return Redirect('/engineering/type');
            } else {
                $list = new SacType;
                $list->name = $type;
                $list->type_line = $type_line;
                $list->save();
            }

            LogSystem("Novo tipo criado: ". $list->name, $request->session()->get('r_code'));
        }

        $request->session()->put('success', "Lista Tipo atualizada com sucesso!");
        return Redirect('/engineering/type');
    }

    public function idlEngDeleteType(Request $request, $id) {
        $list = SacType::find($id);

        if ($list) {

            SacType::where('id', $id)->delete();
            LogSystem("Colaborador fez a exclusão do Tipo: ".$list->name, $request->session()->get('r_code'));
            $request->session()->put('success', "Você realizou a exclusão do tipo com sucesso!");
            return Redirect('/engineering/type');

        } else {
            App::setLocale($request->session()->get('lang'));
            $request->session()->put('error', __('layout_i.not_permissions'));
            return Redirect('/engineering/type');
        }
    }

    public function importCsv() {

        return view('gree_i.misc.import_csv');
    }

    public function admGenericRequest(Request $request) {

        $requests = AdmRequests::with('AdmRequestFiles')->where('r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'description',
            'status',
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code"){

                    $requests->where('code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."description"){

                    $requests->where('description', 'LIKE', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1) {

                        $requests->where('is_cancelled', 0)->where('is_approv', 0)->where('is_reprov', 0);
                    } else if ($valor_filtro == 2) {

                        $requests->where('is_approv', 1);
                    } else if ($valor_filtro == 3) {

                        $requests->where('is_reprov', 1);
                    } else if ($valor_filtro == 4) {

                        $requests->where('is_cancelled', 1);
                    }
                }

            }
        }

        return view('gree_i.administration.request_list', [
            'requests' => $requests->paginate(10)
        ]);
    }

    public function admGenericRequestObserver(Request $request) {

        $requests = AdmRequestObservers::with('AdmRequestFiles', 'AdmRequests')->where('r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'status',
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code"){

                    $requests->AdmRequestFilter(['code' => $valor_filtro]);
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1) {

                        $requests->AdmRequestFilter(['is_cancelled' => 1, 'is_approv' => 0, 'is_reprov' => 0]);
                    } else if ($valor_filtro == 2) {

                        $requests->AdmRequestFilter(['is_approv' => 1]);
                    } else if ($valor_filtro == 3) {

                        $requests->AdmRequestFilter(['is_reprov' => 1]);
                    } else if ($valor_filtro == 4) {

                        $requests->AdmRequestFilter(['is_cancelled' => 1]);
                    }
                }

            }
        }

        return view('gree_i.administration.request_observer_list', [
            'requests' => $requests->paginate(10)
        ]);

    }

    public function admGenericRequestApprov(Request $request) {

        $requests = AdmRequestAnalyze::with('AdmRequestFiles', 'AdmRequests')->where('r_code', $request->session()->get('r_code'))->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'status',
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code"){

                    $requests->AdmRequestFilter(['code' => $valor_filtro]);
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1) {

                        $requests->AdmRequestFilter(['is_cancelled' => 0, 'is_approv' => 0, 'is_reprov' => 0]);
                    } else if ($valor_filtro == 2) {

                        $requests->AdmRequestFilter(['is_approv' => 1]);
                    } else if ($valor_filtro == 3) {

                        $requests->AdmRequestFilter(['is_reprov' => 1]);
                    } else if ($valor_filtro == 4) {

                        $requests->AdmRequestFilter(['is_cancelled' => 1]);
                    }
                }

            }
        }

        return view('gree_i.administration.request_approv_list', [
            'requests' => $requests->paginate(10)
        ]);

    }

    public function admGenericRequestView(Request $request) {

        $AdmRequests = '';
        $b64Doc = '';
        $isAnalyze = 0;

        if ($request->s) {

            $AdmRequests = AdmRequests::with('AdmRequestFiles', 'AdmRequestAnalyze.Users', 'AdmRequestObservers.Users')->where('hash_code', $request->s)->first();

            if ($AdmRequests) {

                $request->session()->put('admRequestID', $AdmRequests->id);
                $b64Doc = $AdmRequests->AdmRequestFiles->base64;

                $isAnalyze = $AdmRequests->AdmRequestAnalyze->where('r_code', $request->session()->get('r_code'))->first();

                // if (!$AdmRequests->where('r_code', $request->session()->get('r_code'))->first()) {
                //     if (!$AdmRequests->AdmRequestAnalyze->where('r_code', $request->session()->get('r_code'))->first()) {
                //         if (!$AdmRequests->AdmRequestObservers->where('r_code', $request->session()->get('r_code'))->first()) {

                //             return redirect('/news')->with('error', 'Você não tem autorização para acessar essa página!');
                //         }
                //     }
                // }
            } else {

                return redirect('/news')->with('error', 'Não foi possível encontrar a solicitação no sistema.');
            }
        } else {
            $request->session()->put('admRequestID', 0);
        }

        return view('gree_i.administration.request_view', [
            'id' => $request->session()->get('admRequestID'),
            'isAnalyze' => $isAnalyze,
            'AdmRequests' => $AdmRequests,
            'b64Doc' => $b64Doc,
        ]);
    }

    public function admGenericRequestCancel(Request $request) {

        if ($request->s) {

            $AdmRequests = AdmRequests::where('hash_code', $request->s)->where('r_code', $request->session()->get('r_code'))->first();

            if ($AdmRequests) {
                if ($AdmRequests->is_cancelled == 0) {

                    $AdmRequests->is_cancelled = 1;
                    $AdmRequests->save();

                    return redirect()->back()->with('success', 'Foi realizado o cancelamento da solicitação com sucesso!');
                } else {

                    return redirect()->back()->with('error', 'A solicitação já foi cancelada no sistema.');
                }
            } else {

                return redirect()->back()->with('error', 'Não foi possível encontrar a solicitação no sistema.');
            }
        } else {
            return redirect()->back()->with('error', 'Ocorreu um erro inesperado!');
        }
    }

    public function admGenericRequestView_do(Request $request) {
        set_time_limit(600);		
		
        DB::beginTransaction();
        // Save request
        $AdmRequests = new AdmRequests;
        $AdmRequests->r_code = $request->session()->get('r_code');
        $AdmRequests->description = $request->description;
        $AdmRequests->code = getCodeModule('adm_request_files', $request->session()->get('r_code'));

        do {
            $unique_code = generateRandomNumber();
        } while (AdmRequests::where('hash_code', $unique_code)->count() > 0);

        $AdmRequests->hash_code = date('my').'-'.strtoupper($unique_code);
        $AdmRequests->save();

        // Save Observers
        $payload = json_decode($request->observers);
        foreach ($payload as $key) {

            $AdmRequestObservers = new AdmRequestObservers;
            $AdmRequestObservers->adm_requests_id = $AdmRequests->id;
            $AdmRequestObservers->r_code = $key->r_code;
            $AdmRequestObservers->save();
        }

        // Save Approvers
        $payload = json_decode($request->approvers);
        foreach ($payload as $key) {

            $AdmRequestAnalyze = new AdmRequestAnalyze;
            $AdmRequestAnalyze->adm_requests_id = $AdmRequests->id;
            $AdmRequestAnalyze->r_code = $key->r_code;
            $AdmRequestAnalyze->save();
        }

        try {
            // Save Files
            $AdmRequestFiles = new AdmRequestFiles;
            $AdmRequestFiles->adm_requests_id = $AdmRequests->id;
            $AdmRequestFiles->url = $request->url;
            $AdmRequestFiles->save();

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'O arquivo está gerando um binário muito grande, tente enviar um arquivo por vez!');
        }

        DB::commit();

        $analyze = AdmRequestAnalyze::with('Users')->where('adm_requests_id', $AdmRequests->id)->first();
        $user = $analyze->Users;
        $pattern = array(
            'title' => 'DOCUMENTO PARA APROVAR',
            'description' => nl2br("<div style='text-align: left;'><p><b>Documento para aprovar</b></p></div><div style='text-align: left;'><p><b>Solicitante:</b> ". getENameF($AdmRequests->r_code) ."<br><b>Observação:</b> ". $AdmRequests->description ."</p></div><div style='text-align: center;'><p><b>Link para aprovação: </b><a href='". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."'>". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."</a></p></div>"),
            'template' => 'misc.Default',
            'subject' => 'Documento para aprovar: '. $AdmRequests->code,
        );

        NotifyUser('Documento para realizar aprovação #'. $AdmRequests->code, $user->r_code, 'fa-exclamation', 'text-info', 'Necessitam da sua aprovação para um documento, clique aqui para visualizar.', $request->root() .'/administration/generic/request/view?s='. $AdmRequests->hash_code);
        SendMailJob::dispatch($pattern, $user->email);

        return redirect('/administration/generic/request/list')->with('success', 'Solicitação foi gerada com sucesso!');

    }


    public function admGenericRequestBase64(Request $request) {
        $AdmRequestFiles = AdmRequestFiles::where('adm_requests_id', $request->id)->first();

        if ($AdmRequestFiles) {

            $base64 = base64_encode(file_get_contents($AdmRequestFiles->url));

            return response()->json([
                "success" => true,
                "base64" => $base64,
            ], 200);

        } else {

            return response()->json([
                "success" => false,
                "msg" => 'Não foi possível renderizar o arquivo, contacte a equipe de TI.',
            ], 200);
        }

    }

    public function admGenericRequestAnalyze(Request $request) {

        $AdmRequests = AdmRequests::with('AdmRequestFiles', 'AdmRequestAnalyze.Users', 'AdmRequestObservers.Users')->where('id', $request->id)->first();

        if ($AdmRequests) {

            if ($AdmRequests->is_cancelled == 1) {

                return redirect()->back()->with('error', 'Essa solicitação foi cancelada!');
            }
            // Save Observers
            $payload = json_decode($request->observers);
            if (count($payload) > 0) {

                AdmRequestObservers::where('adm_requests_id', $AdmRequests->id)->delete();
                foreach ($payload as $key) {

                    $AdmRequestObservers = new AdmRequestObservers;
                    $AdmRequestObservers->adm_requests_id = $AdmRequests->id;
                    $AdmRequestObservers->r_code = $key->r_code;
                    $AdmRequestObservers->save();
                }
            }

            $analyze = $AdmRequests->AdmRequestAnalyze->where('r_code', $request->session()->get('r_code'))->first();
            $order = $AdmRequests->AdmRequestAnalyze->where('is_approv', 0)->where('is_reprov', 0)->first();

            if ($analyze) {

                if ($analyze->is_approv == 1 or $analyze->is_reprov == 1) {
                    return redirect('/news')->with('error', 'Essa solicitação já foi análisada por você.');
                } else if ($order->Users->r_code != $request->session()->get('r_code')) {
                    return redirect()->back()->with('error', 'Você ainda não pode aprovar essa solicitação!');
                } else {

                    $analyze->is_approv = $request->analyze == 1 ? 1 : 0;
                    $analyze->is_reprov = $request->analyze == 2 ? 1 : 0;
                    $analyze->description = $request->obs;
                    $analyze->save();


                    $t_analyze = $request->analyze == 1 ? $AdmRequests->AdmRequestAnalyze->where('is_approv', 1)->count() : $AdmRequests->AdmRequestAnalyze->where('is_approv', 1)->count();

                    if ($AdmRequests->AdmRequestAnalyze->count() == $t_analyze or $request->analyze == 2) {

                        $AdmRequests->is_approv = $request->analyze == 2 ? 0 : 1;
                        $AdmRequests->is_reprov = $request->analyze == 2 ? 1 : 0;
                        $AdmRequests->save();

                        $pdf = new Fpdi;

                        $file = file_get_contents($AdmRequests->AdmRequestFiles->url);
                        $pageCount = $pdf->setSourceFile(StreamReader::createByString($file));

                        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                            // import a page
                            $templateId = $pdf->importPage($pageNo);
                            
							// add a page with the same orientation and size
							$pdf->AddPage('P', [210, 297]);

							// use the imported page and place it at point 10,10 with a width of 200 mm
							$pdf->useTemplate($templateId, 0, 0, 200 );

                            //if ($pageNo == 1) {

                            $pdf->SetFont('helvetica','B',8);
                            if ($AdmRequests->AdmRequestAnalyze->count() == $t_analyze) {
                                $pdf->Image($request->root() .'/gdb_request_approv.png',120,225,42);
                                $pdf->SetTextColor(96,131,53);
                            } else {
                                $pdf->Image($request->root() .'/gdb_request_reprov.png',120,225,42);
                                $pdf->SetTextColor(177,27,39);
                            }

                            $pdf->SetXY(129,255);
                            $pdf->Write(0,$AdmRequests->hash_code);

                            //}

                            $add = 30;
                            foreach ($AdmRequests->AdmRequestAnalyze as $index => $key) {

                                $pos1 = 10;
                                $pos2 = 20;

                                if ($index != 0) {
                                    $pos1 = $pos1 + $add;
                                    $pos2 = $pos2 + $add;
                                    $add = $add + 30;
                                }

                                if ($key->is_approv == 1)
                                    $pdf->Image($request->root() .'/gdb_request_mold_approv.png',$pos1,227,30);
                                else
                                    $pdf->Image($request->root() .'/gdb_request_mold_reprov.png',$pos1,227,30);

                                $pdf->SetFont('helvetica','B',6);
                                $pdf->SetTextColor(255,255,255);
                                $pdf->SetXY($pos1,229);
                                $pdf->Write(0,$key->Users->office);

                                $pdf->SetFont('helvetica','B',6);
                                $pdf->SetTextColor(0,0,0);
                                $pdf->SetXY($pos1,232);
                                $pdf->Write(0,$key->Users->first_name.' '.$key->Users->last_name);

                                $pdf->SetFont('helvetica','',6);
                                $pdf->SetTextColor(0,0,0);
                                $pdf->SetXY($pos1,235);
                                $pdf->Write(0,'Matricula:');

                                $pdf->SetFont('helvetica','B',6);
                                $pdf->SetTextColor(0,0,0);
                                $pdf->SetXY($pos2,235);
                                $pdf->Write(0,$key->Users->r_code);

                                $pdf->SetFont('helvetica','',6);
                                $pdf->SetTextColor(0,0,0);
                                $pdf->SetXY($pos1,238);
                                $pdf->Write(0,'Feito em:');

                                $pdf->SetFont('helvetica','B',6);
                                $pdf->SetTextColor(0,0,0);
                                $pdf->SetXY($pos2,238);
                                $pdf->Write(0,date('d/m/Y H:i', strtotime($key->updated_at)));
                            }

                        }

                        $filename = date('YmdHis').".pdf";
						$rm_link = str_replace("https://s3.amazonaws.com/gree-app.com.br/srvfilemanager/","", $AdmRequests->AdmRequestFiles->url);
        				Storage::disk('s3')->delete($rm_link);

                        $output = $pdf->Output($filename, 'S');
                        Storage::disk('s3')->put('/srvfilemanager/'.$filename, $output);

                        $AdmRequests->AdmRequestFiles->url = Storage::disk('s3')->url('srvfilemanager/'.$filename);
                        $AdmRequests->AdmRequestFiles->save();

                    }

                    if ($AdmRequests->AdmRequestAnalyze->count() == $t_analyze) {
                        // APROVADO

                        $pattern = array(
                            'title' => 'DOCUMENTO APROVADO',
                            'description' => nl2br("<div style='text-align: left;'><p><b>Documento análisado</b></p></div><div style='text-align: left;'><p><b>Solicitante:</b> ". getENameF($AdmRequests->r_code) ."<br><b>Observação:</b> ". $AdmRequests->description ."</p></div><div style='text-align: center;'><p><b>Link para visualizar: </b><a href='". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."'>". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."</a></p></div>"),
                            'template' => 'misc.Default',
                            'subject' => 'Atualização do Documento: '. $AdmRequests->code,
                        );

                        NotifyUser('Atualização do Documento #'. $AdmRequests->code, $AdmRequests->Users->r_code, 'fa-check', 'text-info', 'Seu documento foi aprovado com sucesso, clique aqui para visualizar.', $request->root() .'/administration/generic/request/view?s='. $AdmRequests->hash_code);
                        SendMailJob::dispatch($pattern, $AdmRequests->Users->email);

                    } else if ($request->analyze == 2) {
                        // REPROVADO

                        $pattern = array(
                            'title' => 'DOCUMENTO REPROVADO',
                            'description' => nl2br("<div style='text-align: left;'><p><b>Documento análisado</b></p></div><div style='text-align: left;'><p><b>Solicitante:</b> ". getENameF($AdmRequests->r_code) ."<br><b>Observação:</b> ". $AdmRequests->description ."</p></div><div style='text-align: center;'><p><b>Link para visualizar: </b><a href='". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."'>". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."</a></p></div>"),
                            'template' => 'misc.Default',
                            'subject' => 'Atualização do Documento: '. $AdmRequests->code,
                        );

                        NotifyUser('Atualização do Documento #'. $AdmRequests->code, $AdmRequests->Users->r_code, 'fa-times', 'text-info', 'Seu documento infelizmente foi reprovado, clique aqui para visualizar.', $request->root() .'/administration/generic/request/view?s='. $AdmRequests->hash_code);
                        SendMailJob::dispatch($pattern, $AdmRequests->Users->email);

                    } else {
                        // ANÁLISADO, CONTINUA
                        $user = $AdmRequests->AdmRequestAnalyze->where('is_approv', 0)->where('is_reprov', 0)->first()->Users;
                        $pattern = array(
                            'title' => 'DOCUMENTO PARA APROVAR',
                            'description' => nl2br("<div style='text-align: left;'><p><b>Documento para aprovar</b></p></div><div style='text-align: left;'><p><b>Solicitante:</b> ". getENameF($AdmRequests->r_code) ."<br><b>Observação:</b> ". $AdmRequests->description ."</p></div><div style='text-align: center;'><p><b>Link para aprovação: </b><a href='". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."'>". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."</a></p></div>"),
                            'template' => 'misc.Default',
                            'subject' => 'APROVAÇÃO DE DOCUMENTO: '. $AdmRequests->code,
                        );

                        NotifyUser('Documento para realizar aprovação #'. $AdmRequests->code, $user->r_code, 'fa-exclamation', 'text-info', 'Necessitam da sua aprovação para um documento, clique aqui para visualizar.', $request->root() .'/administration/generic/request/view?s='. $AdmRequests->hash_code);
                        SendMailJob::dispatch($pattern, $user->email);
                    }

                    foreach ($AdmRequests->AdmRequestObservers as $key) {

                        $user = $key->Users;
                        $pattern = array(
                            'title' => 'ATUALIZAÇÃO NO DOCUMENTO',
                            'description' => nl2br("<div style='text-align: left;'><p><b>Você está marcado como observador dessa análise, por isso está recebendo esse email.</b></p></div><div style='text-align: left;'><p><b>Solicitante:</b> ". getENameF($AdmRequests->r_code) ."<br><b>Observação:</b> ". $AdmRequests->description ."</p></div><div style='text-align: center;'><p><b>Link para aprovação: </b><a href='". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."'>". $request->root() ."/administration/generic/request/view?s=". $AdmRequests->hash_code ."</a></p></div>"),
                            'template' => 'misc.Default',
                            'subject' => 'Atualização do Documento: '. $AdmRequests->code,
                        );

                        NotifyUser('Atualização do documento #'. $AdmRequests->code, $user->r_code, 'fa-exclamation', 'text-info', 'Você está marcado como observador dessa análise, clique aqui para visualizar.', $request->root() .'/administration/generic/request/view?s='. $AdmRequests->hash_code);
                        SendMailJob::dispatch($pattern, $user->email);

                    }

                    return redirect()->back()->with('success', 'Você realizou análise do documento com sucesso!');
                }
            } else {

                return redirect('/news')->with('error', 'Você não pode análisar essa solicitação.');
            }

        } else {

            return redirect('/news')->with('error', 'Não foi possível encontrar a solicitação no sistema.');
        }


    }


}
