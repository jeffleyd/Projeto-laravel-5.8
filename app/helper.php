<?php
use Intervention\Image\ImageManager;
use GuzzleHttp\Client as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use App\Model\Users;
use App\Model\Countries;
use App\Model\Regions;
use App\Model\LogAccess;
use App\Model\Settings;
use App\Model\UserOnPermissions;
use App\Model\Notifications;
use App\Model\Sector;
use App\Model\BlogCategory;
use App\Classes\Extenso;

use App\Model\FinancyLending;
use App\Model\FinancyRefund;
use App\Model\FinancyRPayment;
use App\Model\FinancyAccountability;

use App\Model\SacProtocol;
use App\Model\SacAuthorized;
use App\Model\SacOsProtocol;
use Illuminate\Http\Request;

use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Jobs\SendMailAttachJob;

use Carbon\Carbon;

// My common functions

function delayQueueEmail($pattern, $email) {

    $delay = DB::table('jobs')->count()*1;
    SendMailJob::dispatch($pattern, $email)->delay($delay);

    return;
}

function email_notification($id, $type, $message_body, $subject, $trip = null) {

    if ($type == 'user') {
        $user = Users::find($id);
        $email = $user->email;
      
    } 

    if ($trip == 'request_trip_approv') {
        Mail::send('emails.trip.RequestApprov', array('mail_body' => $message_body), function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
    }
 
}

/**
 * Converte o valor para extenso.
 *
 * @param $value float
 * @return string
 */
function currencyToWords(float $value): string
{
    return Extenso::converte($value, true, false);
}

function generateRandomNumber($length = 8)
{
  $random = "";
  srand((double) microtime() * 1000000);

  $data = "123456123456789071234567890890";
  $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; // if you need alphabatic also

  for ($i = 0; $i < $length; $i++) {
          $random .= substr($data, (rand() % (strlen($data))), 1);
  }

  return $random;

}

function distanceLatLong($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

function readableBytes($num) {
    $neg = $num < 0;

    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    if ($neg){
        $num = -$num;
    }

    if ($num < 1){
        return ($neg ? '-' : '') . $num . ' B';
    }
    
    $exponent = min(floor(log($num) / log(1000)), count($units) - 1);
    
    $num = sprintf('%.02F', ($num / pow(1000, $exponent)));
    
    $unit = $units[$exponent];

    return ($neg ? '-' : '') . $num . ' ' . $unit;
}

function NotifyUser($title, $r_code, $icon, $type, $code, $url) {
    $notify = new Notifications;
    $notify->r_code = $r_code;
    $notify->icon = $icon;
    $notify->type = $type;
    $notify->code = $code;
    $notify->title = $title;
    $notify->url = $url;
    $notify->has_read = 0;
    $notify->save();

    $user = Users::where('r_code', $r_code)->first();
    if (!empty($user->token)) {

        $fields = array
            (
                'notification' => array(
                    'title' => $title,
                    'body' => $code,
                    'click_action' => 'https://gree-app.com.br/',
                    'icon' => 'https://gree-app.com.br/media/favicons/apple-touch-icon-180x180.png'
                ),
                'to'  => $user->token,
            );

        sendPushNotification($fields);

    }
    if (!empty($user->token_mobile)) {

        $fields = array
            (
                'notification' => array(
                    'title' => $title,
                    'body' => $code,
                    'click_action' => 'https://gree-app.com.br/',
                    'icon' => 'https://gree-app.com.br/media/favicons/apple-touch-icon-180x180.png'
                ),
                'to'  => $user->token_mobile,
            );

        sendPushNotification($fields);

    }


    return;
}

function sendPushNotification($fields)
{
    $API_ACCESS_KEY = 'AAAAd1Vm5Kw:APA91bGYdFCpAkMJjHDzt8TAFHyw5w_daMfTRBd51YvOQBaplhjk0ssedjZVtTKyEzrG5VXcXQlsSAIFWWf5KGKqPjLBGfh2ZPFanwmZYFlQRKnMBafzGO3CyO1iHlO5AAIlegmvogpZ';
	
	$client = new GuzzleClient();
	try {
		$response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'     => 'application/json',
				'Authorization' => 'key='. $API_ACCESS_KEY
			],
			'json' => $fields,

		]);
		$result = json_decode($response->getBody());
		return $result;
	} catch (Exception $exception) {
		// $responseBody = $exception->getResponse()->getBody(true);
		abort(600, 'Ocorreu um erro inesperado!');
	}
}

