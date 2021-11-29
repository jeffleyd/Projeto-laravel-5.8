<?php

namespace App\Http\Controllers;

use Hash;
use App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Model\BlogPost;
use App\Model\UserNotificationExternal;
use App\Model\UserNotificationExternalMsg;

class ApiNewsController extends Controller
{
    
    public function __construct() {   
        $this->middleware('auth:api', ['except' => [
            'login', 'passwordNew', 'userNew'
        ]]);
    }

    public function login(Request $request) {

        $user = UserNotificationExternal::where('r_code', $request->r_code)->first();
        if($user) {

            if($user->status == 0) {
                $message = "Você não tem permissão!";
                $status = 101;
            } else {
                if($user->password) {

                    if($user->status == 2) {
                        $message = "Em breve você será liberado!";
                        $status = 102;
                    } else {

                        $credentials = request(['r_code', 'password']);
                        $token = auth()->guard('api')->attempt($credentials);

                        if ($token) {
                            return $this->respondWithToken($token, $user->id, $user->name, $user->r_code);
                        } else {
                            $message = "Você errou sua senha!";
                            $status = 103; 
                        }
                    }
                } else {
                    $message = "Você não possui uma senha cadastrada!";
                    $status = 104;
                }
            }
        } else {
            $message = "Você não possui cadastro ou errou sua matrícula!";
            $status = 105;
        }
        return response()->json([
            'success' => false,
            'message' => $message,
            'status' => $status
        ]);
    }
 
    public function me() {
        return response()->json(auth()->guard('api')->user());
    }
 
    public function logout() {
        auth()->guard('api')->logout();
    
        return response()->json(['message' => 'Successfully logged out']);
    }
 
    public function refresh() {   
        return $this->respondWithToken(auth()->guard('api')->refresh());
    }
 
    protected function respondWithToken($token, $data_id = null, $name = null, $r_code = null) {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'data_id' => $data_id,
			'r_code' => $r_code,
            'name' => $name .' ('. $r_code.')'
        ]); 
    }

    public function passwordNew(Request $request) {
        
        $user = UserNotificationExternal::where('r_code', $request->r_code)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não foi encontrado',
            ], 400);
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Senha cadastrada com sucesso',
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function newsNotificationToken(Request $request) {

        try {
            $user = UserNotificationExternal::where('r_code', $request->code_reg)->first();
            if ($user) {
                $user->token = $request->token;
                $user->save();
            } else {
				
				return response()->json([
                    'success' => false,
                    'message' => 'Usuário não foi encontrado'
                ], 200);
            }

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
    
    public function userNew(Request $request) {

        $user = UserNotificationExternal::where('r_code', $request->r_code)->first();
        if(!$user) {

            try {
                $user_new = new UserNotificationExternal;
                $user_new->r_code = $request->r_code;
                $user_new->name = $request->name;
                $user_new->email = $request->email;
                $user_new->phone = $request->phone;
                $user_new->sector = $request->sector;
                $user_new->password = Hash::make($request->password);
                $user_new->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Cadastro realizado com sucesso, em breve você será liberado'
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Você já possui cadastro',
            ]);   
        }
    }

    public function newsPosts(Request $request) {
		
        $post = BlogPost::where('is_publish', 1)->orderBy('id', 'DESC');
		
		$post->where('category_id', 7)
			->orWhere(function ($query) {
				$query->where('is_publish', 1)
					->where('category_id', 6);
			});

        if($request->search != '') {
            $post->where('title_pt', 'like', '%'.$request->search.'%');
        }

        /*if ($request->sector == 1) {
            $post->where('category_id', 7)
            ->orWhere(function ($query) {
                $query->where('is_publish', 1)
                        ->where('category_id', 6);
            });

        } else if ($request->sector == 2) {
            $post->where('category_id', 100);

        } else if ($request->sector == 3) {
            $post->where('category_id', 2);

        } else if ($request->sector == 4) {
            $post->where('category_id', 1);

        } else if ($request->sector == 5) {
            $post->where('category_id', 3);

        } 
        else if ($request->sector == 6) {
            $post->where('category_id', 99);
        } */

        return response()->json([
            'success' => true,
            'posts' => $post->paginate(3),
        ], 200); 
    }

    public function newsPostSingle(Request $request) {

        $post = BlogPost::with('blog_post_attach')->find($request->id);

        return response()->json([
            'success' => true,
            'post' => $post,
        ], 200);
    }

    public function newsNotice(Request $request) {

        $notif = UserNotificationExternalMsg::where('user_notification_external_id', $request->data_id)->orderBy('id', 'DESC');
        if(!$notif) {
            return response()->json([
                'success' => false,
                'message' => 'Não há avisos',
            ]);
        }

        return response()->json([
            'success' => true,
            'notice' => $notif->paginate(5),
        ], 200);
    }
}
