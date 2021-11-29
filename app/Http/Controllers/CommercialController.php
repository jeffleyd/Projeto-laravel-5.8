<?php

namespace App\Http\Controllers;

use App\Exports\DefaultHtmlExport;
use App\Helpers\Commercial\AnalyzeProcessClient;
use App\Helpers\Commercial\AnalyzeProcessOrder;
use App\Helpers\Commercial\ManagerClient;
use App\Helpers\Commercial\OrderInvoiceEmail;
use App\Helpers\Commercial\Orders\OrdersRefund;
use App\Helpers\Commercial\Orders\OrdersInvoice;
use App\Http\Controllers\Controller;
use App\Events\EventSocket;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Jobs\SendMailAttachJob;
use GuzzleHttp\Client as GuzzleClient;

use App\Model\Commercial\Client;
use App\Model\Commercial\ClientDocuments;
use App\Model\Commercial\ClientGroup;
use App\Model\Commercial\ClientVersion;
use App\Model\Commercial\OrderAvaibleMonth;
use App\Model\Commercial\OrderDelivery;
use App\Model\Commercial\OrderProducts;
use App\Model\Commercial\OrderReceiver;
use App\Model\Commercial\OrderSales;
use App\Model\Commercial\OrderSalesAttach;
use App\Model\Commercial\OrderInvoiceProducts;
use App\Model\Commercial\OrderInvoice;
use App\Model\Commercial\OrderInvoiceRefund;
use App\Model\Commercial\OrderInvoiceErrors;
use App\Model\Commercial\OrderInvoiceRefundProducts;

use App\Model\Commercial\Programation;
use App\Model\Commercial\ProgramationMacro;
use App\Model\Commercial\ProgramationMonth;
use App\Model\Commercial\SalesmanOnState;
use App\Model\Commercial\SetProductPriceFixed;
use App\Model\Commercial\SaleVerificationErrors;
use App\Model\Commercial\SaleVerificationClientCompleted;
use App\Model\Commercial\SaleVerificationClient;

use App\Model\UserOnPermissions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Hash;
use App;
use Log;

use \App\Model\Users;
use App\Model\PromoterUsers;
use App\Model\PromoterUserHistory;
use App\Model\PromoterRoute;
use App\Model\PromoterRouteHistory;
use App\Model\PromoterRouteHistoryImg;
use App\Model\PromoterRequestItem;
use App\Model\PromoterRequestItens;

use App\Model\Commercial\SetProductOnGroup;
use App\Model\Commercial\SetProductGroup;
use App\Model\Commercial\Settings;
use App\Model\Commercial\SetProduct;
use App\Model\Commercial\SetProductAdjust;
use App\Model\Commercial\SetProductSave;
use App\Model\Commercial\Salesman;
use App\Model\Commercial\SalesmanImmediate;
use App\Model\Commercial\SalesmanTablePrice;
use App\Model\Commercial\SalesmanTablePriceTemplate;
use App\Model\Commercial\OrderFieldTablePrice;
use App\Model\Commercial\OrderTablePriceRules;
use App\Imports\ClientsImport;

use Carbon\Carbon;
use \App\Http\Controllers\Services\FileManipulationTrait;
use \App\Http\Controllers\Services\CommercialTrait;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DefaultExport;
use \App\Helpers\Commercial\ApplyConditionPriceBase;
use App\Exports\ClientsExport;
use NFePHP\NFe\Common\Standardize;	

class CommercialController extends Controller
{

    use FileManipulationTrait;
    use CommercialTrait;

    public function admPromoterUserEdit_do(Request $request) {

        if ($request->id == 0) {

            $user = new PromoterUsers;
        } else {
            $user = PromoterUsers::find($request->id);

            if (!$user) {

                return response()->json([
                    'message' => 'Usuário não foi encontrado, atualize a página.'
                ], 400);
            }
        }

        //genIconUpS3
        if ($request->hasFile('picture')) {
            $response = $this->uploadS3(1, $request->picture, $request, 200, true, 'png');
            if ($response['success']) {
                $user->picture = $response['url'];
            } else {
                return response()->json([
                    'message' => 'Não foi possível fazer upload da imagem'
                ], 400);
            }
            $response = $this->roundedCornersAndUploadS3(2, '', $user->picture, $request);
            if ($response['success']) {
                $user->icon = $response['url'];
            } else {
                $this->removeS3();
                return response()->json([
                    'message' => 'Não foi possível fazer upload do icone'
                ], 400);
            }
        }
        $user->name = $request->name;
        $user->phone_1 = $request->phone_1;
        $user->phone_2 = $request->phone_2;
        $user->email = $request->email;
        $user->identity = $request->identity;
        $user->is_active = $request->is_active;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->id == 0) {

            LogSystem("Colaborador criou um novo usuário para o promotor. ID ". $user->id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Usuário criado com sucesso!');
            return redirect()->back();
        } else {

            LogSystem("Colaborador atualizou o usuário para o promotor. ID ". $user->id, $request->session()->get('r_code'));
            return response()->json([
                'user' => $user,
            ], 200);
        }

    }

