<?php 

namespace App\Services\Departaments\Logistics;

use App;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Trait LogisticsTrait {

    public function defaultEmail($arr_users, $subject, $title, $link, $name_link, $content = '', $single = false, $type = false, $observers_type) {

        if($observers_type == 1) {
            $observers = $this->model->logistics_warehouse->analyze_observ->map(function ($item, $key) {
                return $item->users->email;
            });
        } else {
            $observers = $this->model->rtd_observers()->map(function ($item, $key) {
                return $item->users->email;
            });
        }

        $type = $this->model->is_entry_exit == 1 ? 'ENTRADA' : 'SAÍDA';
        $type_request = $type == false ? "\n Tipo de solicitação:".$type : '';

        foreach ($arr_users as $key) {

            $pattern = array(
                'title' => $title,
                'description' => nl2br("Código: ".$this->model->code.
                                        "\n Razão: ".$this->model->type_reason_name.
                                        $type_request.
                                        "\n".$content.
                                        "\n<p style='text-align: center;'>Acesse o link abaixo".
                                        "\n<a href='".$this->request->root()."$link' target='_blank'>$name_link</a></p>"
                ),
                'copys' => $observers->toArray(),
                'template' => 'misc.Default',
                'subject' => $subject
            );

            $r_code = $single ? $key->r_code : $key->users->r_code;
            $email = $single ? $key->email : $key->users->email;

            NotifyUser(''.$title.' ('. $this->model->code.')', $r_code, 'fa-exclamation', 'text-info', 'clique aqui para ver mais detalhes.', $this->request->root() . $link);
            SendMailJob::dispatch($pattern, $email);
        }
    }

    public function execMethods($methods): array
    {
        $arr_methods = [];
        foreach ($methods as $method) {
            if ($method) {
                $result = $this->$method();
                $arr_methods[] = ['name' => $method, 'result' => $result];
            }
        }
        return $arr_methods;
    }

    public function loadScheduleVisitorHtml($schedule) {

        $html = '';
        $html .= 
        '<p><table style="text-align: center;margin: auto;">
            <thead>
                <tr role="row">
                    <th style="border: 1px solid #ddd;">Tipo</th>
                    <th style="border: 1px solid #ddd;">Data Liberação</th>
                    <th style="border: 1px solid #ddd;">Restrição</th>
                    <th style="border: 1px solid #ddd;">Encaminhamento</th>
                </tr>
            </thead>
            <tbody>';
            foreach($schedule as $key) {
                
                $type = $key->is_entry_exit == 1 ? 'ENTRADA' : 'SAÍDA';
                $restriction = $key->entry_restriction != '' ? $key->entry_restriction : '-';

                $html .= 
                '<tr>
                    <td style="border: 1px solid #ddd;padding-left: 7px;padding-right: 7px;">'.$type.'</td>
                    <td style="border: 1px solid #ddd;padding-left: 7px;padding-right: 7px;">'.date('d/m/Y H:i', strtotime($key->date_hour)).'</td>
                    <td style="border: 1px solid #ddd;padding-left: 7px;padding-right: 7px;">'.$restriction.'</td>
                    <td style="border: 1px solid #ddd;padding-left: 7px;padding-right: 7px;">'.$key->request_forwarding.'</td>
                </tr>';
            }    
        $html .= 
            '</tbody>    
        </table></p>';

        //return $html;
        return str_replace("\r\n", "", $html);
    }
}