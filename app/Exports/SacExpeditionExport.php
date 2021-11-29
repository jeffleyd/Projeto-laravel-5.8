<?php

namespace App\Exports;

use App\Model\SacExpeditionRequest;
use Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

class SacExpeditionExport implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct($start, $end, $status)
    {
        $this->start = $start;
        $this->end = $end;
        $this->status = $status;
    }

    public function query()
    {

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

        if (!empty($this->start) and !empty($this->end)) {
            $ser->where('sac_expedition_request.updated_at', '>=', $this->start)
            ->where('sac_expedition_request.updated_at', '<=', $this->end);
        }
        
        if (!empty($this->status)) {
            if ($this->status == 1) {
                $ser->where('sac_expedition_request.is_completed', 0);
            } else if ($this->status == 2) {
                $ser->where('sac_expedition_request.is_completed', 1);
            }
        }

        return $ser;
    }

    /**
    * @var SacExpeditionRequest $ser
    */
    public function map($ser): array
    {
        $status = 0;
        $upd = '--';
        if ($ser->is_completed == 1) {
            $status = 'Concluído';
            $upd = date('d-m-Y', strtotime($ser->updated_at));
        } else  {
            $status = 'A caminho';
        }

        $code = ''; 
        if($ser->is_expedition == 1) {
            $code =  $ser->sac_os_protocol_code;
        } 
        else if($ser->is_expedition == 2){
            $code = $ser->buy_part_code;
        } 
        else {
            $code = $ser->remittance_part_code;
        }

        return [
            [
                $ser->id,
                $code,
                $ser->nf_number,
                $ser->code_track,
                date('d-m-Y', strtotime($ser->arrival_forecast)),
                $upd,
                $status,
                'R$ '. number_format($ser->total, 2, ',', '.'),
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '#ID',
            'Código',
            'Nota fiscal',
            'Código de rastreamento',
            'Previsão de chegada',
            'Chegou em',
            'Status',
            'Total',
        ];
    }
}