function validHoliday($perm_id, $grade = null, $can_approv = null) {

    $perms = \Session::get('my_holiday_perm');
    if (!$perms) {
        return false;
    } else if ($perms->count() == 0) {
        return false;
    } else {
        foreach ($perms as $key) {
            foreach (json_decode($key->receiver_perm, true) as $value) {
                if ($grade == null and $can_approv == null) {
                    if ($value['perm_id'] == $perm_id)
                        return true;
                } else if ($grade == null) {
                    if ($value['perm_id'] == $perm_id and $value['can_approv'] == $can_approv)
                        return true;
                } else if ($can_approv == null) {
                    if ($value['perm_id'] == $perm_id and $value['grade'] == $grade)
                        return true;
                } else {
                    if ($value['perm_id'] == $perm_id and $value['grade'] == $grade and $value['can_approv'] == $can_approv)
                        return true;
                }
            }
        }
        return false;
    }
}

function registerTaskinJira($title, $description, $type) {

    $token = 'RPHyNfaXducmrtMVL0a7D466';
    $email = 'jefferson.silva@gree-am.com.br';
    $issue = $type == 1 ? "Story" : 'Bug';
    $fields = array
    (
        'fields' => array(
            'project' => array(
                'key' => 'GDB',
            ),
            'summary' => $title,
            'description' => $description,
            'issuetype' => array(
                'name' => $issue,
            ),
        )
    );
	
	$client = new GuzzleClient();
	try {
		$response = $client->request('POST', 'https://greebrasil.atlassian.net/rest/api/2/issue/', [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'     => 'application/json',
			],
			'auth' => [$email, $token],
			'json' => $fields,

		]);
		$result = json_decode($response->getBody());
		return $result->key;
	} catch (Exception $exception) {
		// $responseBody = $exception->getResponse()->getBody(true);
		abort(600, 'Ocorreu um erro inesperado!');
	}
	
}

function registerCommentJira($key, $description) {

    $token = 'RPHyNfaXducmrtMVL0a7D466';
    $email = 'jefferson.silva@gree-am.com.br';

    $fields = array
    (
        'body' => $description
    );
	
	$client = new GuzzleClient();
	try {
		$response = $client->request('POST', 'https://greebrasil.atlassian.net/rest/api/2/issue/'. $key .'/comment/', [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'     => 'application/json',
			],
			'auth' => [$email, $token],
			'json' => $fields,

		]);
		$result = json_decode($response->getBody());
		return $result;
	} catch (Exception $exception) {
		// $responseBody = $exception->getResponse()->getBody(true);
		abort(600, 'Ocorreu um erro inesperado!');
	}
}

function optAuthGoogleAuthentication($code, $name, $desc) {

    $fields = array();

	$client = new GuzzleClient();

	try {
		$response = $client->request('GET', 'https://gree-app.com.br:3000/twofa/criar?name='. $name .'&code='.$code, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'     => 'application/json',
			],
			'json' => $fields,
		]);
		$result = json_decode($response->getBody()->getContents());

        $secret = $result->secret;

		return  (object)[
            'secret' => $secret,
            'qr' => QrCode::size('200')->generate('otpauth://totp/'.$desc.'?secret='.$secret.'')
        ];

	} catch (Exception $exception) {
		// $responseBody = $exception->getResponse()->getBody(true);
		abort(600, 'Ocorreu um erro inesperado!');
	}
}


function optAuthGoogleAuthenticationVerify($code, $pin) {

    $fields = array();

	$client = new GuzzleClient();
	try {
		$response = $client->request('GET', 'https://gree-app.com.br:3000/twofa/verificar?token='. $code .'&code='.$pin, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'     => 'application/json',
			],
			'json' => $fields,

		]);
		$result = $response->getBody()->getContents();
		return $result;
	} catch (Exception $exception) {
		// $responseBody = $exception->getResponse()->getBody(true);
		return null;
	}
}

function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}


function googleRecaptchaV3($token, $action) {
    $RECAPTCHA_V3_SECRET_KEY = '6LeKg8wZAAAAAKD2ds0F_Rd_aZVXTeEpCsRnhtwW';
	$fields = array('secret' => $RECAPTCHA_V3_SECRET_KEY, 'response' => $token);
	
	$client = new GuzzleClient();
	try {
		$response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
			'query' => $fields,

		]);
		$result = json_decode($response->getBody(), true);
		
		// verify the response
		if($result["success"] == '1' && $result["action"] == $action && $result["score"] >= 0.5) {

			return true;
		} else {
			return false;
		}
	} catch (Exception $exception) {
		// $responseBody = $exception->getResponse()->getBody(true);
		return null;
	}
}

