<?php

namespace App\Model;

use DB;
use \App\Model\Services\Analyze\ProcessAnalyze;
use \App\Model\Services\Analyze\Model\RequestAnalyzeApprovers;
use \App\Model\Services\Analyze\Model\RequestAnalyzeObservers;

class FinancyLending extends ProcessAnalyze
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_lending';

    protected $appends = [
        // 'is_pending',
		'position_analyze'
    ];
	
	public function analyze_approv() {
        return $this->morphMany(RequestAnalyzeApprovers::class, 'analyze');
    }

    public function analyze_observ() {
        return $this->morphMany(RequestAnalyzeObservers::class, 'analyze');
    }

    public function configClass($type) {
        return [
            'name' => 'Empréstimo',
			'url' => '/financy/lending/approv',
            'arr_mark' => config('gree.analyze_office_mark')['financy'],
            'activemenu' => 'mAdmin,mFinancyLending,mFinancyLendingApprovers'
        ][$type];
    }

    public function getPositionAnalyzeAttribute() {
        return $this->rtd_status['status']['validation']->count() ? $this->rtd_status['status']['validation']->first()->position : 1;
    }  

	public function financy_lending_mng_analyze() {
        return $this->hasMany(FinancyLendingMngAnalyze::class);
    }
	
    public function user()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }
	
	public function financy_lending_attach()
    {
        return $this->hasMany(FinancyLendingAttach::class, 'financy_lending_id', 'id');
    }
    
    public function prestacao_conta()
    {
        return $this->hasMany(FinancyAccountability::class, 'lending_request_id', 'id');
    }

    public function prestacao_conta_manual()
    {
        return $this->hasMany(FinancyAccountabilityManualEntry::class, 'financy_lending_id', 'id');
    }

    public function reembolso_prestacao_conta()
    {
        return $this->belongsTo(FinancyAccountabilityReceiverHistory::class, 'id', 'lending_request_id');
    }

    public function getDataPagamentoEmprestimo(){
        
        $id_payment = $this->payment_request_id;
        if($id_payment && $this->is_paid == 1){
            $r_payment = FinancyRPayment::find($id_payment);
            if($r_payment){
                return $r_payment->updated_at;
            }
        }
        
        return null;
        // return \Carbon\Carbon::createFromFormat('Y-m-d', '0000-00-00');
    }

    public function getTotalPrestacaoContaAttribute(){
        $total = 0;
        $prestacaoConta = $this->prestacao_conta;

        if($prestacaoConta){
            $total=$prestacaoConta->sum(function($value) {
                if($value->status->id!=3){
                    return $value->total;
                }
            });
        }
        return $total;
    }

    /**
     * getTotalPrestacaoConta function
     *
     * @param [int] $tipo
     * 1='Total'
     * 2='Total Prestação de Contas'
     * 3='Total Pagamentos'
     * @return void
     */
    public function getTotalPrestacaoConta($tipo=1){
        
        $total = 0;
        $totalPagamentos = 0;
        $totalPrestacaoContas = 0;
        $prestacaoConta = $this->prestacao_conta;
        $prestacao_conta_manual = $this->prestacao_conta_manual;

        if($prestacao_conta_manual){
            $totalPrestacaoContas += $prestacao_conta_manual->where('type_entry',2)->sum('total');
        }

        if($prestacaoConta){
            foreach ($prestacaoConta as $item){
                if ($item->status->id!=3){
                    $totalPrestacaoContas+=$item->total;
                }
                $pagamento = $item->pagamento_prestacao_conta;
                if($pagamento){
                    if($pagamento->is_paid==1){
                        $totalPagamentos+=$pagamento->amount_liquid;
                    }else{
                        $totalPagamentos+=($pagamento->amount_liquid * (-1.0));
                        $totalPrestacaoContas+=($pagamento->amount_liquid * (-1.0));
                    }
                    
                }
            }
        }
        
        if ($tipo==1){
            return $totalPrestacaoContas - $totalPagamentos;
        }
        if ($tipo==2){
            return $totalPrestacaoContas;
        }
        if ($tipo==3){
            return $totalPagamentos;
        }

        return $total;
    }

    /**
     * getTotalEmprestimo function
     *
     * @param [int] $tipo
     * 1='Total'
     * 2='Total Emprestimo'
     * 3='Total Emprestimo Manual'
     * 
     * @return void
     */
    public function getTotalEmprestimo($tipo=1){
        
        $total = $this->amount;
        $totalManual = 0;
        $prestacao_conta_manual = $this->prestacao_conta_manual;

        if($prestacao_conta_manual){
            $totalManual = $prestacao_conta_manual->where('type_entry',1)->sum('total');
        }

        if ($tipo==1){
            return $totalManual + $total;
        }
        if ($tipo==2){
            return $total;
        }
        if ($tipo==3){
            return $totalManual;
        }

        return $total;
    }

    /**
     * function getTotalPendente
     * Retorna o Total da Divida do Usuario
     * @param [int] $tipo
     * 1='Total Pago'
     * 2='Total Em Analise'
     * 3='Total Pago + Em Analise'
     * 
     * @return saldo
     */
    public function getTotalPendente($tipo=1){
        $total = $this->getTotalPrestacaoConta($tipo) - $this->getTotalEmprestimo($tipo);
        return $total;
    }

    public function getTotalPago($tipo=1){
        
        $total = 0;
        $totalPagamentos = 0;
        $totalPrestacaoContas = 0;
        $prestacaoConta = $this->prestacao_conta;
        $prestacao_conta_manual = $this->prestacao_conta_manual;

        if($prestacao_conta_manual && $tipo!=2){
            $totalPrestacaoContas += $prestacao_conta_manual->where('type_entry',2)->sum('total');
        }

        if($prestacaoConta){
            foreach ($prestacaoConta as $item){
                if ($item->status->id!=3 && $item->status->id>=2 && $tipo==1 ){
                    $totalPrestacaoContas+=$item->total;
                }
                if ($item->status->id!=3 && $item->status->id<=1 && $tipo==2 ){
                    $totalPrestacaoContas+=$item->total;
                }
                if ($item->status->id!=3 && $tipo==3 ){
                    $totalPrestacaoContas+=$item->total;
                }

                $pagamento = $item->pagamento_prestacao_conta;
                if($pagamento && $tipo!=2){
                    if($pagamento->is_paid==1){
                        $totalPagamentos+=$pagamento->amount_liquid;
                    }else{
                        $totalPagamentos+=($pagamento->amount_liquid * (-1.0));
                        $totalPrestacaoContas+=($pagamento->amount_liquid * (-1.0));
                    }
                    
                }
                
            }
        }
        
        if ($tipo==1){
            return $totalPrestacaoContas - $totalPagamentos;
        }
        if ($tipo==2){
            return $totalPrestacaoContas;
        }
        if ($tipo==3){
            return $totalPrestacaoContas - $totalPagamentos;;
        }

        return $total;
    }

    public function getTotalAnalise(){
        $total = 0;
        $prestacaoConta = $this->prestacao_conta;

        if($prestacaoConta){
            foreach ($prestacaoConta as $item){
                if ($item->status->id==1){
                    $total+=$item->total;
                }
            }
        }

        return $total;
    }

    public function getTotalReprovado(){
        
        $total = 0;
        $prestacaoConta = $this->prestacao_conta;

        if($prestacaoConta){
            foreach ($prestacaoConta as $item){
                if ($item->status->id==3){
                    $total+=$item->total;
                }
            }
        }

        return $total;
    }

    public function getTotalReembolso(){
        
        $total = 0;
        $prestacaoConta = $this->prestacao_conta;

        if($prestacaoConta){

            $pagamento_prestacao_conta = $prestacaoConta->where('pagamento_prestacao_conta','!=',null);

            foreach ($pagamento_prestacao_conta as $item){
               
                $pagamento = $item->pagamento_prestacao_conta;
                if($pagamento->is_paid==1){
                    $total+=$pagamento->amount_liquid;
                }else{
                    $total+=($pagamento->amount_liquid*(-1.0));
                }
            }
        }

        return $total;
    }

    /* public function getIsPendingAttribute(){

        if(round(abs($this->getTotalPendente()),2) != 0.0){
            return true;
        }
        $exists = $this->prestacao_conta()->whereRaw('( (is_paid = 0 AND is_reprov = 0 AND is_approv = 0 AND has_analyze = 0) OR has_analyze = 1 )')->first();
        if($exists){
            return true;
        }

        return false;
    } */
    
    public function isPending(){

        if(round(abs($this->getTotalPendente()),2) != 0.0){
            return true;
        }
        $exists = $this->prestacao_conta()->whereRaw('( (is_paid = 0 AND is_reprov = 0 AND is_approv = 0 AND has_analyze = 0) OR has_analyze = 1 )')->first();
        if($exists){
            return true;
        }

        return false;
    }
    

    public function scopePendings($query, $r_code='')
    {
        return $query->where('is_paid',1)
        ->where('is_accountability_paid',0)
        ->where('r_code',$r_code);
        // ->whereNotExists(function ($subquery){
        //     $subquery->select(DB::raw(1))
        //           ->from('financy_accountability')
        //           ->whereRaw('financy_lending.id = financy_accountability.lending_request_id')
        //           ->whereRaw('NOT( financy_accountability.is_paid = 1 OR (financy_accountability.is_paid = 0 AND financy_accountability.is_reprov = 1 OR financy_accountability.is_approv = 1 ))');
        // });
    }
}