    public function admPromoterUserAll(Request $request) {

        $users = PromoterUsers::with('routes')->orderBy('id', 'DESC');
        $userall = PromoterUsers::all();

        $array_input = collect([
            'id_user',
        ]);

        // SAVE FILTERS
        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."id_user"){

                    $users->where('id', $valor_filtro);
                }
            }
        }

        return view('gree_i.commercial.promoter.user_all', [
            'users' => $users->paginate(10),
            'userall' => $userall
        ]);
    }

    public function admPromoterRequestItemEdit_do(Request $request) {

        if ($request->id == 0) {

            $item = new PromoterRequestItem;
        } else {
            $item = PromoterRequestItem::leftjoin('promoter_users', 'promoter_request_item.promoter_user_id', '=', 'promoter_users.id')
            ->select('promoter_users.name as promoter_users_name', 'promoter_users.id as promoter_users_id', 'promoter_request_item.*')
            ->where('promoter_request_item.id', $request->id)->first();

            if (!$item) {

                return response()->json([
                    'message' => 'O item não foi encontrado, atualize a página.'
                ], 400);
            }
        }

        $item->name = $request->name;
        $item->quantity = $request->quantity;
        $item->promoter_user_id = $request->user;
        if ($request->editstatus == 1) {
            $item->is_cancelled = 1;
        } else if ($request->editstatus == 2) {
            $item->is_cancelled = 0;
            $item->has_accept = 0;
            $item->has_receiver = 0;
            $item->has_send = 0;
            $item->receiver_date = null;
        } else if ($request->editstatus == 3) {
            $item->is_cancelled = 0;
            $item->has_accept = 1;
            $item->has_receiver = 0;
            $item->has_send = 0;
            $item->receiver_date = null;
        } else if ($request->editstatus == 4) {
            $item->is_cancelled = 0;
            $item->has_accept = 1;
            $item->has_receiver = 0;
            $item->has_send = 1;
        } else if ($request->editstatus == 5) {
            $item->is_cancelled = 0;
            $item->has_accept = 1;
            $item->has_send = 1;
            $item->has_receiver = 1;
        }

        if ($request->send_date)
        $item->send_date = $request->send_date;

        if ($request->receiver_date)
        $item->receiver_date = $request->receiver_date;

        $item->save();

        if ($request->id == 0) {

            LogSystem("Colaborador criou uma nova solicitação de item para promotor. ID ". $item->id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Item criado com sucesso!');
            return redirect()->back();
        } else {

            $item->date_created = date('d-m-Y', strtotime($item->created_at));
            $item->date_receiver = date('d-m-Y', strtotime($item->receiver_date));
            $item->date_send = date('d-m-Y', strtotime($item->send_date));

            LogSystem("Colaborador atualizou a solicitação de item para promotor. ID ". $item->id, $request->session()->get('r_code'));
            return response()->json([
                'item' => $item,
            ], 200);
        }

    }

    public function admPromoterRequestItemAll(Request $request) {

        $item = PromoterRequestItem::with('user')->OrderBy('id', 'DESC');
        $userall = PromoterUsers::all();

        $array_input = collect([
            'id_user',
            'situation',
            'start_date',
            'end_date'
        ]);

        // SAVE FILTERS
        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."id_user"){

                    $item->where('promoter_user_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."situation"){
                    if ($valor_filtro == 1) {
                        $item->where('is_cancelled', 1);
                    } else if ($valor_filtro == 2) {
                        $item->where('has_accept', 0);
                    } else if ($valor_filtro == 3) {
                        $item->where('has_accept', 1)->where('has_send', 0);
                    } else if ($valor_filtro == 4) {
                        $item->where('has_send', 1)->where('has_receiver', 0);
                    } else if ($valor_filtro == 5) {
                        $item->where('has_receiver', 1);
                    }
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $item->where('created_at', '>=', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $item->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($valor_filtro)));
                }
            }
        }



        return view('gree_i.commercial.promoter.request_item', [
            'item' => $item->paginate(10),
            'userall' => $userall
        ]);
    }

    public function admPromoterRouteEdit_do(Request $request) {

        if ($request->id == 0) {

            $route = new PromoterRoute;
        } else {
            $route = PromoterRoute::find($request->id);

            if (!$route) {

                return response()->json([
                    'message' => 'O rota não foi encontrado, atualize a página.'
                ], 400);
            }
        }

        DB::beginTransaction();
        $route->description = $request->description;
        $route->promoter_user_id = $request->user;
        $route->latitude = $request->latitude;
        $route->longitude = $request->longitude;
        $route->address = $request->address;
        $route->complement = $request->complement;
        $route->date_start = $request->date_start;
        $route->date_end = $request->date_end;
        $error = $route->save();

        if (!$error) {

            DB::rollBack();
            return response()->json([
                'message' => 'Não foi possível salvar no banco de dados, preencha todos os campos corretamente.'
            ], 400);
        }

        $raw_payload = $request->input('json_data');
        $payload = json_decode($raw_payload, true);

        if (!empty($payload)) {

            $to_insert = collect($payload)->pluck('id');
            $itens = PromoterRouteHistory::where('promoter_route_id', $route->id);
            $pluck = $itens->pluck('id');

            // Deletar
            if (!empty($pluck)) {
                $to_delete = $pluck->diff($to_insert);
                //$to_insert = $to_insert->diff(collect($pluck));
                $itens->whereIn('id', $to_delete)->delete();
            }

            if (count($payload) > 0) {
                foreach ($payload as $key) {

                    if ($key['id'] == 0) {
                        $add = new PromoterRouteHistory;
                    } else {
                        $add = PromoterRouteHistory::find($key['id']);
                    }
                    $add->promoter_route_id = $route->id;
                    $add->description = $key['description'];
                    $error = $add->save();

                    if (!$error) {

                        DB::rollBack();
                        return response()->json([
                            'message' => 'Não foi possível salvar no banco de dados a tarefa, verifique os dados preenchidos corretamente.'
                        ], 400);
                    }
                }
            }
        }

        DB::commit();

        $promoter_rh = PromoterRouteHistory::where('promoter_route_id', $route->promoter_route_id)->where('attach_date', NULL)->get();

        if ($promoter_rh->count() == 0) {
            $rte = PromoterRoute::find($route->promoter_route_id);
			if ($rte) {
				$rte->is_completed = 1;
				$rte->save();
			}
        } else {
            $rte = PromoterRoute::find($route->promoter_route_id);
			if ($rte) {
				$rte->is_completed = 0;
				$rte->save();
			}
        }

        if ($request->id == 0) {

            LogSystem("Colaborador criou uma rota para o promotor. Rota ID ". $route->id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Rota criada com sucesso!');
            return response()->json([], 200);
        } else {

            LogSystem("Colaborador atualizou a rota do promotor. Rota ID ". $route->id, $request->session()->get('r_code'));
            $request->session()->put('success', 'Rota atualizada com sucesso!');
            return response()->json([], 200);
        }

    }

    public function admPromoterRouteCancel(Request $request) {

        $route = PromoterRoute::find($request->id);

        if (!$route) {

            return response()->json([
                'message' => 'A rota não foi encontrado, atualize a página.'
            ], 400);
        }

        $route->is_cancelled = 1;
        $route->save();

        LogSystem("Colaborador cancelou a rota do promotor. Rota ID ". $route->id, $request->session()->get('r_code'));
        $request->session()->put('success', 'Rota foi cancelada com sucesso!');
        return response()->json([], 200);

    }

    public function admPromoterRouteAll(Request $request) {

        $userall = PromoterUsers::all();

        return view('gree_i.commercial.promoter.routes', [
            'userall' => $userall
        ]);
    }

    public function admPromoterCalendarGet(Request $request) {

        $routes = PromoterRoute::with(['user','routeHistory.images'])->OrderBy('id', 'DESC');

        if ($request->id_user) {
            $routes->where('promoter_user_id', $request->id_user);
        }
        if ($request->id_status) {
            if ($request->id_status == 1)
            $routes->where('is_completed', 1);
            else if ($request->id_status == 2)
            $routes->where('is_cancelled', 1);
            else
            $routes->where('is_completed', 0)->where('is_cancelled', 0);
        }
        $array = array();
        foreach ($routes->get() as $key) {
            $add = array();
            $add['title'] = $key->user->name;
            $add['start'] = $key->date_start;
            $add['end'] = date('Y-m-d', strtotime($key->date_end .' + 1 days'));
            $add['allDay'] = $key->date_start->format('Y-m-d') == $key->date_end->format('Y-m-d') ? true : false;
            $add['extendedProps'] = array(
                'id' => $key->id,
                'user' =>$key->user,
                'routeHistory' =>$key->routeHistory,
                'all' => $key,
            );

            array_push($array, $add);
        }
        return response()->json([
            'events' => $array,
        ], 200);

    }

    public function admPromoterMonitor(Request $request) {
        $userall = PromoterUsers::all();

        return view('gree_i.commercial.promoter.monitor', [
            'userall' => $userall
        ]);
    }

    public function admPromoterMonitorFilter(Request $request) {

        $is_route = false;


        if ($request->user and $request->date_start or $request->user and $request->date_end) {
            $track = PromoterUsers::with(['routes.routeHistory.images', 'positions' => function ($query) use ($request) {
                if ($request->date_start) {
                    if ($request->hour_start)
                    $query->where('created_at', '>=', $request->date_start .' '.$request->hour_start.':00');
                    else
                    $query->where('created_at', '>=', $request->date_start);
                }
                if ($request->date_end) {
                    if ($request->hour_end)
                    $query->where('created_at', '<=', $request->date_end .' '.$request->hour_end.':00');
                    else
                    $query->where('created_at', '<=', $request->date_end);
                }
            }])->ShowOnlyWithPosition();
            $is_route = true;
        } else {
            $track = PromoterUsers::with('routes.routeHistory.images')->ShowOnlyWithPosition();
        }

        if ($request->user) {
            $track->where('id', $request->user);
        }
        return response()->json([
            'track' => $track->get(),
            'is_route' => $is_route
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // PROMOTER EXTERNAL
    public function promoterNewPosition(Request $request) {

        $promoter_id = $request->session()->get('promoter_data')->id;

        $position = PromoterUserHistory::where('promoter_user_id', $promoter_id)->orderBy('id', 'DESC')->first();

        if ($position) {
            if ($position->latitude != $request->latitude or $position->longitude != $request->longitude) {

                $position = new PromoterUserHistory;
                $position->promoter_user_id = $promoter_id;
                $position->latitude = $request->latitude;
                $position->longitude = $request->longitude;
                $position->save();

                $params = [
                    'promoter_id' => $promoter_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                 ];

                // FIRE EVENT
                event(new EventSocket($params, 'promoteGetPosition'));

            }
        } else {

            $position = new PromoterUserHistory;
            $position->promoter_user_id = $promoter_id;
            $position->latitude = $request->latitude;
            $position->longitude = $request->longitude;
            $position->save();

            $params = [
                'promoter_id' => $promoter_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
             ];

            // FIRE EVENT
            event(new EventSocket($params, 'promoteGetPosition'));
        }

        return response()->json([
            'success' => true,
        ]);

    }

    public function promoterRequestItemEdit_do(Request $request) {

        if ($request->id == 0) {

            $item = new PromoterRequestItem;
        } else {
            $item = PromoterRequestItem::where('promoter_user_id', $request->session()->get('promoter_data')->id)->where('promoter_request_item.id', $request->id)->first();

            if (!$item) {

                $request->session()->put('error', 'Item que deseja atualizar, não foi encontrado');
                return redirect()->back();
            }
        }

        $item->name = $request->name;
        $item->quantity = $request->quantity;
        $item->promoter_user_id = $request->session()->get('promoter_data')->id;
        $item->save();

        if ($request->id == 0) {

            $request->session()->put('success', 'Item criado com sucesso!');
            return redirect()->back();
        } else {

            $request->session()->put('success', 'Item atualizado com sucesso!');
            return redirect()->back();
        }

    }

    public function promoterRequestItemDelete(Request $request, $id) {


        $item = PromoterRequestItem::where('promoter_user_id', $request->session()->get('promoter_data')->id)->where('promoter_request_item.id', $id)->first();

        if (!$item) {

            $request->session()->put('error', 'Item que deseja deletar, não foi encontrado.');
            return redirect()->back();
        }

        PromoterRequestItem::where('promoter_user_id', $request->session()->get('promoter_data')->id)->where('promoter_request_item.id', $id)->delete();

        $request->session()->put('success', 'Item deletado com sucesso!');
        return redirect()->back();


    }

    public function promoterRequestItemReceiver(Request $request, $id) {

        $item = PromoterRequestItem::where('promoter_user_id', $request->session()->get('promoter_data')->id)->where('promoter_request_item.id', $id)->first();

        if (!$item) {

            $request->session()->put('error', 'Item que desejado, não foi encontrado.');
            return redirect()->back();
        }

        $item->has_receiver = 1;
        $item->save();

        $request->session()->put('success', 'Confirmação do recebimento do item com sucesso!');
        return redirect()->back();


    }

    public function promoterLogin(Request $request) {

        return view('gree_commercial_promoter.login');
    }

    public function promoterLogout(Request $request) {

        $request->session()->forget('promoter_data');
        return redirect('/promotor/login');
    }

    public function promoterLoginVerify(Request $request) {

        $promoter = PromoterUsers::where('identity', $request->identity)->first();

        if ($promoter) {

            if (Hash::check($request->password, $promoter->password)) {

                $request->session()->put('promoter_data', $promoter);

                return response()->json([
                    'success' => true,
                    'message' => 'Acesso realizado com sucesso...'
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Senha desse usuário está incorreta.'
                ], 200);
            }
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Promotor não foi encontrado no sistema.'
            ], 200);
        }
    }

    public function promoterLoginForgotten(Request $request) {

        $promoter = PromoterUsers::where('email', $request->email_f)->first();

        if ($promoter) {

            $new_pass = rand(1000, 9999);
            $promoter->password = Hash::make($new_pass);
            $promoter->save();

            $pattern = array(
                'title' => 'RECUPERAÇÃO DE CONTA: PROMOTOR',
                'description' => nl2br("Olá, <b>". $promoter->name ."</b><p>Seus dados de acesso constam abaixo: <br><br>Login: <b>". $promoter->identity ."</b><br>Senha: <b>". $new_pass ."</b><br>Caso preciso de ajuda, não deixe de entrar em contato.</p>"),
                'template' => 'misc.DefaultExternal',
                'subject' => 'Gree - recuperação de conta',
            );

            SendMailJob::dispatch($pattern, $request->email_f);

            return response()->json([
                'success' => true,
                'message' => 'Foi enviado para o seu email os dados da sua conta.'
            ], 200);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível encontrar o promotor desse email.'
            ], 200);
        }
    }

    public function promoterDashboard(Request $request) {

        $routes = PromoterRoute::with('routeHistory')->where('promoter_user_id', $request->session()->get('promoter_data')->id)->paginate(10);

        return view('gree_commercial_promoter.dashboard', [
            'routes' => $routes
        ]);
    }

    public function promoterRequestItem(Request $request) {

        $carbon = new Carbon;

        $itens = PromoterRequestItem::where('promoter_user_id', $request->session()->get('promoter_data')->id)
                    ->orderBy('id', 'DESC')
                    ->paginate(10);

        return view('gree_commercial_promoter.request_item', [
            'itens' => $itens,
            'carbon' => $carbon,
        ]);
    }

    public function promoterTaskCompleted(Request $request) {

        $route = PromoterRouteHistory::where('attach_date', NULL)->where('id', $request->task_id)->first();

        if ($route) {

            DB::beginTransaction();

            if (!empty($request->images)) {

                $payload = json_decode($request->images, true);

                foreach ($payload as $index => $key) {

                    $response = $this->uploadS3Base64($index, $key, $request, 800);
                    if ($response['success']) {
                        $add_image = new PromoterRouteHistoryImg;
                        $add_image->promoter_route_history_id = $route->id;
                        $add_image->image = $response['url'];
                        $add_image->save();
                    } else {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'message' => 'Não foi possível fazer upload da imagem. Tente novamente!'
                        ], 400);
                    }
                }
            }

            $route->report = $request->report;
            $route->attach_date = date('Y-m-d H:i:s');
            $route->save();

            $promoter_rh = PromoterRouteHistory::where('promoter_route_id', $route->promoter_route_id)->where('attach_date', NULL)->get();

            if ($promoter_rh->count() == 0) {
                $rte = PromoterRoute::find($route->promoter_route_id);
                $rte->is_completed = 1;
                $rte->save();
            } else {
                $rte = PromoterRoute::find($route->promoter_route_id);
                $rte->is_completed = 0;
                $rte->save();
            }

            $request->session()->put('success', 'Sua tarefa foi concluida com sucesso!');
            DB::commit();

        } else {

            return response()->json([
                'success' => false,
                'message' => 'A tarefa que está tentando atualizar, foi cancelada ou já está concluida.'
            ], 200);
        }
    }

    public function promoteRouteUpdate(Request $request) {

        $route = PromoterRoute::where('checkout', NULL)->where('id', $request->id)->first();

        if ($route) {

            $checkout = false;
            if ($route->checkin) {
                $checkout = true;
                $route->checkout = date('Y-m-d H:i:s');
            } else {
                $route->checkin = date('Y-m-d H:i:s');
            }
            $route->save();

            $new_position = new PromoterUserHistory;
            $new_position->promoter_user_id = $request->session()->get('promoter_data')->id;
            $new_position->promoter_route_id = $route->id;
            $new_position->has_checkin = $checkout == false ? 1 : 0;
            $new_position->has_checkout = $checkout == true ? 1 : 0;
            if ($request->latitude and $request->longitude) {
                $new_position->latitude = $request->latitude;
                $new_position->longitude = $request->longitude;
            }
            $new_position->save();


            return response()->json([
                'success' => true,
            ], 200);

        } else {

            return response()->json([
                'success' => false,
                'message' => 'O checkin e checkout já foi realizado nessa rota.'
            ], 200);
        }
    }

    public function salesmanList(Request $request) {

        $salesman = Salesman::orderBy('id', 'DESC');

        $array_input = collect([
            'status',
            'name',
            'identity',
            'code'
        ]);

        $array_input = putSession($request, $array_input, 'sal_');
        $filter_session = getSessionFilters('sal_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."status"){
                    if ($value_filter == 1)
                        $salesman->where('is_active', 1);
                    else
                        $salesman->where('is_active', 0);
                }
                if($name_filter == $filter_session[1]."name"){
                    $salesman->where('first_name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."identity"){
                    $salesman->where('identity', $value_filter);
                }
                if($name_filter == $filter_session[1]."code"){
                    $salesman->where('code', $value_filter);
                }
            }
        }

        return view('gree_commercial.salesman.salesmanList', [
            'salesman' => $salesman->paginate(10),
        ]);
    }

    public function salesmanEdit(Request $request, $id) {

        if ($id == 0) {
            $is_active = 1;
            $code = '';
            $picture = '';
            $is_direction = 0;
            $r_code = '';
            $identity = '';
            $company_name = '';
            $full_name = '';
            $phone1 = '';
            $phone2 = '';
            $email = '';
            $zipcode = '';
            $address = '';
            $city = '';
            $state = '';
            $region = '';
            $office = '';
            $complement = '';
            $immediate = collect();
            $subordinate = collect();
            $states = collect();
			$password = '';
			$otpauth = '';

        } else {

            $salesman = Salesman::with('immediate_boss', 'subordinates', 'salesman_on_state')->where('id', $id)->first();

            if($salesman) {
                $is_active = $salesman->is_active;
                $code = $salesman->code;
                $picture = $salesman->picture;
                $is_direction = $salesman->is_direction;
                $r_code = $salesman->r_code;
                $identity = $salesman->identity;
                $company_name = $salesman->company_name;
                $full_name = ''.$salesman->first_name.' '.$salesman->last_name.'';
                $phone1 = $salesman->phone_1;
                $phone2 = $salesman->phone_2;
                $email = $salesman->email;
                $zipcode = $salesman->zipcode;
                $address = $salesman->address;
                $city = $salesman->city;
                $state = $salesman->state;
                $region = $salesman->region;
                $office = $salesman->office;
                $complement = $salesman->complement;
                $immediate = $salesman->immediate_boss;
                $subordinate = $salesman->subordinates;
                $states = $salesman->salesman_on_state;
				$password = $salesman->password;
				$otpauth = $salesman->otpauth;

            }else {
                $request->session()->put('error', 'Representante não foi encontrado.');
                return redirect()->back();
            }
        }

        return view('gree_commercial.salesman.salesmanEdit', [
            'is_active' => $is_active,
            'code' => $code,
            'picture' => $picture,
            'is_direction' => $is_direction,
            'r_code' => $r_code,
            'identity' => $identity,
            'company_name' => $company_name,
            'full_name' => $full_name,
            'phone1' => $phone1,
            'phone2' => $phone2,
            'email' => $email,
            'zipcode' => $zipcode,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'region' => $region,
            'office' => $office,
            'complement' => $complement,
            'immediate' => $immediate,
            'subordinate' => $subordinate,
            'states' => $states,
            'id' => $id,
			'password' => $password,
			'otpauth' => $otpauth
        ]);
    }

    public function salesmanEdit_do(Request $request) {

        DB::beginTransaction();

        if ($request->id == 0) {

            $salesman = new Salesman;
        } else {

            $salesman = Salesman::find($request->id);

            if (!$salesman) {

                $request->session()->put('error', 'Representante não foi encontrado.');
                return redirect()->back();
            }
        }

		if ($request->is_active == 1) {
			$salesman->is_active = 1;
			$salesman->retry = 3;
		} else {
			$salesman->is_active = 0;
		}
        
        $salesman->code = $request->code;
        $salesman->is_direction = $request->is_direction;
        $salesman->r_code = $request->r_code;
        $salesman->identity = $request->identity;
        $salesman->company_name = $request->company_name;

        if ($request->hasFile('picture')) {
            $response = $this->uploadS3(1, $request->picture, $request, 200, true, 'png');
            if ($response['success']) {
                $salesman->picture = $response['url'];
            } else {
                return redirect()->back()->with('error', 'Não foi possível fazer upload do arquivo!');
            }
        }

        $first_name = "";
        $last_name = "";
        if($request->contact_name != '') {
            $name = explode(" ", $request->contact_name);
            $first_name = array_shift($name);
            $last_name = implode(" ", $name);
        }

        $salesman->first_name = $first_name;
        $salesman->last_name = $last_name;

        $salesman->phone_1 = $request->phone1;
        $salesman->phone_2 = $request->phone2;
        $salesman->email = $request->email;
        $salesman->zipcode = $request->zipcode;
        $salesman->address = $request->address;
        $salesman->city = $request->city;
        $salesman->state = $request->state;
        $salesman->region = $request->region;
        $salesman->office = $request->office;
        $salesman->complement = $request->complement;
		
		$new_pass = rand(1000, 9999);
		if($request->is_direction == 3 || $request->is_direction == 2) {

            $users = Users::where('r_code', $request->r_code)->first();
            if(!$users)
                return redirect()->back()->with('error', 'Necessário gerente ou diretor ser cadastrado no GREE-APP');
            
            $salesman->password = $users->password;

        } else {

            if($request->password == '') 
                $salesman->password = Hash::make($new_pass);
            else 
                $salesman->password = Hash::make($request->password);
        }

        if ($salesman->save()) {

            $immediate = json_decode($request->immediate);

            if (!empty($immediate)) {

                // cria o collection a partir do request
                $request_collect = collect($immediate);

                // faz uma consulta para buscar os dados para comparação
                $query = salesmanImmediate::where('salesman_id', $salesman->id)->pluck('immediate_id');

                // Compara os dados do request no formato collect com os dados do banco utilizando o pluck acima. Para deletar
                $immediate_to_delete = $query->diff($request_collect);

                // Compara os dados do request no formato collect com os dados do banco utilizando o pluck acima. Para inserir
                $request_collect = $request_collect->diff(collect($query));

                salesmanImmediate::whereIn('immediate_id', $immediate_to_delete)->where('salesman_id', $salesman->id)->delete();

                foreach ($request_collect as $id) {

                    $immediate_new = new SalesmanImmediate;
                    $immediate_new->salesman_id = $salesman->id;
                    $immediate_new->immediate_id = $id;
                    $immediate_new->save();
                }

            }

            $states = json_decode($request->states);

            if (!empty($states)) {

                // cria o collection a partir do request
                $request_collect = collect($states);

                $salmesmanonstate = SalesmanOnState::where('salesman_id', $request->id);
                $uf_state = $salmesmanonstate->pluck('state');

                if (!$uf_state->isEmpty()) {

                    $state_to_delete = $uf_state->diff($request_collect);
                    $request_collect = $request_collect->diff($uf_state);
                    $salmesmanonstate->whereIn('state', $state_to_delete)->delete();
                }

                foreach ($request_collect as $id) {

                    $state_new = new SalesmanOnState;
                    $state_new->salesman_id = $salesman->id;
                    $state_new->state = $id;
                    $state_new->save();
                }

            }

        } else {

            DB::rollBack();
            $request->session()->put('error', 'Ocorreu um erro ao salvar os dados do representante, preencha as informações, corretamente.');
            return redirect()->back();
        }

        if ($request->id == 0) {

            $pattern = array(
                'title' => 'ACESSO DA CONTA: REPRESENTANTE',
                'description' => nl2br("Olá, <b>". $salesman->full_name ."</b><p>Seus dados de acesso constam abaixo: <br><br>Login: <b>". $salesman->identity ."</b><br>Senha: <b>". $new_pass ."</b><br>Caso preciso de ajuda, não deixe de entrar em contato.</p></br><hr><p>Para realizar acesso ao painel, click abaixo:</p><p><a target='_blank' href='". $request->root()."/comercial/operacao/login'>". $request->root()."/comercial/operacao/login</a></p>"),
                'template' => 'misc.DefaultExternal',
                'subject' => 'Acesso da conta: representante',
            );

            SendMailJob::dispatch($pattern, $salesman->email);
        }

        $request->session()->put('success', 'Representante salvo com sucesso!');
        return redirect('/commercial/salesman/list');
    }
	
	public function salesmanView(Request $request, $id) {

        $user = Salesman::find($id);

        if ($user) {

            LogSystem("Colaborador realizou acesso como representante ID: ". $id, $request->session()->get('r_code'));

            $request->session()->put('salesman_data', $user);
            $request->session()->put('sal_code', $user->code);

            if($user->otpauth)
                $request->session()->put('sal_otpauth', $user->otpauth);
            else
                $request->session()->put('sal_otpauth', 'NDXJUTKSDFP573UWP4LNBVANOTDYV2JT');    


            return redirect('/comercial/operacao/dashboard');
        } else {

            $request->session()->put('error', 'Representante não foi encontrado');
            return redirect()->back();
        }

    }

    public function salesmanDelete(Request $request, $id) {

        $salesman = Salesman::find($id);

        if ($salesman) {

            $salesman->delete();

            $request->session()->put('success', 'Representante foi deletado com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Representante não encontrado no banco de dados.');
            return redirect()->back();
        }
    }

    public function salesmanVerifyIdentityAjax(Request $request) {

        $name = '';
        $exists = false;
        $identity = '';

        $varify = Salesman::where(''.$request->name.'', $request->value)->first();

        if($varify != null) {
            $name = ''.$varify->first_name.' '.$varify->last_name.'';
            $exists = true;
            $identity = $varify->identity;
        }

        return response()->json([
            'success' => true,
            'name' => $name,
            'exists' => $exists,
            'identity' => $identity
        ], 200, array(), JSON_PRETTY_PRINT);
    }
	
	public function salesmanResetAuth(Request $request, $id) {

        $salesman = Salesman::find($id);

        if ($salesman) {

            $salesman->otpauth = null;
            $salesman->save();

            $request->session()->put('success', 'Autenticação de 2 fatores do representante foi resetada com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Representante não encontrado no banco de dados.');
            return redirect()->back();
        }
    }

    public function clientGroupList(Request $request) {

        $client_group = ClientGroup::orderBy('id', 'DESC');

        $array_input = collect([
            'fname',
            'fcode',
            'fstatus'
        ]);

        $array_input = putSession($request, $array_input, 'client_');
        $filter_session = getSessionFilters('client_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."fstatus"){
                    if ($value_filter == 1)
                        $client_group->where('is_active', 1);
                    else
                        $client_group->where('is_active', 0);
                }
                if($name_filter == $filter_session[1]."fname"){
                    $client_group->where('name', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."fcode"){
                    $client_group->where('code', $value_filter);
                }
            }
        }

        $last = ClientGroup::withTrashed()->orderBy('id', 'DESC')->first();
        if($last) {
            $total = $last->id + 1;
        } else {
            $total = 1;
        }

        return view('gree_commercial.client.groupList', [
            'client_group' => $client_group->paginate(10),
            'total' => $total,
        ]);
    }

    public function clientGroupEdit_do(Request $request) {

        if ($request->id) {

            $client_group = ClientGroup::find($request->id);

            if ($client_group) {

                $client_group->code = $request->code;
                $client_group->name = $request->name;
                $client_group->is_active = $request->is_active;
                $client_group->save();

                $request->session()->put('success', 'Grupo foi atualizado com sucesso!');
                return redirect()->back();

            } else {

                $request->session()->put('error', 'Grupo não encontrado no banco de dados.');
                return redirect()->back();
            }
        } else {

            $client_group = new ClientGroup;

            $client_group->code = $request->code;
            $client_group->name = $request->name;
            $client_group->is_active = $request->is_active;
            $client_group->save();

            $request->session()->put('success', 'Grupo criado com sucesso!');
            return redirect()->back();

        }
    }

    public function clientGroupDelete(Request $request, $id) {

        $client_group = ClientGroup::find($id);

        if ($client_group) {

            $client_group->delete();

            $request->session()->put('success', 'Grupo de cliente foi deletado com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Grupo de cliente não encontrado no banco de dados.');
            return redirect()->back();
        }
    }

    public function clientList(Request $request) {

        $client = Client::with('client_group')->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'name',
            'identity',
            'status',
			'is_analyze',
            'region',
            'status_chart',
			'salesman_id',
			'reason_social'
        ]);

        $array_input = putSession($request, $array_input, 'client_');
        $filter_session = getSessionFilters('client_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {
				
                if($name_filter == $filter_session[1]."code"){
                    $client->where('code', $value_filter);
                }
                if($name_filter == $filter_session[1]."name"){
                    $client->where('fantasy_name', 'like', '%'.$value_filter.'%');
                }
				if($name_filter == $filter_session[1]."reason_social"){
                    $client->where('company_name', 'like', '%'.$value_filter.'%');
                }
				if($name_filter == $filter_session[1]."salesman_id"){
                    $client->where('request_salesman_id', $value_filter);
                }
                if($name_filter == $filter_session[1]."identity"){
                    $client->where('identity', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."status"){
	
					if ($value_filter == 1) {
                        $client->where('is_active', 1);
                    } elseif ($value_filter == 2) {
                        $client->where('is_active', 0);
                    } elseif ($value_filter == 3) {
                        $client->where('salesman_imdt_reprov', 1)
                               ->orWhere('revision_is_reprov', 1)
                               ->orWhere('judicial_is_reprov', 1)
                               ->orWhere('commercial_is_reprov', 1)
                               ->orWhere('financy_reprov', 1);
                    } elseif ($value_filter == 4 || $value_filter == 5 || $value_filter == 6) {
                        $client->where('salesman_imdt_approv', 1)
                               ->where('revision_is_approv', 1)
                               ->where('judicial_is_approv', 1)
                               ->where('commercial_is_approv', 1)
                               ->where('financy_approv', 1);

                        if($value_filter == 4)
                            $client->where('financy_status', 1);
                        elseif($value_filter == 5)    
                            $client->where('financy_status', 2);
                        elseif($value_filter == 6)        
                            $client->where('financy_status', 3);
                    }    
                    elseif($value_filter == 7 || $value_filter == 8 || $value_filter == 9) {

                        $client->where('has_analyze', 0);

                        if($value_filter == 7)
                            $client->where('financy_status', 1);
                        elseif($value_filter == 8)    
                            $client->where('financy_status', 2);
                        elseif($value_filter == 9)        
                            $client->where('financy_status', 3);
                    }
                    elseif($value_filter == 10) {
                        $client->where('has_analyze', 1);
                    }
                    
                }
				if($name_filter == $filter_session[1]."is_analyze"){
                    $client->where('has_analyze', 1);
                }
				if($name_filter == $filter_session[1]."region"){
                    $client->whereIn('state', config('gree.arr_region')[$value_filter]);
                }
				if($name_filter == $filter_session[1]."status_chart"){

                    if($value_filter == 'Liberado antecipado') {
                        
                        $client->where('salesman_imdt_approv', 1)
                               ->where('revision_is_approv', 1) 
                               ->where('judicial_is_approv', 1)
                               ->where('commercial_is_approv', 1)
                               ->where('financy_approv', 1)
                               ->where('financy_status', 2)
                               ->orWhere(function ($query) {
                                    $query->where('has_analyze', 0)
                                        ->where('financy_status', 2);
                                });                                
                    }
                    elseif($value_filter == 'Liberado antecipado e parcelado') {
                        $client->where('salesman_imdt_approv', 1)
                               ->where('revision_is_approv', 1) 
                               ->where('judicial_is_approv', 1)
                               ->where('commercial_is_approv', 1)
                               ->where('financy_approv', 1)
                               ->where('financy_status', 3)
                               ->orWhere(function ($query) {
                                    $query->where('has_analyze', 0)
                                          ->where('financy_status', 3);
                                });                                
                    }
                    elseif($value_filter == 'Desativado') {
                        $client->where('is_active', 0);
                    }
                    elseif($value_filter == 'Ativo') {
                        $client->where('is_active', 1);
                    }
                }
            }
        }
		
		if ($request->export == 1) {
            return Excel::download(new ClientsExport($request), 'ClientsExport'. date('Y-m-d H.s') .'.xlsx');
        }

        return view('gree_commercial.client.clientList', [
            'client' => $client->paginate(10)
        ]);
    }

    public function clientEdit(Request $request, $id) {

        $client = new ManagerClient($request, $id);

        return view('gree_commercial.client.clientEdit', [
            'client' => $client->mng_model,
            'id' => $id
        ]);
    }

    public function clientEdit_do(Request $request) {

        $client = new ManagerClient($request, $request->id);

        try {

            if ($client->mng_model != null)
                if ($client->mng_model->has_analyze == 1)
                    return redirect()->back()->with('error', 'Não é possível realizar alteração de um cadastro em análise.');

            $client->editClient();

            $request->session()->put('success', 'Cliente salvo com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {

            $request->session()->put('error', $e->getMessage());
            return redirect()->back();

        }
    }

    public function clientEditAnalyze(Request $request) {

        try {

            if ($request->id == 0) {
                $client = new ManagerClient($request, $request->id);
                $request->merge(['id' => $client->editClient()]);

                $settings = Settings::where('command', 'client_new_register')->first();

                if ($settings->value) {
                    $arr = explode(',', $settings->value);

                    foreach ($arr as $key) {

                        $pattern = array(
                            'title' => 'COMERCIAL - NOVO CLIENTE REGISTRADO',
                            'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre o novo cadastro.
                            <p>Cliente: ". $request->company_name ."<br>
                               Identidade: ". $request->identity ."<br>
                               URL: <a href='". $request->root() ."/commercial/client/list'>". $request->root() ."/commercial/client/list</a>
                            </p>"),
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'Comercial - Novo cliente registrado',
                        );

                        SendMailJob::dispatch($pattern, $key);
                    }
                }

            } else {

                $settings = Settings::where('command', 'client_update_register')->first();

                if ($settings->value) {
                    $arr = explode(',', $settings->value);

                    foreach ($arr as $key) {

                        $pattern = array(
                            'title' => 'COMERCIAL - CLIENTE ATUALIZADO',
                            'description' => nl2br("Acesse a plataforma da Gree em comercial, para verificar mais informações sobre atualização do cadastro.
                            <p>Cliente: ". $request->company_name ."<br>
                               Identidade: ". $request->identity ."<br>
                               URL: <a href='". $request->root() ."/commercial/client/list'>". $request->root() ."/commercial/client/list</a>
                            </p>"),
                            'template' => 'misc.DefaultExternal',
                            'subject' => 'Comercial - Cliente atualizado',
                        );

                        SendMailJob::dispatch($pattern, $key);
                    }
                }
            }

            $analyze_client =  new AnalyzeProcessClient($request);
            $analyze_client->startAnalyze();

            return redirect()->back()->with('success', 'Dados do cliente enviados para análise!');

        } catch (\Exception $e) {
            $request->session()->put('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function clientAnalyze_do(Request $request) {

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {

            try {
                $AnalyzeProcessClient = new AnalyzeProcessClient($request);
                $AnalyzeProcessClient->doAnalyze($request->type_analyze, 1, $request->description);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            return redirect('/commercial/client/list/analyze')->with('success', 'Análise realizada com sucesso!');
        } else {

            if ($user->retry > 0) {

                $user->retry = $user->retry - 1;
                $user->save();

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Cliente do comercial) muitas vezes e foi bloqueado no sistema.", $user->r_code);
                    return redirect('/logout')->with('error', "You have often erred in your secret password and been blocked, talk to administration.");
                } else {

                    // Write Log
                    LogSystem("Colaborador errou sua senha secreta para aprovar (Cliente do comercial). Restou apenas ". $user->retry ." tentativa(s).", $user->r_code);
                    return redirect()->back()->with('error', 'You missed your secret password, only '. $user->retry .' attempt(s) left.');
                }
            } else {

                // Write Log
                LogSystem("Colaborador está tentando aprovar (Cliente do comercial) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->r_code);
                return redirect()->back();
            }
        }


    }

    public function clientDocumentsAjax(Request $request) {

        $client = new ManagerClient($request);

        try {

            $return = $client->uploadDocuments();

            return response()->json([
                'success' => true,
                'url' => $return
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    public function clientDocumentDeleteAjax(Request $request) {

        $client = new ManagerClient($request);

        try {

            $client->deleteDocument();

            return response()->json([
                'success' => true,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /*private function validPerm($request, $perm_id, $grade, $can_approv) {
        $imDir = $request->session()->get('permissoes_usuario')
            ->where('user_r_code', $request->session()->get('r_code'))
            ->where('perm_id', $perm_id);

		if ($grade)
            $imDir->where('grade', $grade);

        $result = $imDir->where('can_approv', $can_approv)
            ->first();
		
        return $result;
    }*/
	
	private function validPerm($request, $perm_id, $grade, $can_approv) {
        
		if ($grade) {
            $imDir = $request->session()->get('permissoes_usuario')
            ->where('user_r_code', $request->session()->get('r_code'))
            ->where('perm_id', $perm_id)
            ->where('grade', $grade)
            ->where('can_approv', $can_approv)
            ->first();
        } else {
            $imDir = $request->session()->get('permissoes_usuario')
            ->where('user_r_code', $request->session()->get('r_code'))
            ->where('perm_id', $perm_id)
            ->where('can_approv', $can_approv)
            ->first();
        }    
		
        return $imDir;
    }

    public function clientAnalyzeList(Request $request) {

        $analyze = Client::with(['client_group', 'client_version' => function ($q) {
            $q->orderBy('id', 'DESC')->withTrashed();
        }])->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 1)
            ->where('salesman_imdt_reprov', 0)
            ->orderBy('id', 'DESC');

        if ($this->validPerm($request, 20, 4, 1)) {
            $analyze->where('salesman_imdt_approv', 1)
				->where('revision_is_approv', 0)
				->where('revision_is_reprov', 0)
				->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);

        } else if ($this->validPerm($request, 23, null, 1)) {
            $analyze->where('salesman_imdt_approv', 1)
				->where('revision_is_approv', 1)
				->where('judicial_is_approv', 0)
				->where('judicial_is_reprov', 0)
				->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);

        } else if ($this->validPerm($request, 20, 9, 1)) {
            $analyze->where('salesman_imdt_approv', 1)
                ->where('judicial_is_approv', 1)
                ->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);

        } else if ($this->validPerm($request, 18, 8, 1)) {
            $analyze->where('salesman_imdt_approv', 1)
                ->where('judicial_is_approv', 1)
                ->where('commercial_is_approv', 1)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);
        } else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar essa página.');
		}

        $array_input = collect([
            'status',
            'name',
            'identity',
            'code'
        ]);

        $array_input = putSession($request, $array_input, 'client_');
        $filter_session = getSessionFilters('client_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."status"){
                    $analyze->ClientVersionStatus($value_filter);
                }
                if($name_filter == $filter_session[1]."code"){
                    $analyze->where('code', 'like', '%'.$value_filter.'%');
                }
                if($name_filter == $filter_session[1]."fantasy_name"){
                    $analyze->where('fantasy_name', $value_filter);
                }
                if($name_filter == $filter_session[1]."cnpj_rg"){
                    $analyze->where('identity', $value_filter);
                }
                if($name_filter == $filter_session[1]."group"){
                    $analyze->GroupFilter($value_filter);
                }
            }
        }

        return view('gree_commercial.client.listAnalyze', [
            'analyze' => $analyze->paginate(10),
        ]);
    }

    public function clientAnalyze(Request $request, $id) {
	
		$client = Client::find($id);
        $versions = ClientVersion::where('client_id', $id)->orderBy('version', 'DESC')->get();
        $documents = ClientDocuments::with([
            'contractSocial' => function($q) {
                $q->orderBy('id', 'DESC');
            }, 
            'balanceEquity' => function($q) {
                $q->orderBy('id', 'DESC');
            },
            'balanceEquity2Year' => function($q) {
                $q->orderBy('id', 'DESC');
            },
            'balanceEquity3Year' => function($q) {
                $q->orderBy('id', 'DESC');
            }
        ])->where('client_id', $id)->first();

        if ($versions->count() == 0)
            return redirect('/commercial/client/list/analyze')->with('error', 'Cliente para realizar a análise, não foi encontrado.');

        return view('gree_commercial.client.analyze', [
            'id' => $id,
			'client' => $client,
            'versions' => $versions,
            'documents' => $documents,
        ]);
    }

    private function clientCreateArrayList($list, $type_user = 1) {

        $arr = [];
        if ($type_user == 1) {
            foreach ($list as $key) {
                $desc = $key->description ? $key->description : "";
                $row = array(
                    'type_user' => 'Representante',
                    'name' => $key->salesman->short_name,
                    'office' => $key->salesman->office,
                    'status' => $key->is_approv,
                    'description' => $desc,
                    'version' => $key->version,
                );

                array_push($arr, $row);
            }
        } else {
            foreach ($list as $key) {
                $desc = $key->description ? $key->description : "";
                $row = array(
                    'type_user' => 'Usuário interno',
                    'name' => $key->user->short_name,
                    'office' => $key->user->office,
                    'status' => $key->is_approv,
                    'description' => $desc,
                    'version' => $key->version,
                );

                array_push($arr, $row);
            }
        }

        return $arr;
    }

    public function clientAnalyzeHistoryApprov(Request $request) {

        $version = ClientVersion::with([
                    'ClientImdtAnalyze' => function($q) use($request) {
                        $q->where('version', $request->version_hist);
                    },
                    'ClientImdtAnalyze.salesman',
					'ClientRevisionAnalyze' => function($q) use($request) {
						$q->where('version', $request->version_hist);
					},
					'ClientRevisionAnalyze.user',
					'ClientJudicialAnalyze' => function($q) use($request) {
						$q->where('version', $request->version_hist);
					},
					'ClientJudicialAnalyze.user',
                    'ClientCommercialAnalyze' => function($q) use($request) {
                        $q->where('version', $request->version_hist);
                    },
                    'ClientCommercialAnalyze.user',
                    'ClientFinancyAnalyze' => function($q) use($request) {
                        $q->where('version', $request->version_hist);
                    },
                    'ClientFinancyAnalyze.user'

                    ])->where('client_id', $request->id)
                    ->where('version', $request->version_hist)->withTrashed()->first();

        if ($version) {
            $data = [
                'imdt' => $version->ClientImdtAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientImdtAnalyze)) : [],
				'revision' => $version->ClientRevisionAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientRevisionAnalyze, 2)): [],
				'judicial' => $version->ClientJudicialAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientJudicialAnalyze, 2)): [],
                'commercial' => $version->ClientCommercialAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientCommercialAnalyze, 2)): [],
                'financy' => $version->ClientFinancyAnalyze->count() > 0 ? array_reverse($this->clientCreateArrayList($version->ClientFinancyAnalyze, 2)) : [],
            ];
        } else {
            $data = [];
        }


        return response()->json([
            'data' => $data
        ], 200);
    }

    public function productGroupEdit_do(Request $request) {

        if ($request->id) {

            $set_product_group = SetProductGroup::find($request->id);

            if ($set_product_group) {

                $set_product_group->code = $request->code;
                $set_product_group->name = $request->name;
                $set_product_group->is_conf_cap = $request->is_conf_cap;
                $set_product_group->is_active = $request->is_active;
                $set_product_group->save();

                $request->session()->put('success', 'Grupo foi atualizado com sucesso!');
                return redirect()->back();

            } else {

                $request->session()->put('error', 'Grupo não encontrado no banco de dados.');
                return redirect()->back();
            }
        } else {

            $set_product_group = new SetProductGroup;

            $set_product_group->code = $request->code;
            $set_product_group->position = $request->position;
            $set_product_group->name = $request->name;
            $set_product_group->is_conf_cap = $request->is_conf_cap;
            $set_product_group->is_active = $request->is_active;
            $set_product_group->save();

            $request->session()->put('success', 'Grupo criado com sucesso!');
            return redirect()->back();

        }

    }

    public function productGroupEditDelete(Request $request, $id) {

        $set_product_group = SetProductGroup::find($id);

        if ($set_product_group) {

            SetProductGroup::where('id', $id)->delete();

            $request->session()->put('success', 'Grupo foi deletado com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Grupo não encontrado no banco de dados.');
            return redirect()->back();
        }

    }

	public function productGroupList(Request $request) {

        $set_product_group = SetProductGroup::with('setProductOnGroup')->orderBy('id', 'DESC');

        $array_input = collect([
            'status',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1)
                    $set_product_group->where('is_active', 1);
                    else
                    $set_product_group->where('is_active', 0);

                }

            }
        }


        $last = SetProductGroup::withTrashed()->orderBy('id', 'DESC')->first();

        if ($last) {
            $last = $last->id + 1;
        } else {
            $last = 1;
        }
        return view('gree_commercial.product.groupList', [
            'set_product_group' => $set_product_group->paginate(10),
            'last_id' => $last
        ]);
    }

	public function productSetEdit(Request $request, $id) {

        if ($id == 0) {

            $last = SetProduct::withTrashed()->orderBy('id', 'DESC')->first();
            if ($last) {
                $numb = $last->id + 1;
                $code = 'CP-'. $numb;
            } else {
                $code = 'CP-1';
            }

            $position = 1;
            $is_active = 1;
            $group = '';
            $is_qf = 0;
			$btus = 0;
            $resume = '';
            $capacity = 1;
            $evap = '';
            $evap_product_price = '';
            $cond = '';
            $has_type_client = '';
            $cond_product_price = '';
			
			$is_for_hide = 2;
			$show_for_salesmans = collect([]);

        } else {

            $set = SetProduct::with('setProductOnGroup', 'productAirEvap', 'productAirCond')->where('id', $id)->first();

            if ($set) {

                $numb = $set->id;
                $code = 'CP-'. $numb;
                $position = $set->position;
                $is_active = $set->is_active == 1 ? 1 : 2;
                $group = $set->setProductOnGroup->first();
                $is_qf = $set->is_qf;
                $resume = $set->resume;
                $capacity = $set->capacity;
                $evap = $set->productAirEvap;
                $evap_product_price = $set->evap_product_price;
                $cond = $set->productAirCond;
                $has_type_client = $set->has_type_client;
                $cond_product_price = $set->cond_product_price;
				$btus = $set->btus;
				
				$is_for_hide = $set->is_for_hide == 1 ? 1 : 2;
				$show_for_salesmans = collect(explode(',', $set->show_for_salesmans));
				

            } else {

                $request->session()->put('error', 'Conjunto não foi encontrado no banco de dados.');
                return redirect()->back();
            }
        }

		$salesmans = Salesman::all();

        return view('gree_commercial.product.setEdit', [
            'code' => $code,
			'salesmans' => $salesmans,
            'position' => $position,
            'is_active' => $is_active,
			'btus' => $btus,
            'group' => $group,
            'is_qf' => $is_qf,
            'resume' => $resume,
            'capacity' => $capacity,
            'evap' => $evap,
            'evap_product_price' => $evap_product_price,
            'cond' => $cond,
            'has_type_client' => $has_type_client,
            'cond_product_price' => $cond_product_price,
			'is_for_hide' => $is_for_hide,
			'show_for_salesmans' =>	$show_for_salesmans,
            'id' => $id,
        ]);
    }

    public function productSetEdit_do(Request $request) {

        if ($request->id == 0) {

            $set = new SetProduct;
        } else {

            $set = SetProduct::find($request->id);

            if (!$set) {

                $request->session()->put('error', 'Conjunto não foi encontrado no banco de dados.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();

        $old_value_evap = $set->evap_product_price;
        $old_value_cond = $set->cond_product_price;

        $set->code = $request->code;
        $set->position = $request->position;
        $set->is_active = $request->is_active == 1 ? 1 : 0;
        $set->is_qf = $request->is_qf == 1 ? 1 : 0;
        $set->has_type_client = $request->has_type_client == 1 ? 1 : 0;
        $set->resume = $request->resume;
        $set->capacity = $request->capacity;
		$set->btus = $request->btus;
        $set->evap_product_id = $request->evap;
        $set->evap_product_price = $request->evap_price;
        $set->cond_product_id = $request->cond;
        $set->cond_product_price = $request->cond_price;
		
		if ($request->is_for_hide == 1) {
			$set->is_for_hide = 1;
			if ($request->show_for_salesmans) {
				$set->show_for_salesmans = implode(',', $request->show_for_salesmans);
			}
		} else if ($request->is_for_hide == 2) {
			$set->is_for_hide = 0;
			$set->show_for_salesmans = '';
		}

        if (!$set->save()) {

            DB::rollBack();
            $request->session()->put('error', 'Ocorreu um erro ao salvar os dados do conjunto, preencha as informações, corretamente.');
            return redirect()->back();
        }

        if ($request->id != 0) {

            if ($old_value_evap != $request->evap_price or $old_value_cond != $request->cond_price) {
                $settings = Settings::where('command', 'version_table_price')->first();

                $med = ($settings->value - number_format($settings->value, 0)) * 100;

                if ($med >= 50 or $med <= 99)
                $settings->value = round($settings->value + 0.01, 2);
                else
                $settings->value = round($settings->value + 0.50);

                $settings->save();
            }
        }

        $set_product_on_group = SetProductOnGroup::where('set_product_id', $set->id)->first();

        if (!$set_product_on_group) {
            $set_product_on_group = new SetProductOnGroup;
        }

        $set_product_on_group->set_product_id = $set->id;
        $set_product_on_group->set_product_group_id = $request->group;

        if (!$set_product_on_group->save()) {

            DB::rollBack();
            $request->session()->put('error', 'Ocorreu um erro ao salvar os dados do grupo no conjunto, preencha as informações, corretamente.');
            return redirect()->back();
        }

        DB::commit();

        $request->session()->put('success', 'Conjuntos atualizados com sucesso!');
        return redirect()->back();

    }

    public function productSetEditDelete(Request $request, $id) {

        $set_product = SetProduct::find($id);

        if ($set_product) {

            SetProduct::where('id', $id)->delete();
            SetProductOnGroup::where('set_product_id', $set_product->id)->delete();

            $request->session()->put('success', 'Conjunto foi deletado com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Conjunto não encontrado no banco de dados.');
            return redirect()->back();
        }

    }

    public function productSaveDelete(Request $request, $id) {

        $set_product = SetProductSave::find($id);

        if ($set_product) {

            SetProductSave::where('id', $id)->delete();

            $request->session()->put('success', 'Tabela foi deletado com sucesso!');
            return redirect('/commercial/product/set/list');

        } else {

            $request->session()->put('error', 'Tabela não encontrado no banco de dados.');
            return redirect()->back();
        }

    }

	public function productSetList(Request $request) {

        $set_product = SetProduct::with('setProductOnGroup', 'productAirEvap', 'productAirCond')->orderBy('id', 'DESC');

        $sum_products = SetProduct::with('setProductOnGroup')->where('is_active', 1)->orderBy('id', 'DESC')->get();

        $array_input = collect([
            'status',
            'group',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1)
                    $set_product->where('is_active', 1);
                    else
                    $set_product->where('is_active', 0);

                } else if ($nome_filtro == $filtros_sessao[1]."group") {

                    $set_product->SearchProductPerGroup([$valor_filtro]);

                }
            }
        }

        $set_product_adjust = SetProductAdjust::orderBy('id', 'DESC')->paginate(10,['*'], 'adjust');

        if ($request->table_id) {
            $table = SetProductSave::orderBy('id', 'DESC')->where('id', $request->table_id)->first();
        } else {
            $table = SetProductSave::orderBy('id', 'DESC')->first();
        }

        if ($request->has('tab')) {
            $request->session()->put('tab', $request->tab);
        } else {
            $request->session()->forget('tab');
        }

        if ($table)
        $var_table = json_decode($table->toJson(), false);
        else
        $var_table = '';

        $settings = Settings::where('command', 'version_table_price')->first();

        return view('gree_commercial.product.setList', [
            'set_product' => $set_product->paginate(10,['*'], 'list'),
            'sum_products' => $sum_products,
            'set_product_adjust' => $set_product_adjust,
            'table' => $var_table,
            'version' => $settings->value,
        ]);
    }

    public function productCreateCopy(Request $request) {

        $products = SetProductGroup::with('setProductOnGroup')->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $table = new SetProductSave;
        $table->name = $request->table_name;
        $table->collect = $products;
        $table->save();

        $request->session()->put('success', 'Sua tabela de preço foi salva com sucesso!');
        return redirect()->back();
    }

    private function productApplyAdjust($request, $data) {

        $new_amount = 0;
        $old_amount = 0;
        foreach ($data as $key) {

            $old_amount = $old_amount + ($key->evap_product_price + $key->cond_product_price);

            if ($request->is_sum == 1) {
                $key->evap_product_price = round($key->evap_product_price * (1+($request->amount/100)));
                $key->cond_product_price = round($key->cond_product_price * (1+($request->amount/100)));
            } else {
                $key->evap_product_price = round(($key->evap_product_price * 100) / (100+($request->amount)));
                $key->cond_product_price = round(($key->cond_product_price * 100) / (100+($request->amount)));
            }

            $new_amount = $new_amount + ($key->evap_product_price + $key->cond_product_price);
            $key->save();
        }


        return ['new_amount' => $new_amount, 'old_amount' => $old_amount];
    }

    public function productSetAdjust(Request $request) {

        // GRUPO
        if ($request->type == 1) {

            $data = SetProduct::SearchProductPerGroup($request->price_group)->where('is_active', 1)->get();

        // FRIO
        } else if ($request->type == 2) {

            $data = SetProduct::where('is_qf', 0)->where('is_active', 1)->get();

        // QUENTE FRIO
        } else if ($request->type == 3) {

            $data = SetProduct::where('is_qf', 1)->where('is_active', 1)->get();

        // TODOS
        } else if ($request->type == 4) {

            $data = SetProduct::where('is_active', 1)->get();

        }

        $return_values = $this->productApplyAdjust($request, $data);
        $history = new SetProductAdjust;
        $history->r_code = $request->session()->get('r_code');
        $history->type = $request->type;
        $history->factor = $request->amount;
        $history->is_sum = $request->is_sum == 1 ? 1 : 0;
        $history->old_amount = $return_values['old_amount'];
        $history->new_amount = $return_values['new_amount'];
        $history->save();

        $settings = Settings::where('command', 'version_table_price')->first();

        $settings->value = number_format($settings->value, 0) + 1.00;
        $settings->save();

        $request->session()->put('success', 'Reajuste de valores, foram realizados com sucesso!');
        return redirect()->back();
    }

    public function clientConditionTablePrice(Request $request) {

        $salesman_table_price = SalesmanTablePrice::with('salesman')->orderBy('id', 'DESC');
        $settings = Settings::where('command', 'version_table_price')->first();

        $array_input = collect([
            'status',
            'mtp',
            'salesman',
            'code',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."mtp"){
                    if ($valor_filtro == 1)
                        $salesman_table_price->where('manual_table_price', 1);
                    else
                        $salesman_table_price->where('manual_table_price', 0);
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
                    if ($valor_filtro == 1)
                    $salesman_table_price->where('version', '>=', $valor_filtro);
                    else
                    $salesman_table_price->where('version', '<', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $salesman_table_price->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."salesman"){
                    $salesman_table_price->SalesmanFilter($valor_filtro);
                }

            }
        }

        return view('gree_commercial.client.conditions.tablePrice', [
            'salesman_table_price' => $salesman_table_price->paginate(10),
            'version' => $settings->value,
        ]);
    }

    public function clientConditionTablePriceEditDelete(Request $request, $id) {

        $set_product = SalesmanTablePrice::find($id);

        if ($set_product) {

            SalesmanTablePrice::where('id', $id)->delete();

            $request->session()->put('success', 'Tabela de preço foi deletada com sucesso!');
            return redirect()->back();

        } else {

            $request->session()->put('error', 'Tabela de preço não foi encontrada no banco de dados.');
            return redirect()->back();
        }

    }

    public function clientConditionTablePriceEdit(Request $request, $id) {

		$date_choose = $request->date ? date($request->date.'-01-01') : date('Y-01-01');
        $months = $this->setSessionDatesAvaibles($date_choose, true);

        $fields = OrderFieldTablePrice::all();
		
		$products = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $rules = OrderTablePriceRules::all()->toJson();

        $table = '';
        if ($id != 0) {
            $table = SalesmanTablePrice::with('salesman', 'client', 'set_product_price_fixed')->where('id', $id)->first();
        }

        $date = date('y-n-01');

        $month = [1 => 'janeiro',2 => 'Fevereiro',3 => 'Março', 4 =>'Abril',5 =>'Maio', 6 =>'Junho', 7 =>'julho', 8 =>'Agosto', 9 =>'Setembro', 10 =>'Outubro', 11 =>'Novembro', 12 =>'Dezembro',];

        return view('gree_commercial.client.conditions.tablePriceEdit', [
            'products' => $products,
            'fields' => $fields,
            'rules' => $rules,
            'table' => $table,
            'id' => $id,
            'months' => $months,
            'month' => $month,
        ]);
    }

    public function clientConditionTablePriceExport(Request $request, $id) {

        // lipando os buffs do PHP
        if (ob_get_contents()){
            ob_end_clean();
        }

        $fields = OrderFieldTablePrice::all();
        $rules = OrderTablePriceRules::get();
        $table = SalesmanTablePrice::with('salesman', 'client')
            ->where('id', $id)
            ->first();

        if (!$table) {

            return redirect()->back()->with('error', 'Não foi possível encontrar a tabela que deseja exportar.');
        }

        $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

        $months = $this->setSessionDatesAvaibles();
        $products = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $pattern_second = [
            'view' => 'gree_commercial.exports.table_price_sheet_products',
            'sheet_title' => 'Proposta cliente',
            'months' => $months,
            'products' => $products,
            'table' => commercialTablePriceConvertValue($table),
            'applyPrice' => $applyPrice,
        ];

        $pattern = [
            'view' => 'gree_commercial.exports.table_price',
            'sheet_title' => 'Tabela de preço Gree',
            'months' => $months,
            'products' => $products,
            'table' => commercialTablePriceConvertValue($table),
            'applyPrice' => $applyPrice,
            'sheets' => [
                $pattern_second
            ]
        ];

        return Excel::download(new DefaultHtmlExport($pattern), 'CommercialTablePriceExport-'. date('Y-m-d') .'.xlsx');
    }

    public function clientConditionTablePriceEdit_do(Request $request) {

        if ($request->id == 0) {

            $table = new SalesmanTablePrice;

        } else {

            $table = SalesmanTablePrice::find($request->id);

            if (!$table) {

                return redirect()->back()->with('error', 'Tabela não existe no banco de dados.');
            }
        }

        if ($request->salesman_id)
            $table->salesman_id = $request->salesman_id;

        // Gen code
        if ($request->id == 0) {

            $last_code = '';

            $code = SalesmanTablePrice::orderBy('id', 'DESC')->first();
            if ($code) {
                $last = $code->id + 1;
                $last_code = 'CTP-'. $last;
            } else {
                $last_code = 'CTP-1';
            }

            $table->code = $last_code;
        }

        if ($request->type_client == null) {
			$table->type_client = 0;
		} else {
			$table->type_client = $request->type_client;
		}
		
		if ($request->descont_extra == null) {
			$table->descont_extra = 0;
		} else {
			$table->descont_extra = $request->descont_extra;
		}
        	
        if ($request->charge == null) {
			$table->charge = 0;
		} else {
			$table->charge = $request->charge;
		}
		
		if ($request->contract_vpc == null) {
			$table->contract_vpc = 0;
		} else {
			$table->contract_vpc = $request->contract_vpc;
		}
		
		if ($request->average_term == null) {
			$table->average_term = 0;
		} else {
			$table->average_term = $request->average_term;
		}
		
		if ($request->pis_confis == null) {
			$table->pis_confis = 0;
		} else {
			$table->pis_confis = $request->pis_confis;
		}
		
		if ($request->cif_fob == null) {
			$table->cif_fob = 0;
		} else {
			$table->cif_fob = $request->cif_fob;
		}
		
		if ($request->icms == null) {
			$table->icms = 0;
		} else {
			$table->icms = $request->icms;
		}
		
		if ($request->adjust_commercial == null) {
			$table->adjust_commercial = 0;
		} else {
			$table->adjust_commercial = $request->adjust_commercial;
		}
		
		if ($request->is_suframa == null) {
			$table->is_suframa = 0;
		} else {
			$table->is_suframa = $request->is_suframa;
		}

        $table->manual_table_price = $request->manual_table_price ? 1 : 0;

        $table->is_programmed = $request->is_programmed ? 1 : 0;
		
		$table->is_fixed_price = $request->is_fixed_price ? 1 : 0;

        $settings = Settings::where('command', 'version_table_price')->first();
        $table->version = $settings->value;
        $table->name = $request->name;
		$table->date_condition = $request->date_condition.'-01';
        $table->description_condition = $request->description_condition;
		
        $table->save();
		
		if ($request->is_fixed_price == 1) {
			foreach($request->products as $id => $price) {
				$add = SetProductPriceFixed::where('salesman_table_price_id', $table->id)->where('set_product_id', $id)->first();
				if (!$add)
					$add = new SetProductPriceFixed;
				
				$add->salesman_table_price_id = $table->id;
				$add->set_product_id = $id;
				$add->price = $price ? $price : 0.00;
				$add->save();
			}
		}

        return redirect('/commercial/client/conditions/table')->with('success', 'Tabela de preço, foi criada com sucesso.');

    }

    public function clientConditionTablePriceTemplace(Request $request, $action) {

        if ($action == 1) {

            $template = new SalesmanTablePriceTemplate;
            if ($request->salesman_id)
            $template->salesman_id = $request->salesman_id;

            $template->name = $request->template;
            if ($request->type_client)
            $template->type_client = $request->type_client;
            if ($request->descont_extra)
            $template->descont_extra = $request->descont_extra;
            if ($request->charge)
            $template->charge = $request->charge;
            if ($request->contract_vpc)
            $template->contract_vpc = $request->contract_vpc;
            if ($request->average_term)
            $template->average_term = $request->average_term;
            if ($request->pis_confis)
            $template->pis_confis = $request->pis_confis;
            if ($request->cif_fob)
                $template->cif_fob = $request->cif_fob;
            if ($request->icms)
            $template->icms = $request->icms;
            if ($request->adjust_commercial)
            $template->adjust_commercial = $request->adjust_commercial;
            if ($request->is_suframa)
            $template->is_suframa = $request->is_suframa;
            $template->save();

            $request->session()->put('success', 'Template da tabela de preço, foi criada com sucesso.');
            return redirect()->back();

        } else if ($action == 2) {

            $template = SalesmanTablePriceTemplate::find($request->template_id);
            if ($template) {

                if ($request->salesman_id)
                $template->salesman_id = $request->salesman_id;

                $template->name = $request->template;
                if ($request->type_client)
                $template->type_client = $request->type_client;
                if ($request->descont_extra)
                $template->descont_extra = $request->descont_extra;
                if ($request->charge)
                $template->charge = $request->charge;
                if ($request->contract_vpc)
                $template->contract_vpc = $request->contract_vpc;
                if ($request->average_term)
                $template->average_term = $request->average_term;
                if ($request->pis_confis)
                $template->pis_confis = $request->pis_confis;
                if ($request->cif_fob)
                    $template->cif_fob = $request->cif_fob;
                if ($request->icms)
                $template->icms = $request->icms;
                if ($request->adjust_commercial)
                $template->adjust_commercial = $request->adjust_commercial;
                if ($request->is_suframa)
                $template->is_suframa = $request->is_suframa;
                $template->save();

                $request->session()->put('success', 'Template da tabela de preço, foi atualizada com sucesso.');
                return redirect()->back();
            } else {

                $request->session()->put('error', 'Template da tabela de preço, não foi encontrada.');
                return redirect()->back();
            }

        } else if ($action == 3) {

            $template = SalesmanTablePriceTemplate::find($request->template_id);
            if ($template) {

                SalesmanTablePriceTemplate::where('id', $request->template_id)->delete();

                $request->session()->put('success', 'Template da tabela de preço, foi excluida com sucesso.');
                return redirect()->back();
            } else {

                $request->session()->put('error', 'Template da tabela de preço, não foi encontrada.');
                return redirect()->back();
            }
        }

        return redirect()->back();

    }

    public function clientConditionTablePriceRules(Request $request) {

        $rules_static = OrderTablePriceRules::with('OrderFieldTablePrice')->where('is_static', 1)->get();

        $rules_custom = OrderTablePriceRules::with('OrderFieldTablePrice')->where('is_static', 0);

        $fields = OrderFieldTablePrice::all()->toJson();

        if ($request->field)
        $rules_custom->FieldPriceFilter($request);

        return view('gree_commercial.client.conditions.priceRules', [
            'rules_static' => $rules_static,
            'rules_custom' => $rules_custom->paginate(10),
            'fields' => $fields,
        ]);
    }

    public function clientConditionTablePriceRules_do(Request $request) {

        if ($request->id == 0) {

            $rule = new OrderTablePriceRules;
        } else {

            $rule = OrderTablePriceRules::find($request->id);

            if (!$rule) {

                $request->session()->put('error', 'A regra não foi encontrada no banco de dados.');
                return redirect()->back();
            }
        }

        $rule->name = $request->name;
        //$rule->field_id = $request->field_selected;
        $rule->logic = 1+($request->multiplay/100);
        $rule->save();

        $request->session()->put('success', 'A regra foi atualizada com sucesso!');
        return redirect()->back();
    }

    public function clientConditionTablePriceRulesDelete(Request $request) {

        $rule = OrderTablePriceRules::find($request->id);

        if (!$rule) {

            $request->session()->put('error', 'A regra não foi encontrada no banco de dados.');
            return redirect()->back();
        }

        OrderTablePriceRules::where('id', $request->id)->delete();

        $request->session()->put('success', 'A regra foi deletada com sucesso!');
        return redirect()->back();
    }

    public function orderAvaibleMonth(Request $request) {

        $months = OrderAvaibleMonth::orderBy('id', 'DESC');

        if ($request->get('filter_year') and $request->get('filter_month')) {
            $request->merge(
                [
                    'filter_date' => date('Y-n-01', strtotime($request->filter_year . '-' . $request->filter_month . '-01'))
                ]);
        }

        $array_input = collect([
            'filter_date',
            'filter_type_apply',
            'filter_group_id_apply',
        ]);

        $array_input = putSession($request, $array_input, 'f_');
        $filter_session = getSessionFilters('f_');

        if($filter_session[0]->isNotEmpty()){
            foreach ($filter_session[0] as $name_filter => $value_filter) {

                if($name_filter == $filter_session[1]."filter_date"){
                    $months->where('date', date('Y-n-01', strtotime($value_filter)));
                }
                if($name_filter == $filter_session[1]."filter_type_apply"){
                    $months->where('type_apply', $value_filter);
                }
                if($name_filter == $filter_session[1]."filter_group_id_apply"){
                    $months->where('group_id_apply', $value_filter);
                }
            }
        }

        $month = [1 => 'janeiro',2 => 'Fevereiro',3 => 'Março', 4 =>'Abril',5 =>'Maio', 6 =>'Junho', 7 =>'julho', 8 =>'Agosto', 9 =>'Setembro', 10 =>'Outubro', 11 =>'Novembro', 12 =>'Dezembro',];

        $groups = SetProductGroup::where('is_active', 1)->get();
		$setProducts = SetProduct::where('is_active', 1)->get();
        return view('gree_commercial.orderSale.orderAdjust', [
            'months' => $months->paginate(10),
            'groups' => $groups,
			'setProducts' => $setProducts,
            'month' => $month,
        ]);
    }

    public function orderAvaibleMonth_do(Request $request) {

        if ($request->id == 0)
            $r_month = new OrderAvaibleMonth;
        else
            $r_month = OrderAvaibleMonth::find($request->id);

        /*if ($request->id == 0 and OrderAvaibleMonth::orderBy('id', 'desc')->where('type_apply', $request->type_apply)->where('date', date('Y-n-01', strtotime($request->year .'-'. $request->month .'-01')))->count() > 0)
            return redirect()->back()->with('error', 'Esse tipo de reajuste já foi cadastrado para esse mês e ano.');*/

        $r_month->r_code = $request->session()->get('r_code');
        $r_month->date = date('Y-n-01', strtotime($request->year .'-'. $request->month .'-01'));
        $r_month->type_apply = $request->type_apply;
        $r_month->group_id_apply = $request->group_id_apply == null ? 0 : $request->group_id_apply;
		if ($request->product_id and $request->type_apply)
			$r_month->p_ids = implode(',', $request->product_id);
		else
			$r_month->p_ids = null;
		

        $r_month->factor = $request->factor;
        $r_month->save();

        return redirect()->back()->with('success', 'Foi realizado o cadastro do ajuste mensal no sistema.');

    }

    public function orderAvaibleMonthDelete(Request $request, $id) {

        $r_month = OrderAvaibleMonth::find($id);

        if ($r_month) {

            OrderAvaibleMonth::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Foi deletado o cadastro do ajuste mensal no sistema.');
        } else {
            return redirect()->back()->with('error', 'Não foi possível encontrar o ajuste mensal desejado para excluir.');
        }

    }

    public function clientPrintAnalyze(Request $request, $id) {

        $client = new ManagerClient(null, $id);

        if ($client->mng_model) {

            $header = DB::connection('commercial')
                ->table('settings')
                ->where('type', 1)
                ->get();

        } else {

            return 'Não é possível visualizar a folha de impressão! Cliente não existe.';
            //return  redirect()->back()->with('error', 'Não é possível visualizar a folha de impressão! Cliente não existe.');
        }

        $version = DB::connection('commercial')->table('client_version')->where('client_id', $id)->orderBy('id', 'DESC')->get();

        if ($version->count() == 1) {

            return $version->first()->view;
        }

        $json = $version->first()->inputs;

        if ($version->first()->version == 2 or $version->first()->version == 1) {
            $data = Client::with(
                ['client_peoples_contact' => function($q) {
                    $q->withTrashed();
                },
                    'client_account_bank' => function($q) {
                        $q->withTrashed();
                    },
                    'client_main_clients' => function($q) {
                        $q->withTrashed();
                    },
                    'client_main_suppliers' => function($q) {
                        $q->withTrashed();
                    },
                    'client_on_group' => function($q) {
                        $q->withTrashed();
                    },
                    'client_on_product_sales' => function($q) {
                        $q->withTrashed();
                    },
                    'client_owner_and_partner' => function($q) {
                        $q->withTrashed();
                    },
                    'client_group',
                    'salesman',
                    'client_documents']
            )->where('id', $id)->first()->toJson();
        } else {
            $data = $version->splice(1)->first()->inputs;
        }

        // Diferença da tabela cliente
        $arr_client_diff = array_diff_assoc($this->splitArray($json, 1), $this->splitArray($data, 1));

        // Diferença das relações
        $arr_relation_diff = [];
        $path = json_decode($data, true);

        foreach ($this->splitArray($json, 2) as $key => $value) {

            foreach ($value as $index => $cvalue) {

                if  (!isset($path[$key][$index]))
                    $isHasOne = true;
                elseif ($path[$key][$index] == 1)
                    $isHasOne = true;
                else
                    $isHasOne = false;

                if (!$isHasOne) {
                    if (count($value) != count($path[$key])) {
                        if (!in_array($key, $arr_relation_diff)) {
                            $arr_relation_diff[] = $key;
                            continue;
                        }
                    } else {
                        $diff = array_diff_assoc($this->splitArray(json_encode($cvalue), 1), $this->splitArray(json_encode($path[$key][$index]), 1));
                    }
                } else {
                    $diff = array_diff_assoc($this->splitArray(json_encode($value), 1), $this->splitArray(json_encode($path[$key]), 1));
                }


                if (count($diff) > 0)
                    if (!in_array($key, $arr_relation_diff))
                        $arr_relation_diff[] = $key;

            }
        }

        $client = collect(json_decode($json, true));
        $header = collect(json_decode($header, true));
        return view('gree_commercial.client.printApprov', [
            'header' => $header,
            'client' => $client,
            'arr_client_diff' => $arr_client_diff,
            'arr_relation_diff' => $arr_relation_diff
        ]);
    }

    public function clientPrintView(Request $request, $id) {

        $client = new ManagerClient(null, $id);

        if ($client->mng_model) {

            if ($client->mng_model->has_analyze == 1) {
                $version = ClientVersion::where('client_id', $id)->orderBy('id', 'DESC')->get();
                if (count($version) > 0) {

                    if ($client->mng_model->has_analyze == 1 and $version->count() > 1)
                        return $version->splice(1)->first()->view;
                    else
                        return $version->first()->view;
                }
            }

            $header = DB::connection('commercial')
                ->table('settings')
                ->where('type', 1)
                ->get();

        } else {

            return 'Não é possível visualizar a folha de impressão! Cliente não existe.';
            //return  redirect()->back()->with('error', 'Não é possível visualizar a folha de impressão! Cliente não existe.');
        }

        return view('gree_commercial.client.print', [
            'header' => $header,
            'client' => $client->mng_model,
        ]);
    }

    public function clientPrintVersionView(Request $request, $id, $ver) {

        $version = ClientVersion::where('client_id', $id)->where('version', $ver)->orderBy('id', 'DESC')->withTrashed()->first();
        if ($version) {

            return $version->view;
        } else {
            return 'Não foi possível encontrar a versão...';
        }
    }

    public function settings(Request $request) {

        $settings = Settings::all();
        $permissions = config('gree.permmisions_commercial_scheme');
        $usersOnPermissions = App\Model\Commercial\UserOnPermissions::with('user')->get();

        return view('gree_commercial.settings', [
            'settings' => $settings,
            'permissions' => $permissions,
            'usersOnPermissions' => $usersOnPermissions,
        ]);
    }

    public function settings_do(Request $request) {

        $setting = Settings::all();

        foreach ($setting as $row) {

            foreach ($request->request as $key => $value) {

                if ($row->command == $key)
                    if (is_array($value))
                        $row->value = implode(',', $value);
                    else
                        $row->value = $value;
            }

            $row->save();
        }

        return redirect()->back()->with('success', 'Foi realizado atualização das configurações');
    }

    public function permissions_do(Request $request) {

        App\Model\Commercial\UserOnPermissions::where('id', $request->id)->delete();
        $scheme = new App\Model\Commercial\UserOnPermissions;

        // limpar o request
        $arr_request = $request->all();
        array_splice($arr_request, 0, 2);

        $scheme->r_code = $request->r_code;
        $scheme->scheme = json_encode($arr_request);
        $scheme->save();

        return redirect()->back()->with('success', 'Permissões do usuário foram atualizadas.');
    }

    public function permissions_delete(Request $request, $id) {

        App\Model\Commercial\UserOnPermissions::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Permissões do usuário deletada com sucesso!');
    }

    public function programationStatus(Request $request, $id) {
        $programation = Programation::with('programationMonth.orderSales', 'programationMacro')->find($id);

        if (!$programation)
            return redirect()->back()->with('error', 'A programação não foi encontrada.');

        $programation->is_cancelled = 1;
		$programation->cancel_reason = $request->reason;
        $programation->cancel_r_code = $request->session()->get('r_code');
		$programation->has_analyze = 0;
        $programation->save();

        foreach ($programation->programationMonth as $orders) {
            foreach ($orders->orderSales as $order) {
                if ($order->is_invoice == 0) {
                    $order->is_cancelled = 0;
                    $order->save();
                }
            }
        }
		
		$programation->programationMacro()->delete();

        return redirect()->back()->with('success', 'A programação foi cancelada com sucesso.');

    }

    public function programationList(Request $request) {

        $array_input = collect([
            'code',
            'manager',
            'subordinates',
            'client',
            'start_date',
			'is_open',
            'is_analyze',
			'status'
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        $managers = DB::connection('commercial')
            ->table('salesman')
            ->where('is_direction', 2)
            ->get();

        $subordinates = Salesman::all();

		$programations = Programation::with('client.client_managers', 'programationVersion', 'programationMonth.orderSales.salesman');

        $programations->orderBy('id', 'DESC');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code"){
                    $programations->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."manager"){
                    $programations->ShowOnlyManager($valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."subordinates"){
                    $programations->where('request_salesman_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $programations->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $programations->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                    });
                }
				if($nome_filtro == $filtros_sessao[1]."is_open"){
                    $programations->whereHas('programationMacro', function ($q) {
                        $q->where('quantity', '>', 0)->whereHas('programation', function ($q2) {
                            $q2->where('is_cancelled', 0);
                        });
                    });
                }
				if($nome_filtro == $filtros_sessao[1]."is_analyze"){
                    $programations->where('has_analyze', 1);
                }
				if($nome_filtro == $filtros_sessao[1]."status"){
                    
                    if($valor_filtro == 1) {
                        $programations->where('is_cancelled', 1);
                    }
                    else if($valor_filtro == 2) {
                        $programations->whereHas('programationVersion', function ($q) {
                            $q->where('is_approv', 1);
                        })->where('is_cancelled', 0);
                    }
                    else if($valor_filtro == 3) {
                        $programations->whereHas('programationVersion', function ($q) {
                            $q->where('is_reprov', 1);
                        });
                    }    
                    else if($valor_filtro == 4) {
                        $programations->where('has_analyze', 1);
                    }
                }
            }
        }

        return view('gree_commercial.programation.list', [
            'programations' => $programations->paginate(10),
            'managers' => $managers,
            'subordinates' => $subordinates,
        ]);
    }
	
	
	public function programationMacro(Request $request) {

        if ($request->client_id) {
			$programation_macro = ProgramationMacro::with('programation')->whereHas('programation', function($q) use ($request) {
				$q->where('client_id', $request->client_id);
			});
        } else {
            $programation_macro = ProgramationMacro::with('programation');
        }
		
		if ($request->salesman_id) {
			$programation_macro->where('salesman_id', $request->salesman_id);
		}

        if($request->start_date){
            $date_begin = explode("-", $request->start_date);
            $programation_macro->whereYear('yearmonth', '>=', $date_begin[0]);
            $programation_macro->whereMonth('yearmonth', '>=', $date_begin[1]);
        }
        if($request->end_date){
            $date_final = explode("-", $request->end_date);
            $programation_macro->whereYear('yearmonth', '<=', $date_final[0]);
            $programation_macro->whereMonth('yearmonth', '<=', $date_final[1]);
        }

        $pmacro = $programation_macro->get();
        $months = $pmacro->unique('yearmonth')->pluck('yearmonth');
        
        $category = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }, 'setProductOnGroup.productAirEvap'])->withTrashed()->orderBy('position', 'ASC')->get();
		
		if($request->export == 1) {

            if ($request->client_id) {
                $view = 'gree_commercial.programation.tableMacroClient';
            } else {
                $view = 'gree_commercial.programation.tableMacro';
            }    

            $pattern = [
                'view' => $view,
                'sheet_title' => 'Total',
                'months' => $months,
                'cat_uniq' => $category,
                'category' => $category->toArray()
            ];

            if ($months->count() > 0) {
                return Excel::download(new DefaultHtmlExport($pattern), 'ProgramationMacroExport-'. date('Y-m-d') .'.xlsx');
            } else {
                return redirect()->back()->with('error', 'NÃO EXISTE PROGRAMAÇÃO');
            }    
        }   

        return view('gree_commercial.programation.programation_macro', [
            'months' => $months,
            'cat_uniq' => $category,
            'category' => $category->toArray()
        ]);
    }
	
	
	public function programationMacroClientsAjax(Request $request) {

        try {
			
            if((int)$request->year && (int)$request->month) {

				$query = "SELECT client.company_name, client.identity, client.code, SUM(programation_macro.total) as total, SUM(programation_macro.quantity) as quantity
				FROM `client` 
					INNER JOIN programation ON programation.client_id = client.id AND programation.is_cancelled = 0
					INNER JOIN programation_macro ON programation.id = programation_macro.programation_id
					WHERE financy_status = 3
					AND YEAR(programation_macro.yearmonth) = $request->year 
					AND MONTH(programation_macro.yearmonth) = $request->month   
					AND EXISTS (
						SELECT 1 FROM programation 
						WHERE programation.client_id = client.id
						AND EXISTS (SELECT 1 FROM programation_macro 
									WHERE programation_macro.programation_id = programation.id)
					)
					GROUP BY client.identity";

                $macro_clients = DB::connection('commercial')->select(DB::raw($query));

                return response()->json([
                    'success' => true,
                    'macro_clients' => $macro_clients
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Mês / Ano não foram passados corretamente'
                ], 400);
            }
        
        } catch (\Exception $e) {    

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }    
    }
	
	public function programationView(Request $request, $id) {

        $programation = Programation::with('client.manager_region', 'programationVersionAll', 'programationVersion')
            ->where('id', $id)
            ->first();

        if (!$programation)
            return redirect()->back()->with('error', 'A programação que está tentando acessar, não está disponível!');

        if ($request->version)
            if ($request->version > $programation->programationVersion()->first()->version)
                return redirect()->back()->with('error', 'A versão que deseja visualizar, não está disponível');

        $client = json_decode($programation->json_client);

        $alert = App\Model\Commercial\Settings::where('command', 'programation_alert')->first();
        $months = json_decode($programation->json_months, true);
        $category = collect(json_decode($programation->json_categories_products, true));
        if ($request->version) {
            $version = $programation->programationVersion($request->version)->first();
            $tables = $programation->programationMonth($request->version)->get();
        } else {
            $version = $programation->programationVersion()->first();
            $tables = $programation->programationMonth($version->version)->orderBy('id', 'DESC')->get();
        }

        $version_arr = json_decode($version->json_programation, true);

        $months_arr = [];
        foreach ($version_arr as $idx => $vsion_val) {
            $months_arr[] = $idx;
        }

        $in_analyze = 0;
        if ($request->version) {
            if ($request->version == $programation->programationVersion->version and $programation->programationVersion->is_approv == 0 and $programation->programationVersion->is_reprov == 0 and $programation->has_analyze == 1)
                //if ($programation->request_salesman_id != \Session::get('salesman_data')->id)
                    $in_analyze = 1;
        } else {
            if ($programation->programationVersion->is_approv == 0 and $programation->programationVersion->is_reprov == 0 and $programation->has_analyze == 1)
                //if ($programation->request_salesman_id != \Session::get('salesman_data')->id)
                    $in_analyze = 1;
        }

        return view('gree_commercial.programation.view', [
            'in_analyze' => $in_analyze,
            'client' => $client,
            'months' => $months,
            'months_arr' => $months_arr,
            'category' => $category,
            'version_arr' => $version_arr,
            'version' => $version,
            'tables' => $tables,
            'programation' => $programation,
            'alert' => $alert->value
        ]);
    }

    public function orderList(Request $request) {

        $array_input = collect([
            'code_order',
            'code_programation',
            'client',
            'start_date',
            'is_analyze',
			'subordinates',
			'region',
			'status'
        ]);

        $array_input = putSession($request, $array_input, 'order_');
        $filtros_sessao = getSessionFilters('order_');
		
        $clients = DB::connection('commercial')->table('client')->where('is_active', 1)->get();

        $order = OrderSales::with(['programationMonth.programation.client', 'orderSalesAttach'])
            ->where('is_programmed', 1)
            ->orderBy('id', 'DESC');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."code_programation"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->whereHas('programation', function($q1) use ($valor_filtro) {
                            $q1->where('code', 'like', '%'.$valor_filtro.'%');
                        });
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."is_analyze"){
                    $order->where('has_analyze', 1);
                }
				if($nome_filtro == $filtros_sessao[1]."subordinates"){
                    $order->where('request_salesman_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                    });
                }
				if($nome_filtro == $filtros_sessao[1]."region"){ 
                    $order->where('is_cancelled', 0)->where('is_reprov', 0);
                    $order->whereIn('client_state', config('gree.arr_region')[$valor_filtro])
                    ->orWhere(function ($query) use ($valor_filtro) {
                        $query->whereHas('orderDelivery', function ($q) use ($valor_filtro) {
                            $q->whereIn('state', config('gree.arr_region')[$valor_filtro]);
                        });
                    });
                }
				if($nome_filtro == $filtros_sessao[1]."status") {

                    if($valor_filtro == 1) {  
                        $order->where('is_cancelled', 1);
                    } 
                    elseif($valor_filtro == 2) { 
                        $order->where('is_approv', 1)->where('is_invoice', 0)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 3) { 
                        $order->where('is_invoice', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 4) {
                        $order->where('salesman_imdt_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 5) {
                        $order->where('commercial_is_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 6) {
                        $order->where('financy_reprov', 1)->where('is_cancelled', 0);
                    }    
                    elseif($valor_filtro == 7) {
                        $order->where('waiting_assign', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 8) {
                        $order->where('has_analyze', 1);
                    }  
                }
            }
        }

        $subordinates = Salesman::all();
        return view('gree_commercial.orderSale.programation.list', [
            'order' => $order->paginate(10),
            'clients' => $clients,
            'subordinates' => $subordinates,
        ]);
    }

    public function orderApprovList(Request $request) {

        $array_input = collect([
            'code_order',
            'code_programation',
            'subordinates',
            'client',
            'start_date',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        $order = OrderSales::with(
            'programationMonth.programation.client',
            'orderSalesAttach',
            'programationMonth.programation.salesman')
            ->where('has_analyze', 1)
            ->where('is_programmed', 1)
            ->where('waiting_assign', 0)
            ->where('salesman_imdt_approv', 1)
            ->where('salesman_imdt_reprov', 0)
            ->orderBy('id', 'DESC');

        if ($this->validPerm($request, 20, 9, 1)) {
            $order->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);

        } else if ($this->validPerm($request, 18, 8, 1)) {
            $order->where('commercial_is_approv', 1)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);
        } else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar essa página.');
		}

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."code_programation"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->whereHas('programation', function($q1) use ($valor_filtro) {
                            $q1->where('code', 'like', '%'.$valor_filtro.'%');
                        });
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->whereHas('programationMonth', function ($q) use ($valor_filtro) {
                        $q->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                    });
                }

            }
        }

        $subordinates = Salesman::all();
        $clients = DB::connection('commercial')->table('client')->where('is_active', 1)->get();

        return view('gree_commercial.orderSale.programation.listAnalyze', [
            'order' => $order->paginate(10),
            'clients' => $clients,
            'subordinates' => $subordinates
        ]);
    }

    public function orderAnalyze(Request $request, $id) {

        $query = OrderSales::with(
            'client',
            'salesman',
            'orderSalesAttach',
            'orderImdAnalyze',
            'orderCommercialAnalyze',
            'orderFinancyAnalyze',
			'programationMonth',
			'OrderProducts')
            ->where('is_programmed', 1)
            ->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 1)
            ->where('salesman_imdt_reprov', 0)
            ->where('id', $id);

        if ($this->validPerm($request, 20, 9, 1)) {
            $query->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);

        } else if ($this->validPerm($request, 18, 8, 1)) {
            $query->where('commercial_is_approv', 1)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);
        } else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar essa página.');
		}

        $order = $query->first();

        if (!$order)
            return redirect()->back()->with('error', 'Não foi possível encontrar o pedido.');

        $salesman_id = $order->programationMonth->programation->request_salesman_id;
        $salesman = '';
        $arr_imdt = [];
        // Montar array de gestores
        do {
            $data = Salesman::with('immediate_boss')->find($salesman_id);
            if ($data->immediate_boss->first()) {
                $salesman = $data->immediate_boss->first();
                $salesman_id = $salesman->id;
                if ($salesman->is_direction != 2)
                    $arr_imdt[] = $salesman;
            }
        } while ($salesman->is_direction != 2);

        $dir_commecrial = UserOnPermissions::with('user')->where('perm_id', 20)
            ->where('grade', 9)
            ->where('can_approv', 1)
            ->first();

        $dir_financy = UserOnPermissions::with('user')->where('perm_id', 18)
            ->where('grade', 8)
            ->where('can_approv', 1)
            ->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);

        return view('gree_commercial.orderSale.programation.analyze', [
            'order' => $order,
            'arr_imdt' => $arr_imdt,
            'dir_commecrial' => $dir_commecrial,
            'dir_financy' => $dir_financy,
        ]);
    }

    public function orderPrintView(Request $request, $id) {

        $order = OrderSales::with(['programationMonth.programation.client', 'orderSalesAttach'])
            ->where('id', $id)
            ->where('is_programmed', 1)
            ->first();


        if (!$order)
            return redirect()->back()->with('error', 'Você não está autorizado para ver essa solicitação!');

        return $order->view;
    }
	
	public function orderPrintViewServer(Request $request, $id) {

		if ($request->getClientIp() != "52.201.83.221")
				return abort(404, 'Página não encontrada');		

        $order = OrderSales::with(['programationMonth.programation.client', 'orderSalesAttach'])
            ->where('id', $id)
            ->where('is_programmed', 1)
            ->first();


        if (!$order)
            return redirect()->back()->with('error', 'Você não está autorizado para ver essa solicitação!');

        return $order->view;
    }

    public function orderConfirmedNew(Request $request) {

        if ($request->client_id and $request->monthyear and $request->table_id) {

            $month = $request->monthyear;
            $table = json_decode(SalesmanTablePrice::with('set_product_price_fixed')->find($request->table_id)->toJson());
            $client = json_decode(Client::find($request->client_id)->toJson());
            $categories = SetProductGroup::with(['setProductOnGroup'=>function($q){
                $q->orderBy('position', 'ASC');
            }, 'setProductOnGroup.productAirEvap'])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

            $fields = OrderFieldTablePrice::all();
            $rules = OrderTablePriceRules::get();

            $applyPrice = new ApplyConditionPriceBase($fields, $rules, $table);

            $arr_month = [];
            $arr_cat_id = [];
            foreach ($categories as $key) {
                $cat = [];
                $cat['id'] = $key->id;
                $arr_cat_id[] = $key->id;
                $arr_prod = [];
                foreach ($key->setProductOnGroup as $prod) {
                    $prods = [];
                    $prods['id'] = $prod->id;
					if ($table->is_fixed_price == 1) {
						$collect_price_fixed = collect($table->set_product_price_fixed);
						$fixed_price = $collect_price_fixed
							->where('salesman_table_price_id', $table->id)
							->where('set_product_id', $prod->id)
							->first();
						if ($fixed_price)
							$prods['price'] = $fixed_price->price;
						else
							$prods['price'] = 0.00;
					} else {
						$prods['price'] = $applyPrice->calcPrice($prod->price_base, $prod, $request->monthyear, FALSE);
					}
                    $prods['qtd'] = 0;
                    $prods['max'] = 0;
                    $prods['is_custom'] = 0;
					$prods['calc_cubage'] = $prod->calc_cubage;
                    $prods['descoint'] = 0;

                    array_push($arr_prod, $prods);
                }

                $cat['products'] = $arr_prod;
                array_push($arr_month, $cat);
            }

            $all_cat_prod = $categories->whereIn('id', $arr_cat_id);
            $type_client = [
                1 => 'Varejo Regional',
                2 => 'Varejo Regional (Abertura)',
                3 => 'Especializado Regional',
                4 => 'Especializado Nacional',
                5 => 'Refrigerista Nacional',
                6 => 'Varejo Nacional',
                7 => 'E-commerce',
                8 => 'VIP'
            ];

            $tax_regime = [
                15 => 'Lurco Real (CNPJ)',
                16 => 'Lucro Presumido (CNPJ)',
                17 => 'Consumidor (CPF)',
                24 => 'Simplificado (CNPJ)',
                25 => 'Outros Clientes (CNPJ)'
            ];

            $clients = DB::connection('commercial')->table('client')->where('is_active', 1)->get();
            $tables = DB::connection('commercial')->table('salesman_table_price')->where('is_programmed', 0)->get();
            $months = $this->setSessionDatesAvaibles();
        } else {
            $month = [];
            $arr_month = [];
            $table = "";
            $type_client = [];
            $tax_regime = [];
            $all_cat_prod = [];
            $client = [];

            $clients = DB::connection('commercial')->table('client')->where('is_active', 1)->get();
            $tables = DB::connection('commercial')->table('salesman_table_price')->where('is_programmed', 0)->where('manual_table_price', 1)->get();
            $months = $this->setSessionDatesAvaibles();

        }
		
		if (!$months->count())
			return redirect()->back()->with('error', 'Não há meses disponíveis para criação do pedido, entre em contato com administração para aplicação do reajuste.');

        return view('gree_commercial.orderSale.confirmed.new', [
            'month' => $month,
            'arr_month' => $arr_month,
            'all_cat_prod' => $all_cat_prod,
            'table' => $table,
            'type_client' => $type_client,
            'tax_regime' => $tax_regime,
            'client' => $client,
            'clients' => $clients,
            'tables' => $tables,
            'months' => $months,
        ]);
    }

    public function orderConfirmedSaveNew(Request $request) {

        $client = Client::with('client_peoples_contact')
            ->where('id', $request->client_id)
            ->where('has_analyze', 0)
            ->first();

        if (!$client)
            return redirect()->back()->with('error', 'Cliente não foi encontrado na base de dados.');

        $table = SalesmanTablePrice::where('id', $request->table_id)
            ->where('is_programmed', 0)
            ->first();

        if (!$table)
            return redirect()->back()->with('error', 'Condição comercial não foi encontrada na base de dados.');

        $order = new OrderSales;
        if ($request->other_client) {
            $client_delivery = Client::find($request->other_client);
        } else {
            $client_delivery = $client;
        }
		
		// Verificar se o cliente pode criar pedido.
        if (!$client)
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente não existe.');

        if ($client->financy_status == 1)
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente encontra-se reprovado pelo financeiro.');
        elseif ($client->financy_status == 2 and $request->date_payment != '0')
            return redirect()->back()->with('error', 'Não foi possível criar o pedido, pois o cliente só está habilitado para pagamento à vista! Informe na data de pagamento o valor "0".');

        if ($request->type_order == 1)
            $order->code = getCodeModule('order_commercial', '', 1);
        elseif ($request->type_order == 2)
            $order->code = getCodeModule('order_commercial_vpc', '', 1);
        elseif ($request->type_order == 3)
            $order->code = getCodeModule('order_commercial_vdc', '', 1);
		elseif ($request->type_order == 5)
            $order->code = getCodeModule('order_commercial_dev', '', 1);
        else
            $order->code = getCodeModule('order_commercial_esp', '', 1);

		$order->type_order = $request->type_order;
        $order->control_client = $request->control_client;
        $order->observation = $request->observation;
        $order->code_client = $client->code;
        $order->type_client = $table->type_client;
		$order->client_vpc = $client->vpc;

        $order->contract_vpc = $table->contract_vpc;
        $order->average_term = $table->average_term;
        $order->cif_fob = $table->cif_fob;

        $order->type_payment = $request->type_payment;
        $order->form_payment = $request->form_payment;
        $order->name_transport = $request->transport_name;
		$order->date_payment = $request->date_payment;
        $order->commission = $request->commission;
		if ($request->vpc_view) {
			$order->vpc_view = $request->vpc_view;
		}

        $old_date = str_replace("/", "-", $request->date_invoice);
        $date_end = date('Y-m-d', strtotime($old_date));
        $order->date_invoice = $date_end;

        $order->client_company_name = $client->company_name;
        $order->client_shop = $client->fantasy_name;
        $order->client_address = $client->address;
        $order->client_city = $client->city;
        $order->client_district = $client->district;
        $order->client_phone = $request->client_phone;
        $order->client_state = $client->state;
        $order->client_especial_regime_icms_per_st = $client->state;
        $order->client_identity = $client->identity;
        $order->client_state_registration = $client->state_registration;
        $order->client_suframa_registration = $client->suframa_registration;

        if ($client->client_peoples_contact->count()) {
            if ($client->client_peoples_contact->where('type_contact', 1)->first()) {
                $buyer = $client->client_peoples_contact->where('type_contact', 1)->first();
                $order->client_phone = $buyer->phone;
                $order->client_peoples_contact_buyer_phone = $buyer->phone;
                $order->client_peoples_contact_buyer_contact = $buyer->email;
            }
            if ($client->client_peoples_contact->where('type_contact', 2)->first()) {
                $financy = $client->client_peoples_contact->where('type_contact', 2)->first();
                $order->client_email_financy_nfe = $financy->email;
            }

        }


        $order->client_id = $client->id;

        $categories = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }, 'setProductOnGroup.productAirEvap'])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $order->programation_month_id = 0;
        $order->json_table_price = $table->toJson();
        $order->json_categories_products = $categories->toJson();
        $order->is_programmed = 0;
        $order->yearmonth = $request->monthyear;
        $order->has_analyze = 0;
        $order->waiting_assign = 1;
        $order->request_salesman_id = $client->request_salesman_id;
        $order->programation_version = 1;
        $order->request_r_code = $request->session()->get('r_code');
		$order->adjust_month = OrderAvaibleMonth::where('date', date('Y-m-01 00:00:00'))->get()->toJson();

        $header = DB::connection('commercial')
            ->table('settings')
            ->where('type', 2)
            ->get()->toJson();

        $order->json_header = $header;
        $order->save();

        if ($table->cif_fob == 0) {
            $order_receiver = new OrderReceiver;
            $order_receiver->type_receiver = $request->receiver;
            $order_receiver->type_day_receiver = $request->day_receiver;
            if ($request->day_receiver == 2) {
                $order_receiver->monday_friday_hour_start = $request->hour_start_mon_fri.':00';
                $order_receiver->monday_friday_hour_end = $request->hour_end_mon_fri.':00';
                $order_receiver->saturday_hour_start = $request->hour_start_sat.':00';
                $order_receiver->saturday_hour_end = $request->hour_end_sat.':00';
            } else if ($request->day_receiver == 1) {
                $order_receiver->monday_friday_hour_start = $request->hour_start_mon_fri.':00';
                $order_receiver->monday_friday_hour_end = $request->hour_end_mon_fri.':00';
            }
            $order_receiver->apm_name = $request->apm_name;
            $order_receiver->apm_phone = $request->apm_phone;
            $order_receiver->apm_email = $request->apm_email;
            $order_receiver->transport = $request->discharge;
            if ($request->discharge == 2)
                $order_receiver->total = $request->price_charge;

            $order_receiver->order_sales_id = $order->id;
            $order_receiver->save();
        }

        if ($request->other_client) {
            $order_delivery = new OrderDelivery;
            $order_delivery->order_sales_id = $order->id;
            $order_delivery->address = $client_delivery->address;
            $order_delivery->city = $client_delivery->city;
            $order_delivery->district = $client_delivery->district;
            $order_delivery->state = $client_delivery->state;
            $order_delivery->zipcode = $client_delivery->zipcode;
            $order_delivery->phone = $request->client_phone;
            $order_delivery->state_registration = $client_delivery->state_registration;
            $order_delivery->identity = $client_delivery->identity;
            $order_delivery->save();
        }

        $json = json_decode($request->json_order, 2);

        foreach ($json as $key) {
            foreach ($key['products'] as $prod) {
                if ($prod['qtd'] > 0) {
                    $item = new OrderProducts;
                    $item->order_sales_id = $order->id;
                    $item->set_product_id = $prod['id'];
                    $item->category_id = $key['id'];
                    $item->price_unit = $prod['price'];
                    $item->quantity = $prod['qtd'];
                    $item->descoint = $prod['descoint'];
                    $item->total = $prod['price'];
                    $item->is_price_custom = $prod['is_custom'];
                    $item->save();
                }
            }
        }

        $order->view = $this->renderViewOrderConfirmed($order->id);
		$order->salesman_imdt_approv = 1;
        $order->manual_order_sales = 1;
        $order->request_r_code = $request->session()->get('r_code');
        $order->save();
		
		$salesman = Salesman::find($client->request_salesman_id);
		$salesman_name = $salesman ? $salesman->full_name : '';
		$settings = Settings::where('command', 'order_new_register')->first();
		if ($settings->value) {
			$arr = explode(',', $settings->value);

			foreach ($arr as $key) {

				$pattern = array(
					'title' => 'COMERCIAL - NOVO PEDIDO INTERNO: '. $order->code,
                    'description' => nl2br("Olá! Foi realizado a criação de um novo pedido de vendas na plataforma da GREE
                        <br><p><b>Client:</b> ". $client->company_name ."
						<br>Colaborador: ". $request->session()->get('first_name') ."
						<br>Representante: ". $salesman_name ."
                        <br><a href='". $request->root() ."/commercial/order/confirmed/all'>". $request->root() ."/commercial/order/confirmed/all</a></p>"),
					'template' => 'misc.DefaultExternal',
                    'subject' => 'Comercial - novo pedido',
					
				);

				SendMailJob::dispatch($pattern, $key);
			}
		}

        return redirect('/commercial/order/confirmed/all')->with('success', 'Seu pedido foi criado com sucesso! Faça a comprovação do pedido.');

    }

    public function orderConfirmedList(Request $request) {

        $array_input = collect([
            'code_order',
            'subordinates',
            'users',
            'client',
            'start_date',
            'is_analyze',
			'region',
			'chart_start_date',
			'status'
        ]);

        $array_input = putSession($request, $array_input, 'confirmed_');
        $filtros_sessao = getSessionFilters('confirmed_');
		
         $clients = DB::connection('commercial')->table('client')->where('is_active', 1)->get();

        $order = OrderSales::with(['client', 'orderSalesAttach', 'orderDelivery'])
            ->where('is_programmed', 0)
            ->orderBy('id', 'DESC');

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."subordinates"){
                    $order->where('request_salesman_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."users"){
                    $order->where('request_r_code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."is_analyze"){
                    $order->where('has_analyze', 1);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                }
				if($nome_filtro == $filtros_sessao[1]."region"){ 
                    $order->where('is_cancelled', 0)->where('is_reprov', 0);
                    $order->whereIn('client_state', config('gree.arr_region')[$valor_filtro])
                    ->orWhere(function ($query) use ($valor_filtro) {
                        $query->whereHas('orderDelivery', function ($q) use ($valor_filtro) {
                            $q->whereIn('state', config('gree.arr_region')[$valor_filtro]);
                        });
                    });    
                }
				if($nome_filtro == $filtros_sessao[1]."chart_start_date"){
                    $order->where('is_cancelled', 0)->where('is_reprov', 0);
                    $order->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                }
				if($nome_filtro == $filtros_sessao[1]."status") {

                    if($valor_filtro == 1) {  
                        $order->where('is_cancelled', 1);
                    } 
                    elseif($valor_filtro == 2) { 
                        $order->where('is_approv', 1)->where('is_invoice', 0)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 3) { 
                        $order->where('is_invoice', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 4) {
                        $order->where('salesman_imdt_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 5) {
                        $order->where('commercial_is_reprov', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 6) {
                        $order->where('financy_reprov', 1)->where('is_cancelled', 0);
                    }    
                    elseif($valor_filtro == 7) {
                        $order->where('waiting_assign', 1)->where('is_cancelled', 0);
                    }
                    elseif($valor_filtro == 8) {
                        $order->where('has_analyze', 1);
                    }  
                }
            }
        }

        $subordinates = Salesman::all();
        $users = Users::orderBy('first_name', 'ASC')->get();
        return view('gree_commercial.orderSale.confirmed.list', [
            'order' => $order->paginate(10),
            'clients' => $clients,
            'subordinates' => $subordinates,
            'users' => $users,
        ]);
    }

    public function orderConfirmedApprovList(Request $request) {

        $array_input = collect([
            'code_order',
            'subordinates',
            'users',
            'client',
            'start_date',
        ]);

        $array_input = putSession($request, $array_input);

        $filtros_sessao = getSessionFilters();

        $order = OrderSales::with(
            'client',
            'orderSalesAttach',
            'salesman')
            ->where('is_programmed', 0)
            ->where('waiting_assign', 0)
            ->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 1)
            ->where('salesman_imdt_reprov', 0)
            ->orderBy('id', 'DESC');

        if ($this->validPerm($request, 20, 9, 1)) {
            $order->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);

        } else if ($this->validPerm($request, 18, 8, 1)) {
            $order->where('commercial_is_approv', 1)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);
        } else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar essa página.');
		}

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {

                if($nome_filtro == $filtros_sessao[1]."subordinates"){
                    $order->where('request_salesman_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."users"){
                    $order->where('request_r_code', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order->where('code', 'like', '%'.$valor_filtro.'%');
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order->where('client_id', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $order->where('yearmonth', '=', date('Y-m-01', strtotime($valor_filtro)));
                }

            }
        }

        $subordinates = Salesman::all();
        $users = Users::orderBy('first_name', 'ASC')->get();
        $clients = DB::connection('commercial')->table('client')->where('is_active', 1)->get();

        return view('gree_commercial.orderSale.confirmed.listAnalyze', [
            'order' => $order->paginate(10),
            'clients' => $clients,
            'subordinates' => $subordinates,
            'users' => $users
        ]);
    }
	
	public function orderImport(Request $request) {
			
		if ($request->import == 1) {
			if (!$request->hasFile('attach'))
				return redirect()->back()->with('error', 'Você precisa informar o arquivo antes de enviar.');
				
			$theArray = Excel::toArray([], $request->attach);
			
			$rows = collect($theArray[0])->slice(1);
			$clean_rows = $this->clearRowsImportOrder($rows);
			$group_rows = $clean_rows->groupBy('0');
			
			try {
				foreach($group_rows as $c_code => $data) {
					$this->createOrderOfExcel($c_code, $data, $request->table, $request);
				}
			} catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
			return redirect()->back()->with('success', 'Pedidos importados e gerados com sucesso!');
		} else {

			return view('gree_commercial.orderSale.orderImport');
		}
    }
	
	private function createOrderOfExcel($c_code, $data, $table_id, $request) {

		$client = Client::with('client_peoples_contact')
            ->where('code', $c_code)
            ->orWhere('identity', $c_code)
            ->first();

        if (!$client) {
			throw new \Exception('Cliente: '. $c_code .' não foi encontrado na base de dados. Pedidos não foram gerados.');
		}

        $table = SalesmanTablePrice::with('set_product_price_fixed')->where('id', $table_id)
            ->first();

		if (!$table) {
           throw new \Exception('Tabela de preço não foi encontrado na base de dados. Pedidos não foram gerados.');
		}

        $order = new OrderSales;
        $client_delivery = $client;
		
        $order->code = getCodeModule('order_commercial', '', 1);
		$order->type_order = 1;
        $order->control_client = $data[0][3];
        $order->observation = $data[0][20];
        $order->code_client = $client->code;
        $order->type_client = $table->type_client;
		$order->client_vpc = $client->vpc;

        $order->contract_vpc = $table->contract_vpc;
        $order->average_term = $table->average_term;
        $order->cif_fob = $table->cif_fob;

        $order->type_payment = $data[0][4];
        $order->form_payment = $data[0][5];
        $order->name_transport = $data[0][6];
		$order->date_payment = $data[0][7];
        $order->commission = $client->commission;

        $old_date = str_replace("/", "-", $data[0][8]);
        $date_end = date('Y-m-d', strtotime($old_date));
        $order->date_invoice = $date_end;

        $order->client_company_name = $client->company_name;
        $order->client_shop = $client->fantasy_name;
        $order->client_address = $client->address;
        $order->client_city = $client->city;
        $order->client_district = $client->district;
        $order->client_state = $client->state;
        $order->client_especial_regime_icms_per_st = $client->state;
        $order->client_identity = $client->identity;
        $order->client_state_registration = $client->state_registration;
        $order->client_suframa_registration = $client->suframa_registration;

        if ($client->client_peoples_contact->count()) {
            if ($client->client_peoples_contact->where('type_contact', 1)->first()) {
                $buyer = $client->client_peoples_contact->where('type_contact', 1)->first();
                $order->client_phone = $buyer->phone;
                $order->client_peoples_contact_buyer_phone = $buyer->phone;
                $order->client_peoples_contact_buyer_contact = $buyer->email;
            }
            if ($client->client_peoples_contact->where('type_contact', 2)->first()) {
                $financy = $client->client_peoples_contact->where('type_contact', 2)->first();
                $order->client_email_financy_nfe = $financy->email;
            }

        }


        $order->client_id = $client->id;

        $categories = SetProductGroup::with(['setProductOnGroup'=>function($q){
            $q->orderBy('position', 'ASC');
        }, 'setProductOnGroup.productAirEvap'])->where('is_active', 1)->SetHasActive()->orderBy('position', 'ASC')->get();

        $order->programation_month_id = 0;
        $order->json_table_price = $table->toJson();
        $order->json_categories_products = $categories->toJson();
        $order->is_programmed = 0;
        $order->yearmonth = date('Y-m-01');
        $order->has_analyze = 1;
        $order->waiting_assign = 0;
        $order->salesman_imdt_approv = 0;
        $order->request_salesman_id = $client->request_salesman_id;
        $order->programation_version = 1;
        $order->request_r_code = $request->session()->get('r_code');
		$order->adjust_month = OrderAvaibleMonth::where('date', date('Y-m-01 00:00:00'))->get()->toJson();

        $header = DB::connection('commercial')
            ->table('settings')
            ->where('type', 2)
            ->get()->toJson();

        $order->json_header = $header;
        $order->save();

        if ($table->cif_fob == 0) {
            $order_receiver = new OrderReceiver;
            $order_receiver->type_receiver = $data[0][9];
            $order_receiver->type_day_receiver = $data[0][10];
            if ($data[0][10] == 2) {
                $order_receiver->monday_friday_hour_start = $data[0][11].':00';
                $order_receiver->monday_friday_hour_end = $data[0][12].':00';
                $order_receiver->saturday_hour_start = $data[0][13].':00';
                $order_receiver->saturday_hour_end = $data[0][14].':00';
            } else if ($data[0][10] == 1) {
                $order_receiver->monday_friday_hour_start = $data[0][11].':00';
                $order_receiver->monday_friday_hour_end = $data[0][12].':00';
            }
            $order_receiver->apm_name = $data[0][15];
            $order_receiver->apm_phone = $data[0][16];
            $order_receiver->apm_email = $data[0][17];
            $order_receiver->transport = $data[0][18];
            if ($data[0][18] == 2)
                $order_receiver->total = $data[0][19];

            $order_receiver->order_sales_id = $order->id;
            $order_receiver->save();
        }

        $json = json_decode($request->json_order, 2);

		foreach ($data as $prod) {
			$cat_id = 0;
			$prod_id = 0;
			$p_row = SetProduct::with('setProductOnGroupFilter')->where('code', $prod[1])->first();
			if ($p_row) {
				$cat_id = $p_row->setProductOnGroupFilter->set_product_group_id;
				$prod_id = $p_row->id;
			} else {
				throw new \Exception('Produto informa com código: '. $prod[1] .' Não existe no banco de dados. Nenhum pedido foi salvo.');
			}
			if ($prod > 0) {
				$item = new OrderProducts;
				$item->order_sales_id = $order->id;
				$item->set_product_id = $prod_id;
				$item->category_id = $cat_id;
				$price_unit = 0.00;
				if ($table->is_fixed_price == 1) {
					$collect_price_fixed = $table->set_product_price_fixed;
					$fixed_price = $collect_price_fixed
						->where('salesman_table_price_id', $table->id)
						->where('set_product_id', $prod_id)
						->first();
					if ($fixed_price)
						$price_unit = $fixed_price->price;
					else
						$price_unit = 0.00;
				} else {
					$price_unit = $applyPrice->calcPrice($p_row->price_base, $p_row, date('Y-m-01'), FALSE);
				}
				$item->price_unit = number_format($price_unit, 2, '.', '');
				$item->quantity = $prod[2];
				$item->descoint = 0;
				$item->total = number_format($price_unit, 2, '.', '');
				$item->is_price_custom = 0;
				$item->save();
			}
		}

        $order->view = $this->renderViewOrderConfirmed($order->id);
        $order->manual_order_sales = 0;
        $order->save();
	}
	
	private function clearRowsImportOrder($rows) {
		$rows_with_content = collect([]);
		
		foreach ($rows as $index => $row) {
			if ($row[0])
				$rows_with_content->push($row);
		}
		
		return $rows_with_content;
	}

    public function orderConfirmedAnalyze(Request $request, $id) {

        $query = OrderSales::with(
            'client',
            'salesman',
            'orderSalesAttach',
            'orderImdAnalyze',
            'orderCommercialAnalyze',
            'orderFinancyAnalyze',
			'OrderProducts')
            ->where('is_programmed', 0)
            ->where('has_analyze', 1)
            ->where('salesman_imdt_approv', 1)
            ->where('salesman_imdt_reprov', 0)
            ->where('id', $id);

        if ($this->validPerm($request, 20, 9, 1)) {
            $query->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);

        } else if ($this->validPerm($request, 18, 8, 1)) {
            $query->where('commercial_is_approv', 1)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0);
        } else {
			return redirect()->back()->with('error', 'Você não tem permissão para acessar essa página.');
		}

        $order = $query->first();

        if (!$order)
            return redirect()->back()->with('error', 'Não foi possível encontrar o pedido.');

        $salesman_id = $order->request_salesman_id;
        $salesman = '';
        $arr_imdt = [];
        // Montar array de gestores
        do {
            $data = Salesman::with('immediate_boss')->find($salesman_id);
            if ($data->immediate_boss->first()) {
                $salesman = $data->immediate_boss->first();
                $salesman_id = $salesman->id;
                if ($salesman->is_direction != 2)
                    $arr_imdt[] = $salesman;
            }
        } while ($salesman->is_direction != 2);

        $dir_commecrial = UserOnPermissions::with('user')->where('perm_id', 20)
            ->where('grade', 9)
            ->where('can_approv', 1)
            ->first();

        $dir_financy = UserOnPermissions::with('user')->where('perm_id', 18)
            ->where('grade', 8)
            ->where('can_approv', 1)
            ->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);

        return view('gree_commercial.orderSale.confirmed.analyze', [
            'order' => $order,
            'arr_imdt' => $arr_imdt,
            'dir_commecrial' => $dir_commecrial,
            'dir_financy' => $dir_financy,
        ]);
    }

    public function orderConfirmedPrintView(Request $request, $id) {

        $order = OrderSales::with(['client', 'orderSalesAttach'])
            ->where('id', $id)
            ->where('is_programmed', 0)

            ->first();


        if (!$order)
            return redirect()->back()->with('error', 'Você não está autorizado para ver essa solicitação!');

        return $order->view;
    }
	
	public function orderConfirmedPrintViewServer(Request $request, $id) {

		if ($request->getClientIp() != "52.201.83.221")
				return abort(404, 'Página não encontrada');		

        $order = OrderSales::with(['client', 'orderSalesAttach'])
            ->where('id', $id)
            ->where('is_programmed', 0)

            ->first();


        if (!$order)
            return redirect()->back()->with('error', 'Você não está autorizado para ver essa solicitação!');

        return $order->view;
    }

    public function orderAnalyze_do(Request $request) {

        $user = Users::where('r_code', $request->session()->get('r_code'))->first();

        if (Hash::check($request->password, $user->password)) {

            try {
                $AnalyzeProcessOrder = new AnalyzeProcessOrder($request, $request->id);
                $AnalyzeProcessOrder->doAnalyze($request->type_analyze, 1, $request->description);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            if ($request->is_programmed == 1)
                return redirect('/commercial/order/approv')->with('success', 'Análise realizada com sucesso!');
            else
                return redirect('/commercial/order/confirmed/approv')->with('success', 'Análise realizada com sucesso!');
        } else {

            if ($user->retry > 0) {

                $user->retry = $user->retry - 1;
                $user->save();

                if ($user->retry == 0) {

                    $user->retry_time = date('Y-m-d H:i:s');
                    $user->is_active = 0;
                    $user->save();

                    // Write Log
                    LogSystem("Diretor errou sua senha secreta para aprovar (comercial) muitas vezes e foi bloqueado no sistema.", $user->id);
                    return redirect('/logout')->with('error', "You have often erred in your secret password and been blocked, talk to administration.");
                } else {

                    // Write Log
                    LogSystem("Diretor errou sua senha secreta para aprovar (comercial). Restou apenas ". $user->retry ." tentativa(s).", $user->id);
                    return redirect()->back()->with('error', 'You missed your secret password, only '. $user->retry .' attempt(s) left.');
                }
            } else {

                // Write Log
                LogSystem("Diretor está tentando aprovar (comercial) com sua senha secreta, mesmo já tendo sido bloqueado!", $user->id);
                return redirect()->back();
            }
        }
    }

    public function orderProofUpload(Request $request) {

        $order = OrderSales::with(['orderSalesAttach'])
            ->where('id', $request->order_id)->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);


        $orderSalesAttach = new OrderSalesAttach;
        $response = $this->uploadS3(1, $request->order_file, $request);
        if ($response['success']) {
            $orderSalesAttach->url = $response['url'];
        } else {
            return redirect()->back()->with('error', 'Não foi possível fazer upload do arquivo!');
        }

        $orderSalesAttach->size = $request->file('order_file')->getSize();
        $orderSalesAttach->name = $request->file('order_file')->getClientOriginalName();
        $orderSalesAttach->order_sales_id = $request->order_id;
        $orderSalesAttach->save();

        $order->load('orderSalesAttach');

        return response()->json([
            'success' => true,
            'data' => $order->orderSalesAttach,
        ]);

    }

    public function orderProofRemove(Request $request) {

        $order = OrderSales::with(['orderSalesAttach'])
            ->where('id', $request->order_id)->first();

        if (!$order)
            return response()->json(['success' => false,'msg' => 'Seu pedido não foi encontrado na base de dados.',]);


        $file = $order->orderSalesAttach->where('id', $request->attach_id)->first();

        if (!$file)
            return response()->json(['success' => false,'msg' => 'Arquivo do pedido não foi encontrado.',]);

        $this->removeS3($file->url);
        $file->delete();

        $order->load('orderSalesAttach');

        return response()->json([
            'success' => true,
            'data' => $order->orderSalesAttach,
        ]);

    }

    public function orderProof(Request $request) {

        $order = OrderSales::with(['orderSalesAttach'])
            ->where('id', $request->order_id)->first();

        if (!$order)
            return redirect()->back()->with('error', 'Seu pedido não foi encontrado na base de dados.');

		if ($order->orderSalesAttach->count() == 0)
			return redirect()->back()->with('error', 'Você precisa anexar ao menos 1 arquivo para poder validar a comprovação.');

        $response = $this->fileManagerSVR([
            'order_id' => $order->id
        ], 'api/v1/commercial/order/files/to/pdf');

        if (!$response->success)
            return redirect()->back()->with('error', $response->msg);

        $order->waiting_assign = 0;
        $order->url_view_proof = $response->url;
        $order->save();

        try {
            $orderAnalyze = new AnalyzeProcessOrder($request, $request->order_id, true);
            $orderAnalyze->startAnalyze();
        } catch (\Exception $e) {
			$order->waiting_assign = 1;
			$order->save();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Seu pedido foi enviado para análise com sucesso!.');

    }

    public function orderCancel(Request $request, $id) {

        $order = OrderSales::with(
			'orderSalesAttach', 
			'client', 
			'salesman', 
			'orderDelivery',
            'orderReceiver',
            'orderProducts.setProduct.productAirEvap',
            'programationMonth.programation.salesman')
            ->where('id', $id)->first();

        if (!$order)
            return redirect()->back()->with('error', 'Seu pedido não foi encontrado na base de dados.');

        $order->is_cancelled = 1;
        $order->cancel_reason = $request->reason;
        $order->cancel_r_code = $request->session()->get('r_code');
        $order->has_analyze = 0;
        $order->save();
		
		if ($order->is_programmed)
            $order->view = $this->renderViewOrder($order->id);
        else
            $order->view = $this->renderViewOrderConfirmed($order->id);
		
		$order->save();
		
		if ($order->is_programmed and $order->is_approv) {
			if ($order->is_invoice)
				return redirect()->back()->with('error', 'Esse pedido já foi faturado, por favor, entre em pedidos faturados e cancele por lá.');
				
			$this->upProgramationMacro($order);
		}
		
		$settings = Settings::where('command', 'order_update_register')->first();
		if ($settings->value) {
			$arr = explode(',', $settings->value);

			foreach ($arr as $key) {

				if ($order->is_programmed) {
					$pattern = array(
						'title' => 'COMERCIAL - PEDIDO PROGRAMADO FOI CANCELADO: '. $order->code,
						'description' => nl2br("Olá! Foi realizado o cancelamento do pedido de vendas na plataforma da GREE
							<br><p><b>Client:</b> ". $order->client->company_name ."
							<br>Representante: ". $order->salesman->full_name ."
							<br><a href='". $request->root() ."/commercial/order/all'>". $request->root() ."/commercial/order/all</a></p>"),
						'template' => 'misc.DefaultExternal',
						'subject' => 'Comercial - pedido programado cancelado',

					);
				} else {
					$pattern = array(
						'title' => 'COMERCIAL - PEDIDO NÃO PROGRAMADO FOI CANCELADO: '. $order->code,
						'description' => nl2br("Olá! Foi realizado o cancelamento do pedido de vendas na plataforma da GREE
							<br><p><b>Client:</b> ". $order->client->company_name ."
							<br>Representante: ". $order->salesman->full_name ."
							<br><a href='". $request->root() ."/commercial/order/confirmed/all'>". $request->root() ."/commercial/order/confirmed/all</a></p>"),
						'template' => 'misc.DefaultExternal',
						'subject' => 'Comercial - pedido não programado cancelado',

					);
				}

				SendMailJob::dispatch($pattern, $key);
			}
		}

        return redirect()->back()->with('success', 'Seu pedido foi cancelado com sucesso!.');
    }
	
	private function upProgramationMacro($order) {
        // Devolve saldo na programação macro relacionada.
		$orderProducts = $order->orderProducts;
        $programationMacro = $order->programationMonth->programation->programationMacro()
            ->where('yearmonth', date('Y-m-01', strtotime($order->programationMonth->yearmonth)))
            ->get();

        foreach ($programationMacro as $item) {
            $order_prod = $orderProducts->where('set_product_id', $item->set_product_id)->where('category_id', $item->category_id)->first();
            if ($order_prod) {
                $item->quantity = $item->quantity + $order_prod->quantity;
                $item->save();
            }
        }
    }

    public function orderTimelineAnalyze(Request $request, $id) {
        $order = OrderSales::with(
            'salesman',
            'orderImdAnalyze',
            'orderCommercialAnalyze',
            'orderFinancyAnalyze')
            ->where('id', $id)
            ->first();

        if (!$order)
            return Response()->json([ 'success' => false, 'msg' => 'Não foi possível encontrar o pedido.'], 200);


        $arr_imdt = $this->generatorOrderAnalyze($order)?? [];
		$arr_cancel = [];
        $who_cancel = $order->whoCancel();
        if ($who_cancel) {
            $arr_cancel = array(
                'user' => $who_cancel,
                'when' => date('d/m/y H:i', strtotime($order->updated_at)),
                'description' => $order->cancel_reason
            );
        }

        return Response()->json([
            'imds' => $arr_imdt,
            'dir_commercial' => $this->generatorOrderAnalyze($order, 0, 'Diretor Comercial', 'orderCommercialAnalyze', 20, 9)?? [],
            'dir_financy' => [],
            'dir_revision' => [],
            'dir_judicial' => [],
			'who_cancel' => $arr_cancel
        ], 200);
    }
	
	public function programationTimelineAnalyze(Request $request, $id) {
        $programation = Programation::with('programationVersion')->where('id', $id)
            ->first();

        if (!$programation)
            return Response()->json([ 'success' => false, 'msg' => 'Não foi possível encontrar a programação.'], 200);

		$arr_cancel = [];
		$arr_mng = [];
		$arr_dir = [];
		if ($programation->is_cancelled) {
			$arr_cancel = array(
                'user' => Users::where('r_code', $programation->cancel_r_code)->first(),
                'when' => date('d/m/y', strtotime($programation->updated_at)),
                'description' => $programation->cancel_reason
            );
		} else {
			
			$is_reprov = false;
			$client_mng = DB::connection('commercial')->table('client_managers')->where('client_id', $programation->client_id)->first();
			$manager = Salesman::where('id', $client_mng->salesman_id)->first();

			$analyze_status = 1;
			$when = "";
			$office = $manager->office;
			if ($programation->coordinator_has_analyze) {
				if ($programation->programationVersion->is_reprov) {
					$analyze_status = 3;
					$when = date('d/m/Y', strtotime($programation->programationVersion->updated_at));
					$is_reprov = true;
				} else {
					$analyze_status = 2;
				}
					
			}
			$arr_mng = array(
                'user' => $manager,
                'analyze' => $analyze_status,
                'description' => $programation->programationVersion->description,
                'when' => $when,
                'office' => $office
            );
		
			
			if (!$is_reprov) {
				$direction = Salesman::where('id', '10')->first();
				
				$analyze_status = 1;
				$when = "";
				$office = $direction->office;
				if ($programation->manager_has_analyze) {
					if ($programation->programationVersion->is_reprov) {
						$analyze_status = 3;
						$is_reprov = true;
						$when = date('d/m/Y', strtotime($programation->programationVersion->updated_at));
					} else {
						$analyze_status = 2;
					}

				}

				$arr_dir = array(
					'user' => $direction,
					'analyze' => $analyze_status,
					'description' => $programation->programationVersion->description,
					'when' => $when,
					'office' => $office
				);
			}
			
		}

        return Response()->json([
            'imds' => [],
            'dir_commercial' => $arr_mng,
            'dir_financy' => $arr_dir,
            'dir_revision' => [],
            'dir_judicial' => [],
			'who_cancel' => $arr_cancel
        ], 200);
    }

    public function listDropDownProgramations(Request $request) {
        $name = $request->search;

        $data = Programation::whereExists(function ($sub){
            $sub->select(DB::raw(1))
                ->from('programation_version')
                ->whereRaw('programation.id = programation_version.programation_id')
                ->whereRaw('programation_version.is_approv = 1')
                ->orderBy('programation_version.id', 'DESC');
        })->where('code', 'like', '%'. $name .'%')
            ->where('request_salesman_id',$request->session()->get('salesman_data')->id)
            ->where('has_analyze', 0)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->code .' ('. $key->months .')';

                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }

    public function listProgramationMonth(Request $request) {

        $data = App\Model\Commercial\ProgramationMonth::whereHas('programation', function($q) use ($request) {
            $q->where('request_salesman_id', $request->session()->get('salesman_data')->id)
                ->where('has_analyze', 0)
                ->where('is_cancelled', 0);
        })->where('programation_id', $request->id)
            ->where('version', function ($sub) {
                $sub->select(DB::raw('max(version)'))
                    ->from('programation_version')
                    ->whereRaw('programation_month.programation_id = programation_version.programation_id')
                    ->whereRaw('programation_version.is_approv = 1');
            })
            ->paginate(10);

        $results = array();


        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = date('Y-m', strtotime($key->yearmonth));

                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }

    public function listClientSameGroup(Request $request) {

        $client = Client::find($request->id);
        $data = Client::whereHas('client_group', function($q) use ($client) {
            $q->where('client_group.id', $client->group->id);
        })->where('id', '!=', $client->id)
            ->where(function($sub) use ($request){
                $sub->where('code', 'LIKE', '%'. $request->search .'%')
                    ->orWhere('identity', 'LIKE', '%'. $request->search .'%')
                    ->orWhere('fantasy_name', 'LIKE', '%'. $request->search .'%');
            })
            ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->id;
                $row['text'] = $key->fantasy_name .' ('. $key->identity .')';
                $row['address'] = $key->address;
                $row['state'] = $key->state;
                $row['city'] = $key->city;
                $row['district'] = $key->district;
                $row['zipcode'] = $key->zipcode;

                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }

    public function programationExport(Request $request) {

        $order = OrderSales::with('client.manager_region', 'client.salesman', 'orderProducts.setProduct.productAirEvap', 'programationMonth')
            ->where('is_programmed', 1)
            ->orderBy('id', 'DESC');

        if ($request->salesman_id) {
            $order->where('request_salesman_id', $request->salesman_id);
        }

        if ($request->start_date) {
            $date = $request->start_date;
            $order->whereHas('programationMonth', function($q) use ($date) {
                $q->whereYear('yearmonth', '>=', date('Y', strtotime($date)))
                  ->whereMonth('yearmonth', '>=', date('m', strtotime($date)));
            });
        } 

        if ($request->end_date) {
            $date = $request->end_date;
            $order->whereHas('programationMonth', function($q) use ($date) {
                $q->whereYear('yearmonth', '<=', date('Y', strtotime($date)))
                  ->whereMonth('yearmonth', '<=', date('m', strtotime($date)));
            });
        } 

        if($request->status) {
            $status = $request->status;

            if($status == 1) {  
                $order->where('is_cancelled', 1);
            } 
            elseif($status == 2) { 
                $order->where('salesman_imdt_approv', 1)
                    ->where('commercial_is_approv', 1)
                    ->where('financy_approv', 1)
                    ->where('is_invoice', 0);
            }
            elseif($status == 3) { 
                $order->where('is_invoice', 1);
            }
            elseif($status == 4) {
                $order->where('salesman_imdt_reprov', 1);
            }
            elseif($status == 5) {
                $order->where('commercial_is_reprov', 1);
            }
            elseif($status == 6) {
                $order->where('financy_reprov', 1);
            }    
            elseif($status == 7) {
                $order->where('waiting_assign', 1);
            }
            elseif($status == 8) {
                $order->where('has_analyze', 1);
            }  
        }

        $order = $order->get();
        return Excel::download(new App\Exports\ProgramationExport($order), 'ProgramationExport-'. date('Y-m-d') .'.xlsx');
    }
	
	public function orderExport(Request $request) {

        $order = OrderSales::with('client.client_managers.salesman', 'client.salesman', 'orderProducts.setProduct.productAirEvap')
            ->where('is_programmed', 0)
            ->orderBy('id', 'DESC');

        if ($request->salesman_id) {
            $order->where('request_salesman_id', $request->salesman_id);
        }

        if ($request->start_date) {
            $order->whereYear('yearmonth', '>=', date('Y', strtotime($request->start_date)))
                  ->whereMonth('yearmonth', '>=', date('m', strtotime($request->start_date)));
        } 

        if ($request->end_date) {
            $order->whereYear('yearmonth', '<=', date('Y', strtotime($request->end_date)))
                  ->whereMonth('yearmonth', '<=', date('m', strtotime($request->end_date)));
        } 

        if($request->status) {
            $status = $request->status;

            if($status == 1) {  
                $order->where('is_cancelled', 1);
            } 
            elseif($status == 2) { 
                $order->where('salesman_imdt_approv', 1)
                    ->where('commercial_is_approv', 1)
                    ->where('financy_approv', 1)
                    ->where('is_invoice', 0);
            }
            elseif($status == 3) { 
                $order->where('is_invoice', 1);
            }
            elseif($status == 4) {
                $order->where('salesman_imdt_reprov', 1);
            }
            elseif($status == 5) {
                $order->where('commercial_is_reprov', 1);
            }
            elseif($status == 6) {
                $order->where('financy_reprov', 1);
            }    
            elseif($status == 7) {
                $order->where('waiting_assign', 1);
            }
            elseif($status == 8) {
                $order->where('has_analyze', 1);
            }  
        }

        $order = $order->get();

        //ob_end_clean();
        return Excel::download(new App\Exports\OrderExport($order), 'OrderExport-'. date('Y-m-d') .'.xlsx');

    }
	
	public function clientTimelineAnalyze(Request $request, $id) {
        $client = Client::with([
            'client_imdt_analyze',
            'client_revision_analyze',
            'client_judicial_analyze',
            'client_commercial_analyze',
            'client_financy_analyze',
            'client_version' => function($q) {
                $q->orderBy('id', 'DESC');
            }])
            ->where('id', $id)
            ->first();

        if (!$client)
            return Response()->json([ 'success' => false, 'msg' => 'Não foi possível encontrar o cliente.'], 200);

        $arr_imdt = $this->generatorClientAnalyze($client);

        return Response()->json([
            'imds' => $arr_imdt,
            'dir_commercial' => $this->generatorClientAnalyze($client, 0, 'Diretor Comercial', 'client_commercial_analyze', 20, 9),
            'dir_financy' => [],
            //'dir_judicial' => $this->generatorClientAnalyze($client, 0, 'Direção Juridica', 'client_judicial_analyze', 23),
			'dir_judicial' => [],
            'dir_revision' => $this->generatorClientAnalyze($client, 0, 'Revisão interna', 'client_revision_analyze', 20, 4),
			'who_cancel' => []
        ], 200, [], JSON_PRETTY_PRINT);
    }
	
	public function clientImport(Request $request) {

        return view('gree_commercial.client.clientImport');
    }
		
	public function clientImport_do(Request $request) {

        set_time_limit(360);
        if ($request->hasFile('attach')) {
            $extension = $request->attach->extension();

            $validator = Validator::make(
                [
                    'file'      => $request->attach,
                    'extension' => strtolower($request->attach->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required|max:10240',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]
            );
            
            if ($validator->fails()) {
                
                $request->session()->put('error', "Tamanho do arquivo não pode exceder 10MB!");
                return redirect()->back();
            } else {
        
                try {
                    Excel::import(new ClientsImport($request), $request->file('attach'));
        
                    LogSystem("Colaborador importou clientes", $request->session()->get('r_code'));
                    $request->session()->put('success', "Clientes importados com sucesso!");
                    return Redirect('/commercial/client/list');
                }
                catch (\Exception $e) {
                    $request->session()->put('error', $e->getMessage());
                    return redirect()->back();
                }
            } 
        } else {
            return redirect()->back()->with('error', 'Escolha o arquivo para exportar!');
        }
    }
	
	public function operationDashboardGeneral(Request $request) {

        $block1 = ProgramationMacro::whereHas('programation', function($q) {
			$q->where('is_cancelled', 0);
		})->where('quantity', '>', 0)->groupBy('programation_id')->get()->count();
        $block2 = Client::where('has_analyze', 1)->count();
        $block3 = Programation::where('has_analyze', 1)->where('is_cancelled', 0)->count();
        $block4 = OrderSales::where('has_analyze', 1)->where('is_programmed', 1)->count();
        $block5 = OrderSales::where('has_analyze', 1)->where('is_programmed', 0)->count(); 

        $order = DB::connection('commercial')->table('order_sales')
            ->where('has_analyze', 1)
            ->where('waiting_assign', 0)
            ->where('salesman_imdt_approv', 1)
            ->where('salesman_imdt_reprov', 0);

        if ($this->validPerm($request, 20, 9, 1)) {
            $qresult = $order->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)->get();
				$order_approv = $qresult->where('is_programmed', 1)->count();
				$order_confirmed_approv = $qresult->where('is_programmed', 0)->count();

        } else if ($this->validPerm($request, 18, 8, 1)) {
            $qresult = $order->where('commercial_is_approv', 1)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)->get();
			$order_approv = $qresult->where('is_programmed', 1)->count();
			$order_confirmed_approv = $qresult->where('is_programmed', 0)->count();
        } else {
			$order_approv = 0;
			$order_confirmed_approv = 0;
			
		}
        
        $countClient = 0;			
        if ($this->validPerm($request, 20, 4, 1)) {
            $countClient = $this->scopeClientCountAnalyze()->where('salesman_imdt_approv', 1)
				->where('revision_is_approv', 0)
				->where('revision_is_reprov', 0)
                ->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)->count();

        } else if ($this->validPerm($request, 23, null, 1)) {
            $countClient = $this->scopeClientCountAnalyze()->where('salesman_imdt_approv', 1)
                ->where('revision_is_approv', 1)
				->where('judicial_is_approv', 0)
				->where('judicial_is_reprov', 0)
                ->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)->count();

        } else if ($this->validPerm($request, 20, 9, 1)) {
            $countClient = $this->scopeClientCountAnalyze()->where('salesman_imdt_approv', 1)
                ->where('judicial_is_approv', 1)
                ->where('commercial_is_approv', 0)
                ->where('commercial_is_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)->count();

        } else if ($this->validPerm($request, 18, 8, 1)) {
            $countClient = $this->scopeClientCountAnalyze()->where('salesman_imdt_approv', 1)
                ->where('judicial_is_approv', 1)
                ->where('commercial_is_approv', 1)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)->count();
        }

        $arr_order = collect([]);
        $arr_order_confirmed = collect([]);

        for ($i=1; $i <= 12; $i++) {
            $total1 = OrderSales::with('programationMonth')
                        ->whereHas('programationMonth', function ($q) use ($i) {
                            $q->whereMonth('yearmonth', $i)
                              ->whereYear('yearmonth', date('Y'));
                        }) 
                        ->where('is_programmed', 1)
                        ->where('is_cancelled', 0)
                        ->where('is_reprov', 0);
            
            $arr_order->push($total1->count());
        }    

        for ($i=1; $i <= 12; $i++) {
            $total2 = OrderSales::where('is_programmed', 0)
                        ->where('is_cancelled', 0)
                        ->where('is_reprov', 0)
                        ->whereMonth('created_at', $i)
                        ->whereYear('created_at', date('Y'));

            $arr_order_confirmed->push($total2->count());
        }    

        
        $arr_order_region_programmed = [0, 0, 0, 0, 0];
        $arr_order_region_not_programmed = [0, 0, 0, 0, 0];
        
        $order_regions_programmed = OrderSales::with('orderDelivery')->select('id', 'client_state', 'is_programmed')->where('is_cancelled', 0)->where('is_reprov', 0)->whereYear('created_at', date('Y'))->get();
        foreach($order_regions_programmed as $key) {

            $state = $key->orderDelivery ? $key->orderDelivery->state : $key->client_state;

            if(config('gree.arr_states')[$state]['region'] == 'Sul')
                $key->is_programmed == 1 ? $arr_order_region_programmed[0] += 1 : $arr_order_region_not_programmed[0] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Sudeste')    
                $key->is_programmed == 1 ? $arr_order_region_programmed[1] += 1 : $arr_order_region_not_programmed[1] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Centro-Oeste')    
                $key->is_programmed == 1 ? $arr_order_region_programmed[2] += 1 : $arr_order_region_not_programmed[2] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Norte')
                $key->is_programmed == 1 ? $arr_order_region_programmed[3] += 1 : $arr_order_region_not_programmed[3] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Nordeste')    
                $key->is_programmed == 1 ? $arr_order_region_programmed[4] += 1 : $arr_order_region_not_programmed[4] += 1;
        }    

        $arr_client_region = [0, 0, 0, 0, 0];
        $client_register = Client::select('id', 'state')->get();
        foreach($client_register as $client) {
			
			$state = trim($client->state);

            if(config('gree.arr_states')[$state]['region'] == 'Sul')
                $arr_client_region[0] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Sudeste')    
                $arr_client_region[1] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Centro-Oeste')    
                $arr_client_region[2] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Norte')
                $arr_client_region[3] += 1;
            elseif (config('gree.arr_states')[$state]['region'] == 'Nordeste')    
                $arr_client_region[4] += 1;
        }
        

        $arr_client_status = [0, 0, 0, 0];
        $client_status = Client::select('id', 'is_active', 'salesman_imdt_reprov', 'revision_is_reprov', 
                                        'judicial_is_reprov', 'commercial_is_reprov', 'financy_reprov', 
                                        'salesman_imdt_approv', 'revision_is_approv', 'judicial_is_approv', 
                                        'commercial_is_approv', 'financy_approv', 'financy_status', 'has_analyze')->get();

        $status = '';
        foreach($client_status as $clients) {

            if ($clients->is_active == 0) {
                $arr_client_status[0] += 1;
            } elseif ($clients->salesman_imdt_approv == 1 and $clients->revision_is_approv == 1 and $clients->judicial_is_approv == 1 and $clients->commercial_is_approv == 1 and $clients->financy_approv == 1) {
                if ($clients->financy_status == 2) {
                    $arr_client_status[1] += 1;
                } elseif ($clients->financy_status == 3) {
                    $arr_client_status[2] += 1;
                }
            } elseif($clients->has_analyze == 0) {

                if ($clients->financy_status == 2) {
                    $arr_client_status[1] += 1;
                } elseif ($clients->financy_status == 3) {
                    $arr_client_status[2] += 1;
                }
            }  
            if($clients->is_active == 1) {
                $arr_client_status[3] += 1;
            }
        }   

        return view('gree_commercial.operation.dashboardGeneral', [
            'block1' => $block1,
            'block2' => $block2,
            'block3' => $block3,
            'block4' => $block4,
            'block5' => $block5,
            'order_approv' => $order_approv,
            'order_confirmed_approv' => $order_confirmed_approv,
            'client_approv' => $countClient,
            'chart_arr_order' => $arr_order->toArray(),
            'chart_arr_order_confirmed' => $arr_order_confirmed->toArray(),
            'arr_order_region_programmed' => $arr_order_region_programmed,
            'arr_order_region_not_programmed' => $arr_order_region_not_programmed,
            'arr_client_region' => $arr_client_region,
            'arr_client_status' => $arr_client_status
        ]);
    }

    public function operationalReportInvoicePrint(Request $request) {

        return view('gree_commercial.operation.reportInvoice.printRequestVerbaCommercial', [
        ]);
    }

    public function operationalReportInvoice(Request $request) {

        $reportInvoices = App\Model\Commercial\BudgetCommercialReport::with('BudgetCommercial')->orderBy('id', 'DESC');

        $array_input = collect([
            'code',
            'code_s_verba',
            'client',
            'type_report',
            'start_date',
            'end_date',
            'status',
        ]);

        // SAVE FILTERS
        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $valor_filtro) {
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $reportInvoices->where("code", $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."code_s_verba"){
                    $reportInvoices->whereHas('BudgetCommercial', function($q) use ($valor_filtro) {
                        $q->where('code', $valor_filtro);
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $reportInvoices->whereHas('client', function($q) use ($valor_filtro) {
                        $q->where('id', $valor_filtro);
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."type_report"){
                    $reportInvoices->where("type_report", $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $reportInvoices->where("created_at", '>=', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $reportInvoices->where("created_at", '<=', $valor_filtro);
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
                    $reportInvoices->where("status", $valor_filtro);
                }
            }
        }

        return view('gree_commercial.operation.reportInvoice.orderReportInvoice', [
            'reportInvoices' => $reportInvoices->paginate(10)
        ]);
    }

    public function operationalReportInvoiceUpdate(Request $request) {
        $report = App\Model\Commercial\BudgetCommercialReport::find($request->id);

        if (!$report)
            return redirect()->back()->with('error', 'Apuração que deseja atualizar, não existe no sistema.');

        if ($report->budget_commercial_id)
            return redirect()->back()->with('error', 'Você não pode editar uma apuração vinculada a uma solicitação de verba');

        $report->status = $request->status;
        if ($request->hasFile('report')) {
            $response = $this->uploadS3(1, $request->report, $request);
            if ($response['success']) {
                $report->report_file_url = $response['url'];
            } else {
                return redirect()->back()->with('error', 'Não foi possível fazer upload da imagem');
            }
        }

        LogSystem("Colaborador atualizou apuração CAF-". $report->id, $request->session()->get('r_code'));
        $report->save();
        return redirect()->back()->with('success', 'Apuração atualizada com sucesso!');
    }

    public function operationalReportInvoiceDelete(Request $request) {
        $report = App\Model\Commercial\BudgetCommercialReport::find($request->id);

        if (!$report)
            return redirect()->back()->with('error', 'Apuração que deseja excluir, não existe no sistema.');

        if ($report->budget_commercial_id)
            return redirect()->back()->with('error', 'Você não pode excluir uma apuração vinculada a uma solicitação de verba');

        LogSystem("Colaborador excluiu apuração CAF-". $report->id, $request->session()->get('r_code'));
        $report->delete();
        return redirect()->back()->with('success', 'Apuração excluida com sucesso!');
    }

    public function operationalReportInvoiceNew(Request $request) {

		$group = ClientGroup::with('Clients')->find($request->client_group_id);
		if (!$group)
            return redirect()->back()->with('error', 'Não foi possível encontrar o grupo do cliente.');

        $orders = App\Model\Commercial\OrderInvoice::with(['orderSales' => function ($q) use ($group) {
            $q->whereIn('order_sales.client_id', $group->Clients->pluck('id'));
        }, 'orderSales.client'])
            ->whereBetween('date_emission', [$request->start_date, $request->end_date]);

        if ($request->type_report == 2) {
			if (!$request->tax_rebate or !$request->group or !$request->goal)
				return redirect()->back()->with('error', 'Você precisa preencher todos os dados corretamente.');

            $orders->whereHas('orderInvoiceProducts', function ($q) use ($request) {
                $q->where('set_product_group_id', $request->group);
            });
        }

        $result = $orders->first();

        if (!$result)
            return redirect()->back()->with('error', 'Não há dados para criar apuração.');

        $new_report = new App\Model\Commercial\BudgetCommercialReport;
        $new_report->r_code = $request->session()->get('r_code');
        $new_report->client_group_name = $group->name;
        $new_report->type_report = $request->type_report;
		$new_report->request_salesman_id = $request->salesman_id;
        $new_report->status = 3;
        $new_report->save();

        $new_report->code = 'CAF-'.$new_report->id;
        $new_report->save();
		
		foreach($group->Clients as $client) {
			$new_report_client = new App\Model\Commercial\BudgetCommercialReportClients;
			$new_report_client->budget_commercial_report_id = $new_report->id;
			$new_report_client->client_id = $client->id;
			$new_report_client->code = $client->code;
			$new_report_client->save();
		}

        $request->merge(['report_id' => $new_report->id, 'r_code' => $request->session()->get('r_code')]);

        $response = $this->fileManagerSVR(
            $request->all(),
            $request->type_report == 1 ?
                '/api/v1/commercial/report/export/vpc' :
                '/api/v1/commercial/report/export/rebate'
        );

        if (!$response->success) {
            return redirect()->back()->with('error', $response->msg);
        }

        LogSystem("Colaborador criou nova apuração CAF-". $new_report->id, $request->session()->get('r_code'));
        return redirect()->back()->with('success', 'Nova solicitação de apuração foi gerada com sucesso!');
    }

    public function operationalNFsPendingImport(Request $request) {

        $nfs = \App\Model\Commercial\OrderInvoiceNFPending::all();

        return view('gree_commercial.operation.reportInvoice.nfsPendingImport', [
            'nfs' => $nfs
        ]);
    }

    public function operationalNFsPendingImport_do(Request $request) {

        set_time_limit(360);
        if ($request->hasFile('attach')) {
            $extension = $request->attach->extension();

            $validator = Validator::make(
                [
                    'file'      => $request->attach,
                    'extension' => strtolower($request->attach->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required|max:10240',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]
            );

            if ($validator->fails()) {

                $request->session()->put('error', "Tamanho do arquivo não pode exceder 10MB!");
                return redirect()->back();
            } else {

                try {
                    DB::connection('commercial')->table('order_invoice_nf_pending')->delete();
                    Excel::import(new App\Imports\DefaultImport(new \App\Model\Commercial\OrderInvoiceNFPending, ['nf_number', 'nf_serie', 'code']), $request->file('attach'));

                    LogSystem("Colaborador importou NFs pendentes para pagamento", $request->session()->get('r_code'));
                    $request->session()->put('success', "NFs importadas com sucesso!");
                    return Redirect('/commercial/operation/nfs/pendings/import');
                }
                catch (\Exception $e) {
                    $request->session()->put('error', $e->getMessage());
                    return redirect()->back();
                }
            }
        } else {
            return redirect()->back()->with('error', 'Escolha o arquivo para exportar!');
        }
    }
	
	public function operationSaleVerification(Request $request) {
        return view('gree_commercial.operation.saleVerification');
    }

    public function exportReportSaleClientResponseList(Request $request) {

        $sales_verification = SaleVerificationClientCompleted::with('client', 'users')->orderBy('id', 'DESC');
		
		$client = new GuzzleClient();

		// Provide the body as a string.
		$r = $client->request('POST', 'https://api.anti-captcha.com/getBalance', [
			'json' => ['clientKey' => '63510246f40047a065bed12aa714948f']
		]);
		
		$data = json_decode($r->getBody());

        return view('gree_commercial.operation.saleVerificationList', [
            'sales_verification' => $sales_verification->paginate(10),
			'balance' => $data->balance
        ]);
    }

    public function exportReportSaleClientResponseErrorsList(Request $request, $id) {
		
		$sales_verification_errors = SaleVerificationErrors::where('sale_verification_client_completed_id', $id)->orderBy('id', 'DESC');
		$sales_completed = SaleVerificationClientCompleted::find($id);
		if (!$sales_completed)
			return redirect()->back()->with('error', 'Apuração que está tentando visualizar os erros, já não existe mais.');
		
        return view('gree_commercial.operation.saleVerificationListErrors', [
			'id' => $id,
            'total_errors' => $sales_verification_errors->count(),
			'is_ascertained' => $sales_completed->is_ascertained,
            'sales_verification_errors' => $sales_verification_errors->paginate(10)
        ]);
    }
	
	public function saleClientVerificationErrorsListDropdown(Request $request, $id) {

        $name = $request->search;
        $data = SaleVerificationErrors::where('sale_verification_client_completed_id', $id)
                                      ->where('msg_errors', 'like', '%'. $name .'%')
                                      ->groupBy('msg_errors')
                                      ->orderBy('id', 'DESC')
                                      ->paginate(10);

        $results = array();

        if (count($data) > 0) {

            foreach ($data as $key) {
                $row = array();
                $row['id'] = $key->msg_errors;
                $row['text'] = $key->msg_errors;
                array_push($results, $row);
            }

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => true
                ],
            ]);
        } else {
            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false
                ],
            ]);
        }
    }
	
	public function operationalOrderInvoice(Request $request) {

        $order_invoice = OrderSales::with([
            'orderInvoice.orderInvoiceRefund' => function($query) {
                $query->orderBy('id', 'DESC');
            },
            'orderInvoice.orderInvoiceRefund.orderInvoiceRefundProducts',
            'orderInvoice.orderInvoiceProducts.productAir', 
            'orderProducts.setProduct.productAirEvap',
            'orderProducts.setProduct.productAirCond',
            'orderProducts.setProduct.setProductOnGroup',
            'salesman', 
            'client'])
            ->whereHas('orderInvoice')
            ->orderBy('id', 'DESC');

        $errors_invoice = OrderInvoiceErrors::orderBy('id', 'DESC');    
        
        $array_input = collect([
            'code_order',
            'nf_code',
            'subordinates',
            'client',
            'status',
            'order_date_start',
            'order_date_end',
            'nf_date_start',
            'nf_date_end'
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $value_filter) {
                if($nome_filtro == $filtros_sessao[1]."code_order"){
                    $order_invoice->where("code", $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."nf_code"){
                    $order_invoice->whereHas('orderInvoice', function($q) use ($value_filter){
                        $q->where('nf_number', $value_filter);
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."subordinates"){
                    $order_invoice->where('request_salesman_id', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order_invoice->where('client_id', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $order_invoice->where('client_id', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
                    $value = $value_filter == 99 ? 0 : $value_filter;
                    $order_invoice->where('is_invoice', $value);
                }
                if($nome_filtro == $filtros_sessao[1]."order_date_start"){
                    $order_invoice->whereDate('yearmonth', '>=', $value_filter);
                }   
                if($nome_filtro == $filtros_sessao[1]."order_date_end"){
                    $order_invoice->whereDate('yearmonth', '<=', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."nf_date_start"){
                    $order_invoice->whereHas('orderInvoice', function($q) use ($value_filter){
                        $q->whereDate('date_emission', '>=', $value_filter);
                    });
                }
                if($nome_filtro == $filtros_sessao[1]."nf_date_end"){
                    $order_invoice->whereHas('orderInvoice', function($q) use ($value_filter){
                        $q->whereDate('date_emission', '<=', $value_filter);
                    });
                }
            }
        }
        
        return view('gree_commercial.operation.invoice.orderInvoice', [
            'order_invoice' => $order_invoice->paginate(10),
            'errors_invoice' => $errors_invoice->get()
        ]);
    }
	
	public function operationalOrderInvoiceImportRefund(Request $request) {

        try {

            if ($request->hasFile('xml_refund') && $request->file('pdf_refund')) {

                if($request->file('xml_refund')->getMimeType() != 'text/xml')
                    return redirect()->back()->with('error', 'Selecione arquivo correto para o campo XML');

                if($request->file('pdf_refund')->getMimeType() != 'application/pdf')
                    return redirect()->back()->with('error', 'Selecione arquivo correto para o campo PDF');

                $invoice = OrderInvoice::find($request->invoice_id);
                if(!$invoice)
                    return redirect()->back()->with('error', 'Faturamento não encontrado!');

                $arr_rel_refund = [
                    'refund_cprod' => $request->refund_cprod,
                    'refund_xprod' => $request->refund_xprod,
                    'refund_data' => $request->refund_data
                ];

                $pdf = $request->file('pdf_refund');
                $xml = $request->file('xml_refund');
                $stdCl = new Standardize($xml->get());
                
                $refund = new OrdersRefund($stdCl , $pdf->get());
                $refund->saveRefund($invoice, $arr_rel_refund);

                return redirect()->back()->with('success', 'Devolução importada com sucesso!');
                
            } else {
                return redirect()->back()->with('error', 'Selecione XML e PDF para realizar a importação!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }      
    }
	
	public function operationalOrderInvoiceRefundDelete(Request $request, $id) {
		
        try {

            $verifyRepeatOrder = [];
            $order_sales_id = null;
            $set_product_id = null;
            $quantity = 0;

            $refund = OrderInvoiceRefund::with('orderInvoiceRefundProducts')->where('id', $id);
            if(!$refund->first()) {
                return redirect()->back()->with('error', 'Devolução não encontrada!');
            } else {
                $refund_first = $refund->first();
            }    

            foreach($refund_first->orderInvoiceRefundProducts as $product) {

                if(!in_array($product->set_product_id, $verifyRepeatOrder)) {

                    array_push($verifyRepeatOrder, $product->set_product_id);

                    $quantity = $product->quantity;
                    $order_product = OrderProducts::where('set_product_id', $product->set_product_id)->where('order_sales_id', $refund_first->order_sales_id)->first();
					$order_product->quantity_invoice_refund -=  $quantity;
                    $order_product->save();
                }
            }
			
			$this->updateOrderSaleInvoice($refund_first->order_sales_id);

            $refund_count = $refund->count();
            if($refund_count == 1) {
                $order_invoice = OrderInvoice::find($refund_first->order_invoice_id);
                $order_invoice->is_refund = 0;
                $order_invoice->save();
            }

            if($refund->delete())
                OrderInvoiceRefundProducts::where('order_invoice_refund_id', $id)->delete();
            
            return redirect()->back()->with('success', 'Devolução excluída com sucesso!');

        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
	

	
	private function updateOrderSaleInvoice($id) {
		
        $order_products = OrderProducts::where('order_sales_id', $id);
        $qtd_order = $order_products->sum('quantity');
        $qtd_invoice = $order_products->sum('quantity_invoice');

        if($qtd_order > $qtd_invoice) {
            $order_sale = OrderSales::find($id);
            $order_sale->is_invoice = 0;
            $order_sale->save();
        }
    }
	
	public function operationOrderInvoiceResendXml(Request $request) {

        try {

            $keys = [];
            if(count($request->xml_refund) > 0) {
                foreach ($request->xml_refund as $index => $attach) {
                    array_push($keys, $request->key_nfe[$index]);
                    $xml = new Standardize($attach->get());
                    $invoice = new OrdersInvoice($xml);
                    $invoice->saveInvoice();
                }
                if(count($keys) > 0) {
                    OrderInvoiceErrors::whereIn('key_nfe', $keys)->delete();
                }    
            } else {
                throw new \Exception('Não há arquivos selecionados!');
            }
            return redirect()->back()->with('success', 'Nota fiscal cadastrada com sucesso!');

        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
	
	public function operationalOrderInvoiceConfirm(Request $request, $id) {

        try {
            $order = OrderSales::find($id);
            if(!$order)
                return redirect()->back()->with('error', 'Pedido não encontrado!');

            $order->is_invoice = 1;
            $order->save();

            return redirect()->back()->with('success', 'Pedido faturado com sucesso!');

        } catch(\Exception $e) {
            return Log::error($e->getMessage());
        }
    }
	
	public function operationalOrderInvoiceEmail(Request $request) {

        try {
            $refund = new OrderInvoiceEmail('gree-app.com.br','nfs@gree-app.com.br','2t&0gn6J');
            $refund->startTaskInvoice();
        } 
        catch(\Exception $e) {
            return Log::error($e->getMessage());
        }
    } 
	
	public function operationalOrderInvoiceNfeDelete(Request $request, $order_id, $code_nfe) {
        
        try {

            $verifyRepeatInvoice = [];

            $invoice = OrderInvoice::where('order_sales_id', $order_id)->where('nf_number', $code_nfe)->first();
            if(!$invoice)                       
                return redirect()->back()->with('error', 'Faturamento não encontrado!');

            $this->operationalOrderInvoiceRefundNfeDelete($invoice->id, $order_id);
            $invoice_products = OrderInvoiceProducts::where('order_invoice_id', $invoice->id)->groupBy('set_product_id')->get();
            foreach($invoice_products as $product) {

                $order_products = OrderProducts::where('order_sales_id', $order_id)->where('set_product_id', $product->set_product_id)->first();
                $order_products->quantity_invoice -= $product->quantity;
                $order_products->save();
            }
            OrderInvoiceProducts::where('order_invoice_id', $invoice->id)->delete();
            OrderInvoice::where('order_sales_id', $order_id)->where('nf_number', $code_nfe)->delete();
            
            $this->updateOrderSaleInvoice($order_id);

            return redirect()->back()->with('success', 'Nota de faturamento excluída com sucesso!');

        } catch(\Exception $e) {
            return Log::error($e->getMessage());
        }    
    }
	
	private function operationalOrderInvoiceRefundNfeDelete($invoice_id, $order_sale_id) {

        $verifyRepeatInvoiceRefund = [];

        $invoice_refund = OrderInvoiceRefund::where('order_invoice_id', $invoice_id)->get();
        foreach($invoice_refund as $refund) {

            $invoice_refund_products = OrderInvoiceRefundProducts::where('order_invoice_refund_id',  $refund->id)->groupBy('set_product_id')->get();
            foreach($invoice_refund_products as $refund_product) {

                $order_products = OrderProducts::where('order_sales_id', $order_sale_id)->where('set_product_id', $refund_product->set_product_id)->first();
                $order_products->quantity_invoice_refund -=  $refund_product->quantity;
                $order_products->save();
            } 
            OrderInvoiceRefundProducts::where('order_invoice_refund_id',  $refund->id)->delete();  
        }
        OrderInvoiceRefund::where('order_invoice_id', $invoice_id)->delete();
    }
	
	public function commercialBudgetList(Request $request) {

		$verb = App\Model\Commercial\BudgetCommercial::with([
                'salesman',
				'client',
				'budget_commercial_report',
				'budget_commercial_attach' => function($q) {
					$q->where('type_document', 2);
				}])->orderBy('id', 'DESC');
		
		$array_input = collect([
            'code',
            'subordinates',
            'client',
            'status',
            'start_date',
            'end_date'
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $value_filter) {
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $verb->where("code", $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."subordinates"){
                    $verb->where('request_salesman_id', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $$verb->where('client_id', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
					if ($value_filter == 1)
						$verb->where('is_cancelled', 1);
					elseif ($value_filter == 2)
						$verb->where('is_approv', 1);
					elseif ($value_filter == 1)
						$verb->where('waiting_assign', 1);
					elseif ($value_filter == 1)
						$verb->where('has_analyze', 1);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $verb->whereDate('created_at', '>=', $value_filter);
                }   
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $verb->whereDate('created_at', '<=', $value_filter);
                }
            }
        }
		
		if ($request->export == 1) {
			
			$heading = array('Código', 'Solicitante', 'Cliente', 'Tipo da solicitação', 'Tipo de pagamento', 'Criado em', 'Status');
            $rows = array();
			
			foreach($verb->get() as $key) {
				$line = array();
				
				$status = '';
				
				if ($key->has_analyze)
					$status = 'Em análise';
				elseif ($key->is_approv)
					$status = 'Aprovado';
				elseif ($key->is_reprov)
					$status = 'Reprovado';
				else
					$status = 'Não enviado';
				
				$line[0] = $key->code;
				$line[1] = $key->salesman->short_name;
				$line[2] = $key->client->company_name;
				$line[3] = $key->type_budget_name;
				$line[4] = $key->type_payment_name;
				$line[5] = date('d/m/Y H:i', strtotime($key->created_at));
				$line[6] = $status;
					
				array_push($rows, $line);
			}
			
			return Excel::download(new DefaultExport($heading, $rows), 'verbsCommercial-'. date('Y-m-d H:i:s') .'.xlsx');
		}
		
		return view('gree_commercial.operation.verbCommercial.list', [
			'verb' => $verb->paginate(10)
		]);
	}
	
	public function commercialBudgetListAnalyze(Request $request) {
		
		$verb = App\Model\Commercial\BudgetCommercial::with([
                'salesman',
				'client',
				'budget_commercial_report',
				'budget_commercial_attach' => function($q) {
					$q->where('type_document', 2);
				}])->ValidAnalyzeProccess($request->session()->get('r_code'))->orderBy('id', 'DESC');
		
		$array_input = collect([
            'code',
            'subordinates',
            'client',
            'status',
            'start_date',
            'end_date'
        ]);

        $array_input = putSession($request, $array_input);
        $filtros_sessao = getSessionFilters();

        if($filtros_sessao[0]->isNotEmpty()){
            foreach ($filtros_sessao[0] as $nome_filtro => $value_filter) {
                if($nome_filtro == $filtros_sessao[1]."code"){
                    $verb->where("code", $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."subordinates"){
                    $verb->where('request_salesman_id', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."client"){
                    $$verb->where('client_id', $value_filter);
                }
                if($nome_filtro == $filtros_sessao[1]."status"){
					if ($value_filter == 1)
						$verb->where('is_cancelled', 1);
					elseif ($value_filter == 2)
						$verb->where('is_approv', 1);
					elseif ($value_filter == 1)
						$verb->where('waiting_assign', 1);
					elseif ($value_filter == 1)
						$verb->where('has_analyze', 1);
                }
                if($nome_filtro == $filtros_sessao[1]."start_date"){
                    $verb->whereDate('created_at', '>=', $value_filter);
                }   
                if($nome_filtro == $filtros_sessao[1]."end_date"){
                    $verb->whereDate('created_at', '<=', $value_filter);
                }
            }
        }
		
		return view('gree_commercial.operation.verbCommercial.listAnalyze', [
			'verb' => $verb->paginate(10)
		]);
	}
	
	public function commercialBudgetAnalyze_do(Request $request) {

        $budget = App\Model\Commercial\BudgetCommercial::with('salesman.immediate_boss')
            ->ValidAnalyzeProccess($request->session()->get('r_code'))
            ->where('id', $request->id)
            ->where('waiting_assign', 0)
            ->where('is_cancelled', 0)
            ->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Sua solicitação de verbas não foi encontrado na base de dados.');

        try {

            $solicitation = new App\Services\Departaments\Commercial\RequestBudget($budget, $request);
            $do_analyze = new App\Services\Departaments\ProcessAnalyze($solicitation);

            $actions = [
                1 => 'eventApprov',
                2 => 'eventReprov',
                3 => 'eventSuspended',
                4 => 'eventRevert'
            ];

            $method = $actions[$solicitation->request->type];
            $result = $do_analyze->$method();

            return redirect()->back()->with('success', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
	}
	
	public function commercialBudgetCancel(Request $request) {

		$budget = App\Model\Commercial\BudgetCommercial::where('id', $request->id)
            ->where('is_cancelled', 0)
            ->first();

        if (!$budget)
            return redirect()->back()->with('error', 'Sua solicitação de verbas não foi encontrado na base de dados.');

        $budget->is_cancelled = 1;
        $budget->cancel_reason = $request->cancel_reason;
        $budget->cancel_r_code = $request->session()->get('r_code');
        $budget->has_analyze = 0;
        $budget->save();

        return redirect()->back()->with('success', 'Sua solicitação foi cancelada com sucesso!');
	}
	
	public function commercialBudgetPrint(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'salesman',
			'client',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report'
        )->where('id', $id)
            ->first();

        if (!$budget) {
            $r_code = $request->session()->get('salesman_data')->r_code?? '0';

            $budget = App\Model\Commercial\BudgetCommercial::with(
                'salesman',
				'client',
                'budget_commercial_duplicates',
                'budget_commercial_itens',
                'budget_commercial_report'
            )->ValidAnalyzeProccess($r_code)
                ->where('id', $id)
                ->first();

            if (!$budget)
                return redirect()->back()->with('error', 'Você não tem permissão para visualizar essa solicitação.');
        }

        return view('gree_commercial.operation.reportInvoice.printRequestVerbaCommercial', [
            'budget' => $budget,
        ]);
    }

    public function commercialBudgetCreditPrint(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'salesman',
			'client',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report',
            'rtd_analyze.users'
        )->where('id', $id)
            ->first();

        if (!$budget) {
            $r_code = $request->session()->get('salesman_data')->r_code?? '0';

            $budget = App\Model\Commercial\BudgetCommercial::with(
                'salesman',
                'budget_commercial_duplicates',
                'budget_commercial_itens',
                'budget_commercial_report',
                'client',
                'rtd_analyze.users'
            )->ValidAnalyzeProccess($r_code)
                ->where('id', $id)
                ->first();

            if (!$budget)
                return redirect()->back()->with('error', 'Você não tem permissão para visualizar essa solicitação.');
        }

        return view('gree_commercial.operation.reportInvoice.printRequestVerbaCommercialCredit', [
            'budget' => $budget,
        ]);
    }

    public function commercialBudgetPaymentPrint(Request $request, $id) {

        $budget = App\Model\Commercial\BudgetCommercial::with(
            'salesman',
			'client',
            'budget_commercial_duplicates',
            'budget_commercial_itens',
            'budget_commercial_report',
            'rtd_analyze.users'
        )->where('id', $id)
            ->first();

        if (!$budget) {
            $r_code = $request->session()->get('salesman_data')->r_code?? '0';

            $budget = App\Model\Commercial\BudgetCommercial::with(
                'salesman',
                'budget_commercial_duplicates',
                'budget_commercial_itens',
                'budget_commercial_report',
                'client',
                'rtd_analyze.users'
            )->ValidAnalyzeProccess($r_code)
                ->where('id', $id)
                ->first();

            if (!$budget)
                return redirect()->back()->with('error', 'Você não tem permissão para visualizar essa solicitação.');
        }

        return view('gree_commercial.operation.reportInvoice.printRequestVerbaCommercialPayment', [
            'budget' => $budget,
        ]);
    }
}