function LogSystem($desc, $r_code) {
    $log = new LogAccess;
    $log->ip = \Request::getClientIp(true);
    $log->r_code = $r_code;
    $log->description = $desc;
    $log->save();

    return;
}

function getConfig($table) {
    $setting = Settings::where('command', $table)->first();
    if ($setting) {

        return $setting->value;
    } else {

        return null;
    }
}

function updateUser($r_code, $version) {
    $user = Users::where('r_code', $r_code)->first(); 
    if ($user) {
        $version = getConfig("version_number");
        Session::put('user_version', $version);
        $user->version = $version;
        $user->save();

        return;
    } else {

        return;
    }
    
}

function linkConstructGoogle($str) {
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$str);
}

function strWordCut($string, $length, $end='...')
{
    $string = strip_tags($string);

    if (strlen($string) > $length) {

        // truncate string
        $stringCut = substr($string, 0, $length);

        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')).$end;
    }
    return $string;
}

function getBlogCategory($id) {
    $category = BlogCategory::find($id);

    if ($category) {
        if (Session::get('lang') == 'en') {

            return $category->name_en;
        } else {

            return $category->name_pt;
        }
    } else {
        return;
    }
}

function getNameFormated() {
    $user = Users::where('r_code', Session::get('r_code'))->first();
    if ($user) {

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

        return $user->first_name ." ". strtoupper($lastname);
    } else {

        return null;
    }
}

function hasPerm($perm) {
    // $user = UserOnPermissions::where('perm_id', $perm)->where('user_r_code', Session::get('r_code'))->first();
    $permissoes_usuario = Session::get('permissoes_usuario');
    $user=null;
    if($permissoes_usuario){
        $user = $permissoes_usuario->where('perm_id',$perm)->first();
    }

    if ($user) {
        return true;
    } else if (validHoliday($perm) == true) {
        return true;
    } else {
        return false;
    }
}

function hasPermApprov($perm) {
    // $user = UserOnPermissions::where('perm_id', $perm)->where('can_approv', 1)->where('user_r_code', Session::get('r_code'))->first();

    $permissoes_usuario = Session::get('permissoes_usuario');
    $user=null;
    if($permissoes_usuario){
        $user = $permissoes_usuario->where('perm_id',$perm)->where('can_approv', 1)->first();
    }
    if ($user) {
        return true;
    }  else if (validHoliday($perm, null, 1) == true) {
        return true;
    } else {
        return false;
    }
}

function hasPermManager($perm) {
    // $m_1 = UserOnPermissions::where('perm_id', $perm)->where('grade', 99)->where('user_r_code', Session::get('r_code'))->first();

    $m_1 = null;
    $permissoes_usuario = Session::get('permissoes_usuario');
    if($permissoes_usuario){
        $m_1 = $permissoes_usuario->where('perm_id',$perm)->where('grade', '>=', 11)->first();
    }

    if ($m_1) {
        return true;
    }  else if (validHoliday($perm, 99, null) == true) {
        return true;
    } else {
        return false;
    }
}

function getENameFull($r_code) {
    $user = Users::where('r_code', $r_code)->first();
    if ($user) {

        return $user->first_name ." ". $user->last_name;
    } else {

        return null;
    }
}

function getNameAuthorizedFull($id) {
    $data = SacAuthorized::find($id);
    if ($data) {

        return $data->name;
    } else {

        return null;
    }
}

function getENameF($r_code) {
    $user = Users::where('r_code', $r_code)->first();
    if ($user) {

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

        return $user->first_name ." ". strtoupper($lastname);
    } else {

        return null;
    }
}

function finalityName($id) {
    switch ($id) {
        case 1:
            return __('trip_i.finality_1');
            break;

        case 2:
            return __('trip_i.finality_2');
            break;

        case 3:
            return __('trip_i.finality_3');
            break;

        case 4:
            return __('trip_i.finality_4');
            break;

        case 5:
            return __('trip_i.finality_5');
            break;

        case 6:
            return __('trip_i.finality_6');
            break;

        case 7:
            return __('trip_i.finality_7');
            break;

        case 8:
            return __('trip_i.finality_8');
            break;
        
        case 9:
            return __('trip_i.finality_9');
            break;
        
        default:
            return __('trip_i.finality_99');
            break;
    }
}

