<?php

namespace App\Model\Commercial;

use App\Model\Commercial\Services\Analyze\ProcessAnalyze;

class BudgetCommercial extends ProcessAnalyze
{
    protected $table = 'budget_commercial';
    protected $connection = 'commercial';
    protected $appends = [
        'type_documents_name',
        'type_payment_name',
        'type_budget_name',
        'who_cancel'
    ];

    public function getTypeDocumentsNameAttribute() {
        $arr = [
            1 => 'NF débito',
            2 => 'NF Devolução',
            3 => 'NF Produto',
            4 => 'Pedido do cliente',
        ];

        return $arr[$this->type_documents] ?? '';
    }

    public function getTypePaymentNameAttribute() {
        $arr = [
            1 => 'Produto',
            2 => 'Desconto em duplicata/Título em aberto',
            3 => 'Transação bancária',
        ];

        return $arr[$this->type_payment] ?? '';
    }

    public function getTypeBudgetNameAttribute() {
        $arr = [
            1 => 'VPC',
            2 => 'Rebate',
            3 => 'Bonificação',
            4 => 'Verbas contratus',
            5 => 'Desconto',
        ];

        return $arr[$this->type_budget] ?? '';
    }
	
	public function configClass($type) {
        return [
            'name' => 'Solic. De Verbas',
			'url' => '/commercial/sales/budget/list/analyze',
        ][$type];
    }

    public function salesman() {
        return $this->belongsTo(Salesman::class, 'request_salesman_id', 'id');
    }
	
	public function client() {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function budget_commercial_attach() {
        return $this->hasMany(BudgetCommercialAttach::class);
    }

    public function budget_commercial_duplicates() {
        return $this->hasMany(BudgetCommercialDuplicates::class);
    }

    public function budget_commercial_itens() {
        return $this->hasMany(BudgetCommercialItens::class);
    }

    public function budget_commercial_report() {
        return $this->hasOne(BudgetCommercialReport::class);
    }

    public function getWhoCancelAttribute() {
        if ($this->is_cancelled) {
            if ($this->cancel_r_code)
                $user = \App\Model\Users::where('r_code', $this->cancel_r_code)->first();
            else
                $user = \App\Model\Commercial\Salesman::find($this->cancel_salesman_id);

            return (object)[
                'picture' => $user->picture,
                'name' => $user->short_name,
                'office' => $user->office,
                'description' => $this->cancel_reason,
                'updated_at' => $this->updated_at,
            ];
        }

        return '';
    }

}
