<?php

namespace App\Exports;

use App\Model\FinancyLending;
use Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

class LendingExport implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct($r_code, $status, $start_date, $end_date)
    {
        $this->r_code = $r_code;
        $this->status = $status;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function query()
    {
        $lending = (new FinancyLending)->newQuery();

        if (!empty($this->r_code)) {
            $lending->where('r_code', $this->r_code);
        }

        if (!empty($this->start_date)) {
            $lending->where('created_at', '>=', $this->start_date);
        }

        if (!empty($this->end_date)) {
            $lending->where('created_at', '<=', $this->end_date);
        }
        
        if (!empty($this->status)) {
            if ($this->status == 1) {
                $lending->where('is_paid', 1);
            } else if ($this->status == 2) {
                $lending->where('mng_approv', 0) //->where('mgn_approv', 0)
                        ->where('financy_approv', 0)//->where('fny_approv', 0)
                        ->where('pres_approv', 0)
                        ->where('mng_reprov', 0)
                        ->where('financy_reprov', 0)
                        ->where('pres_reprov', 0)
                        ->orWhere(function ($query) {
                            $query->where('mng_approv', 1)
                                ->where('financy_approv', 0)
                                ->where('pres_approv', 0)
                                ->where('mng_reprov', 0)
                                ->where('financy_reprov', 0)
                                ->where('pres_reprov', 0)
								->where('r_code', $this->r_code);
                        })
                        ->orWhere(function ($query) {
                            $query->where('mng_approv', 1)
                                ->where('financy_approv', 1)
                                ->where('pres_approv', 0)
                                ->where('mng_reprov', 0)
                                ->where('financy_reprov', 0)
                                ->where('pres_reprov', 0)
								->where('r_code', $this->r_code);
                        });
            } else if ($this->status == 3) {
                $lending->where('mng_reprov', 1)
                        ->orWhere(function ($query) {
                            $query->where('financy_reprov', 1)
									->where('r_code', $this->r_code);
                        })
                        ->orWhere(function ($query) {
                            $query->where('pres_reprov', 1)
									->where('r_code', $this->r_code);
                        });
            } else if ($this->status == 4) {
                $lending->where('mng_approv', 1)
                        ->where('financy_approv', 1)
                        ->where('pres_approv', 1)
                        ->where('mng_reprov', 0)
                        ->where('financy_reprov', 0)
                        ->where('pres_reprov', 0);
            }
        }



        return $lending;
    }

    /**
    * @var FinancyLending $lending
    */
    public function map($lending): array
    {
        $status = 0;
        if ($lending->is_paid == 1) {
            $status = "Transferido";
        } else if ($lending->mng_reprov == 1 or $lending->financy_reprov == 1 or $lending->pres_reprov == 1) {
            $status = "Reprovado";
        } else if ($lending->mng_approv == 1 and $lending->financy_approv == 1 and $lending->pres_approv == 1) {
            $status = "Aprovado";
        } else {
            $status = "Em análise";
        }

        return [
            [
                $lending->id,
                $lending->r_code,
                $lending->description,
                number_format($lending->amount, 2, ".", ""),
                date('Y-m-d', strtotime($lending->created_at)),
                "",
                getENameFull($lending->r_code),
                $lending->agency,
                $lending->account,
                $lending->bank,
                $lending->identity,
                $status,
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '#ID',
            "Matricula",
            "Finalidade",
            "Empréstimo",
            "Data de criação",
            "",
            "Favorecido",
            "Agência",
            "Conta",
            "Banco",
            "CPF",
            "Status",
        ];
    }
}