function periodName($id) {
    switch ($id) {
        case 1:
            return __('trip_i.period_1');
            break;

        case 2:
            return __('trip_i.period_2');
            break;

        case 3:
            return __('trip_i.period_3');
            break;
        case 4:
            return __('trip_i.period_4');
            break;
    }
}

function sectorName($id) {
    $sector = Sector::find($id);
    if ($sector) {

        return __('layout_i.'. $sector->name .'');
    } else {

        return '';
    }
    
}

function refundType($id) {
    $refundType = [
        1 => 'COMBUSTÍVEL',
        2 => 'TAXI',
        3 => 'UBER/99',
        4 => 'PASSAGEM AÉREA',
        5 => 'HOSPEDAGEM',
        6 => 'ALIMENTAÇÃO',
        7 => 'OUTROS',
    ];

    if(array_key_exists($id, $refundType)){
        return $refundType[$id];
    }else{
        return $refundType[7];
    }
}

function currency($id) {
    $currency = [
        1 => 'BRL',
        2 => 'USD',
        3 => 'CNY',
        4 => 'HKD',
    ];

    if(array_key_exists($id, $currency)){
        return $currency[$id];
    }else{
        return $currency[1];
    }
    // 125,45
}
function getCurrency($id) {

    // $placement='before', $currency_precision=2, $decimal_separator=',', $thousands_separator='.'
    $currency = [
        1 => ['code'=>'BRL','simbol'=>'R$ ',     'placement'=>'before', 'currency_precision'=>2, 'decimal_separator'=>',', 'thousands_separator'=>'.'],
        2 => ['code'=>'USD','simbol'=>'$',      'placement'=>'before', 'currency_precision'=>2, 'decimal_separator'=>'.', 'thousands_separator'=>','],
        3 => ['code'=>'CNY','simbol'=>'¥',      'placement'=>'before', 'currency_precision'=>2, 'decimal_separator'=>'.', 'thousands_separator'=>','],
        4 => ['code'=>'HKD','simbol'=>'HK$',    'placement'=>'before', 'currency_precision'=>2, 'decimal_separator'=>'.', 'thousands_separator'=>','],
    ];

    if(array_key_exists($id, $currency)){
        return $currency[$id];
    }else{
        return $currency[1];
    }

}

function GetStateName($ctry, $ste) {
    $state = DB::table('regions')
    ->where('country_id', $ctry)
    ->where('id', $ste)
    ->first();

    if ($state) {
        return $state->name;
    } else {
        return;
    }
}

function GetCountryName($ctry) {
    $country = DB::table('countries')
    ->where('id', $ctry)
    ->first();

    if ($country) {
        return $country->name;
    } else {
        return;
    }

}

function send_push($user_id, $message) {

    require_once 'fcm/FCM_user.php';


    $fcm = new FCMUser();
    
    $user_id = array($user_id);
    $fcm->send_notification($user_id, $message);
    
}

function paymentReceipt($id, $type, $receipt) {
    
    
    $module = "";
    if ($type == 1) {

        $module = FinancyLending::find($id);

    } else if ($type == 2) {

        $module = FinancyRefund::find($id);

    } else if ($type == 3) {

        $module = SacOsProtocol::find($id);
          
    }

    if ($module) {
        $module->is_paid = 1;
        $module->receipt = $receipt;
        $module->save();

    }

    return;
    
}

function findAttachPayment($id, $type) {

    $module = [
        1 => FinancyLending::class,
        2 => FinancyRefund::class,
        3 => SacOsProtocol::class,
        4 => FinancyAccountability::class,
    ];

    $array = array(
        'type' => 0,
        'id' => 0,
        'code' => ''
    );
    if(array_key_exists($type, $module)){
        $model = $module[$type]::find($id);

        if ($model) {
            $array = array(
                'type' => $type,
                'id' => $model->id,
                'code' => $model->code
            );
            return $array;
        }
    }

    return $array;
}

function countIntection($all_interaction) {

    $i = 0;
    if ($all_interaction) {
        foreach ($all_interaction as $item) {
            if ($item->r_code == null and $item->authorized_id == 0 and  $item->is_system == 0) {
                $i++;
            } else {
                $i = 0;
            }
        }
    }

    return $i;
}

