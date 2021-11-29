<?php

namespace App\Model;

use DB;
use \App\Casts\NumberFormat;
use \App\Model\Services\Analyze\ProcessAnalyze;
use \App\Model\Services\Analyze\Model\RequestAnalyzeApprovers;
use \App\Model\Services\Analyze\Model\RequestAnalyzeObservers;

class FinancyAccountability extends ProcessAnalyze
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_accountability';

    protected $appends = [
        'total_money',
        'status_text',
        'status',
		'position_analyze',
    ];
	
	
	public function analyze_approv() {
        return $this->morphMany(RequestAnalyzeApprovers::class, 'analyze');
    }

    public function analyze_observ() {
        return $this->morphMany(RequestAnalyzeObservers::class, 'analyze');
    }

    public function configClass($type) {
        return [
            'name' => 'Prestação de contas',
			'url' => '/financy/accountability/approv',
            'arr_mark' => config('gree.analyze_office_mark')['financy'],
            'activemenu' => 'mAdmin,mFinancyLending,mFinancyAccountabilityApprovers'
        ][$type];
    }

    public function getPositionAnalyzeAttribute() {
        return $this->rtd_status['status']['validation']->count() ? $this->rtd_status['status']['validation']->first()->position : 1;
    }


    public function lending()
    {
        return $this->belongsTo(FinancyLending::class, 'lending_request_id', 'id');
    }

    public function pagamento_prestacao_conta()
    {
        return $this->belongsTo(FinancyRPayment::class, 'payment_request_id', 'id')->where('amount_liquid','>',0);
    }
	
	public function financyRPayment()
    {
        return $this->belongsTo(FinancyRPayment::class, 'payment_request_id', 'id');
    }

    public function itens()
    {
        return $this->hasMany(FinancyAccountabilityItem::class, 'financy_accountability_id', 'id');
    }
    
    public function observations()
    {
        return $this->hasMany(FinancyAccountabilityObservationHistory::class, 'financy_accountability_id', 'id');
    }
    public function receiver_history()
    {
        return $this->hasMany(FinancyAccountabilityReceiverHistory::class, 'financy_accountability_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }
	
	public function financy_accountability_manual_entry() {
        return $this->hasMany(FinancyAccountabilityManualEntry::class, 'financy_lending_id', 'lending_request_id');
    }

    public function subTotalPayment(): float {
        return $this->financy_accountability_manual_entry()->sum('total');
    }

    public function getTotalMoneyAttribute(){
        return formatMoney($this->total,1);
    }

    public function getStatusTextAttribute(){

        if($this->is_reprov == 1){
            return '<span class="badge badge-light-danger">Reprovado</span>';
        }

        if($this->is_approv == 1){
            return '<span class="badge badge-light-success">Aprovado</span>';
        }
        if($this->has_analyze == 1 ){
            return '<span class="badge badge-light-info">Em Análise</span>';
        }

        return '<span class="badge badge-light-secondary">Não Enviado</span>';
    }
    public function getStatusAttribute(){
        $status=[
            0=>(object)['id'=>0, 'text'=>'não enviado','html'=>'<span class="badge badge-light-secondary">Não Enviado</span>'],
            1=>(object)['id'=>1, 'text'=>'em analise','html'=>'<span class="badge badge-light-info">Em Análise</span>'],
            2=>(object)['id'=>2, 'text'=>'aprovado','html'=>'<span class="badge badge-light-success">Aprovado</span>'],
            3=>(object)['id'=>3, 'text'=>'reprovado','html'=>'<span class="badge badge-light-danger">Reprovado</span>'],
            4=>(object)['id'=>4, 'text'=>'transferido','html'=>'<span class="badge badge-light-primary">Transferido</span>'],
        ];

        if($this->is_reprov == 1){
            return $status[3];
        }
        if($this->is_paid == 1){
            return $status[4];
        }

        if($this->is_approv == 1){
            return $status[2];
        }
        if($this->has_analyze == 1 ){
            return $status[1];
        }

        return $status[0];

    }

    public function setStatusTextAttribute($value){

        $this->status_text = $value;
    }
    public function setStatus($value){

        $this->status_text = $value;

    }

    public function scopeNotAnalyze($query, $r_code='')
    {
        return $query->whereRaw('NOT( is_paid = 0 AND is_reprov = 0 AND is_approv = 0 AND has_analyze = 0)');
    }

    public function scopeNotSendAnalyze($query, $lending_request_id)
    {
        return $query->where('lending_request_id', $lending_request_id)
                ->whereRaw('( is_paid = 0 AND is_reprov = 0 AND is_approv = 0 AND has_analyze = 0)');
    }

    public function scopeInAnalyze($query, $lending_request_id)
    {
        return $query->where('lending_request_id', $lending_request_id)
                ->where('has_analyze', 1);
    }
	
	public function scopeWhoApprov($query, $perm, $request) {
        if ($perm->grade == 10) {
            return $query->whereHas('financyRPayment', function ($q) use ($request) {
                $q->where('mng_reprov', 0)
                ->where('mng_approv', 1)
                ->where('financy_approv', 1)
                ->where('financy_reprov', 0)
                ->where('pres_reprov', 0)
                ->where('pres_approv', 0)
                ->where('has_analyze', 1)
                ->orWhere(function ($query) use ($request) {
                    $query->whereExists(function ($subquery) use ($request) {
                        $subquery->select(\Illuminate\Support\Facades\DB::raw(1))
                            ->from('user_immediate')
                            ->where('user_immediate.immediate_r_code', '=', $request->session()->get('r_code'))
                            ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                    })
                        ->where('has_analyze', 1)
                        ->where('mng_approv', 0)
                        ->where('mng_reprov', 0)
                        ->where('financy_approv', 0)
                        ->where('financy_reprov', 0)
                        ->where('pres_approv', 0)
                        ->where('pres_reprov', 0);
                });
            });
        } else if ($perm->grade == 99) {
            return $query->whereHas('financyRPayment', function ($q) use ($request) {
                $q->where('mng_reprov', 0)
                    ->where('mng_approv', 1)
                    ->where('financy_r_payment.financy_supervisor', '!=', null)
                    ->where('financy_r_payment.financy_accounting', '!=', null)
                    ->where('financy_approv', 0)
                    ->where('financy_reprov', 0)
                    ->where('pres_reprov', 0)
                    ->where('pres_approv', 0)
                    ->where('has_analyze', 1)
                    ->orWhere(function ($query) use ($request) {
                        $query->whereExists(function ($subquery) use ($request) {
                            $subquery->select(\Illuminate\Support\Facades\DB::raw(1))
                                ->from('user_immediate')
                                ->where('user_immediate.immediate_r_code', '=', $request->session()->get('r_code'))
                                ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                        })
                            ->where('has_analyze', 1)
                            ->where('mng_approv', 0)
                            ->where('mng_reprov', 0)
                            ->where('financy_approv', 0)
                            ->where('financy_reprov', 0)
                            ->where('pres_approv', 0)
                            ->where('pres_reprov', 0);
                    });
            });
        } else if ($perm->grade == 11) {
            return $query->whereHas('financyRPayment', function ($q) use ($request) {
                $q->where('mng_reprov', 0)
                    ->where('mng_approv', 1)
                    ->where('financy_supervisor', '!=', null)
                    ->where('financy_accounting', null)
                    ->where('financy_approv', 0)
                    ->where('financy_reprov', 0)
                    ->where('pres_reprov', 0)
                    ->where('pres_approv', 0)
                    ->where('has_analyze', 1);
            });

        } else if ($perm->grade == 12) {
            return $query->whereHas('financyRPayment', function ($q) use ($request) {
                $q->where('mng_reprov', 0)
                    ->where('mng_approv', 1)
                    ->where('financy_supervisor', null)
                    ->where('financy_approv', 0)
                    ->where('financy_reprov', 0)
                    ->where('pres_reprov', 0)
                    ->where('pres_approv', 0)
                    ->where('has_analyze', 1);
            });

        } else {
            return $query->whereHas('financyRPayment', function ($q) use ($request) {
                $q->whereExists(function ($subquery) use ($request) {
                    $subquery->select(\Illuminate\Support\Facades\DB::raw(1))
                        ->from('user_immediate')
                        ->where('user_immediate.immediate_r_code', '=', $request->session()->get('r_code'))
                        ->whereColumn('financy_r_payment.request_r_code', '=', 'user_immediate.user_r_code');
                })->where('has_analyze', 1)
                ->where('mng_approv', 0)
                ->where('mng_reprov', 0)
                ->where('financy_approv', 0)
                ->where('financy_reprov', 0)
                ->where('pres_approv', 0)
                ->where('pres_reprov', 0);
            });
        }
    }

}
