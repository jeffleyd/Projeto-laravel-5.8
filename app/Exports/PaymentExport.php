<?php

namespace App\Exports;

use App\Model\FinancyRPayment;
use Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

class PaymentExport implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct($r_code, $request_category, $start, $end)
    {
        $this->r_code = $r_code;
        $this->request_category = $request_category;
        $this->start = $start;
        $this->end = $end;
    }

    public function query()
    {
        $frp = FinancyRPayment::leftJoin('users as r','financy_r_payment.request_r_code','=','r.r_code')
                    ->leftJoin('users as b','financy_r_payment.recipient_r_code','=','b.r_code')
                    ->select('financy_r_payment.*', 'r.first_name as r_first_name', 'r.last_name as r_last_name', 'b.first_name as b_first_name', 'b.last_name as b_last_name')
                    ->orderBy('financy_r_payment.id', 'DESC');

        if (!empty($this->start) and !empty($this->end)) {
            $frp->where('financy_r_payment.created_at', '>=', $this->start)
            ->where('financy_r_payment.created_at', '<=', $this->end);
        }
        
        if (!empty($this->request_category)) {
            $frp->where('financy_r_payment.request_category', $this->request_category);
        }

        if (!empty($this->r_code)) {
            $frp->where('financy_r_payment.request_r_code', $this->r_code);
        }

        return $frp;
    }

    /**
    * @var FinancyRPayment $frp
    */
    public function map($frp): array
    {
        $rec = "";
        if (empty($frp->recipient_r_code)) {
             $rec = $frp->recipient;
        } else {
            $rec = $frp->b_first_name .' '. $frp->b_last_name;
        }

        return [
            [
                $frp->id,
                $frp->description,
                $frp->r_first_name .' '. $frp->r_last_name,
                $rec,
                'R$ '. number_format($frp->amount_liquid, 2, ',', '.'),
                date('Y-m-d', strtotime($frp->created_at)),
                date('Y-m-d', strtotime($frp->due_date)),
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '#ID',
            'Conteúdo',
            'Solicitante',
            'Beneficiário',
            'Quantia',
            'Criado em',
            'Vencimento',
        ];
    }
}