function processItemAnalyze($data, $msg, $sector, $status, $type, $date = null) {

    $item = array();

    if ($type != 4) {

        if (isset($data->r_code)) {
            $users = array([
                'r_code' => $data->r_code,
                'name' => getENameF($data->r_code),
            ]);
        } else {
            $users = $data;
        }
    } else {
        $users = $data;
    }

    $item['status'] = $status;
    $item['users'] = $users;
    // Type 1 = approv, 2 = repprov, 3 = suspending, 4 = in coming
    $item['type'] = $type;
    $item['message'] = $msg;
    $item['sector'] = $sector;
    $created = $date != null ? $date : $data->created_at;
    $item['created_at'] = date('d-m-Y', strtotime($created));

    return $item;
}

function sacCreateProtocol($id) {
    
    $protocol = SacProtocol::find($id);
    $code = 'G'. date('Y') .''. rand(1000, 9999) .''. $protocol->id;
    $protocol->code = $code;
    $protocol->save();

    return $code;
}

function sectors($id) {
    $sectors = [
        1 => 'Comercial',
        2 => 'Industrial',
        3 => 'Financeiro',
        4 => 'Expedição & Recebimento',
        5 => 'Importação',
        6 => 'Administração',
        7 => 'RH',
        8 => 'Compras',
        9 => 'TI',
        10 =>'Manutenção',
        112 => 'Jurídico'
    ];

    if(array_key_exists($id, $sectors)){
        return $sectors[$id];
    }else{
        return $sectors[1];
    }
}

function stringCut($string, $length, $end='...')
{
    $string = strip_tags($string);

    if (strlen($string) > $length) {

        // truncate string
        $string = substr($string, 0, $length).$end;
    }
    return $string;
}

function codeSegmentBase($id, $type, $request, $r_code = "") {
    // Type = 1 ['Lending']; Type = 2 ['Refund']; Type = 3 ['Payment'];

    switch ($type) {
        case 1:
            $item = FinancyLending::find($id);

            $s = FinancyLending::orderBy('id', 'DESC')->skip(1)->take(1)->get();
            if (count($s) > 0) {
                $n = $s[0]->segment;
            } else {
                $n = 0000000;   
            }

            // SEARCH USER FOR GET SECTOR
            if ($r_code) {
                $user = Users::where('r_code', $r_code)->first();
            } else {
                $user = Users::where('r_code', $request->session()->get('r_code'))->first();
            }

            $sector = sectors($user->sector_id);
            $cunid = str_pad($n + 1, 7, 0, STR_PAD_LEFT);
            $code =  strtoupper(substr($sector, 0, 3)) .'EM'. date('ym') .''. $cunid;
            $item->code = $code;
            $item->segment = $cunid;
            $item->save();

            return;
        break;

        case 2:
            $item = FinancyRefund::find($id);

            $s = FinancyRefund::orderBy('id', 'DESC')->skip(1)->take(1)->get();
            if (count($s) > 0) {
                $n = $s[0]->segment;
            } else {
                $n = 0000000;   
            }

            // SEARCH USER FOR GET SECTOR
            if ($r_code) {
                $user = Users::where('r_code', $r_code)->first();
            } else {
                $user = Users::where('r_code', $request->session()->get('r_code'))->first();
            }
            $sector = sectors($user->sector_id);
            $cunid = str_pad($n + 1, 7, 0, STR_PAD_LEFT);
            $code =  strtoupper(substr($sector, 0, 3)) .'RE'. date('ym') .''. $cunid;
            $item->code = $code;
            $item->segment = $cunid;
            $item->save();

            return;
        break;

        case 3:
            $item = FinancyRPayment::find($id);

            $s = FinancyRPayment::orderBy('id', 'DESC')->skip(1)->take(1)->get();
            if (count($s) > 0) {
                $n = $s[0]->segment;
            } else {
                $n = 0000000;   
            }

            // SEARCH USER FOR GET SECTOR
            if ($r_code) {
                $user = Users::where('r_code', $r_code)->first();
            } else {
                $user = Users::where('r_code', $request->session()->get('r_code'))->first();
            }
            $sector = sectors($user->sector_id);
            $cunid = str_pad($n + 1, 7, 0, STR_PAD_LEFT);
            $code =  strtoupper(substr($sector, 0, 3)) .'PA'. date('ym') .''. $cunid;
            $item->code = $code;
            $item->segment = $cunid;
            $item->save();

            return;
        break;
        
        default:
            return '0';
                break;
    }

    $protocol = SacProtocol::find($id);
    $code = 'G'. date('Y') .''. rand(1000, 9999) .''. $protocol->id;
    $protocol->code = $code;
    $protocol->save();

    return $code;
}

