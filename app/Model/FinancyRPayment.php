<?php

namespace App\Model;

use App\Model\Services\Analyze\ProcessAnalyze;

class FinancyRPayment extends ProcessAnalyze
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_r_payment';
    protected $appends = [
        'sub_total'
    ];
    protected $dates = [
        'due_date',
    ];

    public function financy_r_payment_attach() {
        return $this->hasMany(FinancyRPaymentAttach::class);
    }

    public function payment_relationship() {
        return $this->hasOne(FinancyRPaymentRelationship::class);
    }

    public function relationship()
    {
        $relation = $this->payment_relationship()->first();
       if ($relation) {
           $namespace = $relation->module_type;
           $collect = $namespace::find($relation->module_id);
           if ($collect)
               return $collect;
           else
               return null;
       }
       return null;
    }

    public function users() {
        return $this->hasOne(Users::class, 'r_code', 'request_r_code');
    }

    public function getSubTotalAttribute() {
        $model = $this->relationship();
        $total_additional = 0.00;
        if ($model) {
            if (method_exists($model, 'subTotalPayment')) {
                $total_additional = $model->subTotalPayment();
            }
        }
        return $this->amount_gross + $total_additional;
    }

    public function relationModules($relationship) {

        $namespace = get_class($relationship);

        $modules = [
            'App\Model\FinancyLending' => [
                'name' => 'EMPRÉSTIMO',
                'description' => 'EMPRÉSTIMO FINANCEIRO',
                'url' => '/financy/lending/all?id='.$relationship->code
            ],
            'App\Model\FinancyRefund' => [
                'name' => 'REEMBOLSO',
                'description' => 'SOLICITAÇÃO DE CONTABILIZAÇÃO E PAGAMENTO',
                'url' => '/financy/refund/edit/'.$relationship->id
            ],
            'App\Model\FinancyAccountability' => [
                'name' => 'PRESTAÇÃO DE CONTAS',
                'description' => 'SOLICITAÇÃO DE PRESTAÇÃO DE CONTAS',
                'url' => '/financy/accountability/edit/'.$relationship->id
            ]
        ];

        return $modules[$namespace]?? [
                'name' => 'PAGAMENTO',
                'description' => 'SOLICITAÇÃO DE CONTABILIZAÇÃO E PAGAMENTO',
                'url' => ''
            ];
    }
}
