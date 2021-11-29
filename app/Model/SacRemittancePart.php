<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacRemittancePart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_remittance_part';

    protected $appends = [
		'value_part_total',
        'value_shipping_total',
        'value_service_total',
        'cost_total',
        'date_updated'
    ];

    public function sac_authorized() {
        return $this->belongsTo(SacAuthorized::class, 'authorized_id', 'id');
    }

    public function sac_remittance_parts() {

        return $this->hasMany(SacRemittanceParts::class, 'sac_remittance_part_id', 'id');
    }

    public function sac_remittance_analyze() {

        return $this->hasMany(SacRemittanceAnalyze::class, 'sac_remittance_part_id', 'id');
    }

    public function parts() {

        return $this->belongsToMany(Parts::class, 'sac_remittance_parts', 'sac_remittance_part_id', 'part');
    }

    public function financy_r_payment()
    {
        return $this->hasOne(FinancyRPayment::class, 'id', 'payment_request_id');
    }

    public function sac_remittance_part_costs() 
    {
        return $this->hasMany(SacRemittancePartCosts::class, 'sac_remittance_part_id', 'id')->orderBy('updated_at', 'DESC');
    }

    public function getValuePartTotalAttribute() 
    {
        return $this->sac_remittance_part_costs->sum('value_part');        
    }

    public function getValueShippingTotalAttribute()
    {   
        return $this->sac_remittance_part_costs->unique('number_nf')->sum('value_shipping');  
    }

    public function getValueServiceTotalAttribute() 
    {   
        return $this->sac_remittance_parts->sum('service_value');
    }

    public function getCostTotalAttribute()
    {   
        return $this->value_part_total + $this->value_shipping_total + $this->value_service_total;
    }

    public function getDateUpdatedAttribute()
    {   
        if($this->sac_remittance_part_costs->first()) {
            return $this->sac_remittance_part_costs->first()->updated_at;
        } else {
            return null;
        }
    }
}