function uploadS3($seq = 1, $img, $request) {

    $file = $img;
    $extension = $img->extension();
    if ($extension != 'php' and $extension != 'html' and $extension != 'exe' and $extension != 'sh') {

        $validator = Validator::make(
                    [
                    'file' => $file,
                    ],
                    [
                    'file' => 'required|max:50000',
                    ]
        );

        if ($validator->fails()) { 
	
            $request->session()->put('error', "Tamanho máximo da imagem é de 50mb, diminua a resolução/tamanho da mesma.");
            return array('success' => false, 'url' => '');
        } else {

            $name_file = $seq .'-'. date('YmdHis') .'.'. $extension;
            if ($extension == 'jpg' or $extension == 'jpeg' or $extension == 'png' or $extension == 'gif' or $extension == 'webp') {

				try {
					$manager = new ImageManager;
					$image_resize = $manager->make($file->getRealPath());   

					// resize the image to a width of 800 and constrain aspect ratio (auto height)
					$image_resize->resize(800, null, function ($constraint) {
						$constraint->aspectRatio();
					})->encode($extension, 100);

					Storage::disk('s3')->put($name_file, $image_resize);
				} catch (Exception $e) {
					$request->session()->put('error', "Arquivo ilegível, error: ". $e->getMessage());
					return array('success' => false, 'url' => '');
				}
            } else {

                $img->storeAs('/', $name_file, 's3');
            }
            
            $url = Storage::disk('s3')->url($name_file);
            return array('success' => true, 'url' => $url);
        }
        
    } else {
	
        $request->session()->put('error', "o formato: (". $extension .") do arquivo não é suportado em nosso servidor.");
        return array('success' => false, 'url' => '');
    }

}

function formatCnpjCpf($value)
{
  $CPF_LENGTH = 11;
  $cnpj_cpf = preg_replace("/\D/", '', $value);
  
  if (strlen($cnpj_cpf) === $CPF_LENGTH) {
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
  } 
  
  return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function removeS3($link_file) {

    $file = Str::replaceFirst('https://s3.amazonaws.com/gree-app.com.br/', '', $link_file);

    $return = Storage::disk('s3')->delete($file);
    return $return;

}

function total_voice_sms($phone, $msg) {


    $fields = array
    (
        'numero_destino' => $phone,
        'mensagem' => $msg,
    );
	
	$client = new GuzzleClient();
	try {
		$response = $client->request('POST', 'https://api2.totalvoice.com.br/sms', [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'     => 'application/json',
				'Access-Token' => 'c6481a8eae89e64b92cc237d7de5c3ef'
			],
			'json' => $fields,

		]);
		$result = json_decode($response->getBody());
		return $result;
	} catch (Exception $exception) {
		return null;
	}

}

function total_voice_call($phone) {


    $fields = array
    (
        'numero_origem' => $phone,
        'numero_destino' => 4001,
        'data_criacao' => '',
        'gravar_audio' => false,
        'bina_origem' => '',
        'bina_destino' => '',
        'tags' => '',
        'detecta_caixa' => true,
    );
	
	$client = new GuzzleClient();
	try {
		$response = $client->request('POST', 'https://api.totalvoice.com.br/chamada', [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'     => 'application/json',
				'Access-Token' => 'c6481a8eae89e64b92cc237d7de5c3ef'
			],
			'json' => $fields,

		]);
		
		$result = json_decode($response->getBody(), true);
		if ($result['sucesso'] == true) {
			return $result['dados']['id'];
		} else {
			Log::error('total_voice call response '. $result['mensagem']);
			return null;
		}
	} catch (Exception $exception) {
		return null;
	}

}

function putSession(Request $request, $array_input = [], $prefix = "filter_"){

    foreach ($array_input as $key => $input) {
        
        if (!empty($request->input( $input))) {
            if(!empty($prefix)){
                $request->session()->put("{$prefix}{$input}", $request->input($input));
            }else{
                $request->session()->put($input, $request->input($input));
            }
        }else{
            if(!empty($prefix)){
                $request->session()->forget("{$prefix}{$input}");
            }else{
                $request->session()->forget($input);
            }
        }
    }

    return $array_input;

}

function getSessionFilters($prefix = 'filter_'){

    $session_values = collect(session()->all());
    
    $arr_input = collect();
    $filtered = $session_values->filter(function ($value, $key) use($prefix,$arr_input){
        $contains = Str::contains($key, $prefix);
        $replaced = Str::replaceFirst($prefix, '', $key);
        if($contains){
            $arr_input[$replaced]=($value);
        }
        return $contains;
    });

    return array(
        $filtered,
        $prefix,
        $arr_input,
    );
}

