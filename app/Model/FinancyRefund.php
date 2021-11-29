<?php

namespace App\Model;

use App\Model\Services\Analyze\ProcessAnalyze;
use \App\Model\Services\Analyze\Model\RequestAnalyzeApprovers;
use \App\Model\Services\Analyze\Model\RequestAnalyzeObservers;

class FinancyRefund extends ProcessAnalyze
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_refund';
	
	public function users() {
        return $this->hasOne(Users::class, 'r_code', 'request_r_code');
    }

    public function financy_refund_items() {
        return $this->hasMany(FinancyRefundItem::class);
    }

    public function financy_refund_mng_analyze() {
        return $this->hasMany(FinancyRefundMngAnalyze::class);
    }
	
	public function analyze_approv() {
        return $this->morphMany(RequestAnalyzeApprovers::class, 'analyze');
    }

    public function analyze_observ() {
        return $this->morphMany(RequestAnalyzeObservers::class, 'analyze');
    }
	
	public function configClass($type) {
        return [
            'name' => 'Reembolso',
			'url' => '/financy/refund/approv',
            'arr_mark' => config('gree.analyze_office_mark')['financy'],
            'activemenu' => 'mAdmin,mFinancyRefund,mFinancyRefundApprovers'
        ][$type];
    }
}