function getCodeModule($module_name,$r_code="", $NotSector = 0)
{
    $model = \App\Model\ModuleCounters::where('key', $module_name)
        ->firstOrFail();

    if ($NotSector == 0) {
        if ($r_code) {
            $user = Users::where('r_code', $r_code)->first();
        } else {
            $user = Users::where('r_code', session()->get('r_code'))->first();
        }
        $sector = sectors($user->sector_id);
        $model->setSector($sector);
    } else {
        $model->setSector("");
    }

    $model->increment('value');
    // segment

    return $model->code;
}

function formatMoney($value, $currency_id=1, $show_simbol=true){
    $currency = getCurrency($currency_id);

    $money = number_format(
        $value, $currency['currency_precision'], $currency['decimal_separator'],  $currency['thousands_separator']
    );

    if($currency['placement'] && $show_simbol) {
        if($currency['placement'] == 'before') {
            return $currency['simbol'].$money;
        }
        if($currency['placement'] == 'after') {
            return $money.$currency['simbol'];
        }
    }

    return $money;
}

function getFinancyRequestCategory(){

    $category = [
        1 => ['value'=>'1','desc'=>'Pagamento a fornecedores'],
        2 => ['value'=>'2','desc'=>'Pagamento a funcionários'],
        3 => ['value'=>'3','desc'=>'Pagamento de encargos sociais/Trabalhistas'],
        4 => ['value'=>'4','desc'=>'Pagamento de tributos'],
        5 => ['value'=>'5','desc'=>'Pagamento de juros e multas fiscais'],
        6 => ['value'=>'6','desc'=>'Pagamento de tarifas bancárias'],
        7 => ['value'=>'7','desc'=>'Pagamento de empréstimo/Reembolso'],
        8 => ['value'=>'8','desc'=>'Investimentos'],
        9 => ['value'=>'9','desc'=>'Pagamento de despesas administrativas'],
        10 => ['value'=>'10','desc'=>'Outros pagamentos'],
    ];


    return $category;
}

function LogObservationHistory($columns=array('model_class_origin'=>null,
                                                'model_id'=>null,
                                                'r_code'=>null,
                                                'description'=>null,
                                                )) {
    
    $observationHistory = new \App\Model\FinancyAccountabilityObservationHistory;

    foreach ($columns as $key => $column) {
        $observationHistory->{$key} = $column;
    }
    $observationHistory->save();

    return;
}

/**
 * @param $data [SalesmanTablePrice em Objeto ou Array]
 * @return object
 */
function commercialTablePriceConvertValue($data) {
    if (is_array($data))
        $table = (object) $data;
    else
        $table = $data;

    $new_table = [];

    if ($table->is_programmed) {
        $new_table['is_programmed'] = 'Sim';
    } else {
        $new_table['is_programmed'] = 'Não';
    }

    if ($table->is_suframa == 2) {
        $new_table['is_suframa'] = 'Sim';
    } else {
        $new_table['is_suframa'] = 'Não';
    }

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

    $new_table['type_client'] = isset($type_client[$table->type_client]) ? $type_client[$table->type_client] : 'Não definido';

    $new_table['descont_extra'] = $table->descont_extra .'%';

    $charge = [
        10 => 'Carga completa',
        11 => 'Carga de 51% a 90%',
        12 => 'Carga menor que 50%',
    ];

    $new_table['charge'] = isset($charge[$table->charge]) ? $charge[$table->charge] : 'Não definido';

    $new_table['contract_vpc'] = $table->contract_vpc .'%';

    $new_table['average_term'] = $table->average_term .' dias';

    $pis_confis = [
        15 => 'Lucro Real (CNPJ)',
        16 => 'Lucro Presumido (CNPJ)',
        17 => 'Consumidor (CPF)',
        24 => 'Simplificado (CNPJ)',
        25 => 'Outros Clientes (CNPJ)',
    ];

    $new_table['pis_confis'] = isset($pis_confis[$table->pis_confis]) ? $pis_confis[$table->pis_confis] : 'Não definido';

    $cif_fob = [
        26 => 'Manaus',
        27 => 'RR/AC/RO/AP/PA',
        28 => 'NORDESTE',
        29 => 'SUDESTE',
        30 => 'CENTROESTE',
        31 => 'SUL',
    ];

    $new_table['cif_fob'] = isset($cif_fob[$table->cif_fob]) ? 'FOB ('.$cif_fob[$table->cif_fob].')' : 'CIF';

	 $icms = [
        19 => '12%',
        21 => '18%',
    ];
	
	$new_table['icms'] = isset($icms[$table->icms]) ? $icms[$table->icms] : '0%';
    $new_table['adjust_commercial'] = $table->adjust_commercial .'%';
	
	$new_table['date_condition'] = isset($table->date_condition) ? date('Y-m', strtotime($table->date_condition)) : '';
	$new_table['description_condition'] = isset($table->description_condition) ? $table->description_condition : '';

    return (object) $new_table;
}

/**
 * @param int $type
 * @return string
 */
function typeAdjuste($type) {

    $arr = [
        1 => 'Grupo',
        2 => 'Quente/Frio',
        3 => 'Frio',
        4 => 'Baixa Cap/Grupo',
        5 => 'Alta Cap/Grupo',
        6 => 'Todos',
        7 => 'Produtos',
    ];

    return isset($arr[$type]) ? $arr[$type] : 'Sem reajuste';
}

/**
 *
 * Cálculo VPC
 *
 * 1 - líquido:
 *     Valor dedução = Valor total nfe - Valor ICMS - Valor PIS - Valor CONFINS
 *     Valor VPC = (Valor dedução * Contrato VPC(%)) / 100
 *
 * 2 - Bruto:
 *     Valor VPC = (Valor total nfe * Contrato VPC(%)) / 100
 *
 *
 * @param $type_vpc
 * @param $contract_vpc
 * @param $total_nfe
 * @param null $total_icms
 * @param null $total_pis
 * @param null $total_confins
 * @return array
 */
function calculateVPC(
    $type_vpc,
    $contract_vpc,
    $total_nfe,
    $total_icms = null,
    $total_pis = null,
    $total_confins = null
) {

    $total_vpc = 0;
    if($type_vpc == 1) {
        $total = $total_nfe - $total_icms - $total_pis - $total_confins;
        $total_vpc = ($total * $contract_vpc) / 100;
    }
    else {
        $total_vpc = ($total_nfe * $contract_vpc) / 100;
    }

    return [
        'total_vpc' => $total_vpc,
        'total_gross' => $total_nfe,
        'total_liquid' => $total_nfe - $total_icms - $total_pis - $total_confins
    ];
}

/**
 * Redirect the user no matter what. No need to use a return
 * statement. Also avoids the trap put in place by the Blade Compiler.
 *
 * @param string $url
 * @param int $code http code for the redirect (should be 302 or 301)
 */
function redirect_now($url, $code = 302)
{
    try {
        \App::abort($code, '', ['Location' => $url]);
    } catch (\Exception $exception) {
        // the blade compiler catches exceptions and rethrows them
        // as ErrorExceptions :(
        //
        // also the __toString() magic method cannot throw exceptions
        // in that case also we need to manually call the exception
        // handler
        $previousErrorHandler = set_exception_handler(function () {
        });
        restore_error_handler();
        call_user_func($previousErrorHandler, $exception);
        die;
    }
}

   function bossToBoss($arr, $immediates, $version = 1, $start_position = 1) {

        $arr = recursiveFuncImdts($arr, $immediates, $start_position, $version);
        $pos = count($arr) ? $arr[count($arr)-1]['position'] : 0;

        return [
            'arr_approv' => $arr,
            'last_position' => $pos
        ];
    }

    function recursiveFuncImdts($arr, $immediates, $pos, $version) {

        if ($pos != 1)
            if ($immediates->where('r_code', '0004')->count())
                return $arr;

        foreach ($immediates as $index => $immediate) {
            array_push($arr, [
                'version' => $version,
                'r_code' => $immediate->r_code,
                'position' => $pos
            ]);

            if ($immediates->count() == ($index+1)) {
                $get_bosses = $immediate->immediates()->get();
                if ($get_bosses->count()) {
                    $pos++;
                    $arr = recursiveFuncImdts($arr, $get_bosses, $pos, $version);
                }
            }
        }

        return $arr;
    }
	
	function arrayRangeTime($start_time, $end_time, $interval) {

        $start = new Carbon($start_time);
        $end  = (new Carbon($end_time))->addDay();

        $result = array();
        while ($start < $end) {
            array_push($result, $start->isoFormat('HH:mm'));
            $start->addMinutes($interval);
        }
        return $result;
    }

?>