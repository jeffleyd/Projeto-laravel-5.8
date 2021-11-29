<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SacPartProtocol extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_part_protocol';
	
	protected $appends = [
        'description_part',
        'part_sum_total',
		'code_part',
		'code_os'
    ]; 

    public function modelParts()
    {
        return $this->hasOne(Parts::class, 'id', 'part_id');
    }
	
	public function modelOs()
    {
        return $this->hasOne(SacModelOs::class, 'sac_os_protocol_id', 'sac_os_protocol_id');
    }

    public function SacOsProtocol()
    {
        return $this->hasOne(SacOsProtocol::class, 'id', 'sac_os_protocol_id');
    }

    public function SacExpedition() {
        
        return $this->belongsTo(SacExpeditionRequest::class, 'sac_expedition_request_id', 'id');
    }

    public function SacProtocol()
    {
        return $this->belongsTo(SacProtocol::class, 'sac_protocol_id', 'id');
    }

    public function SacProductAir() {
        return $this->hasOne(ProductAir::class, 'id', 'product_id');
    }

    public function scopeSacOSProtocolFilter($query, $id)
    {         
        return $query->whereHas('SacOsProtocol', function($q) use ($id) {
            $q->where('id', $id);
        });
    }

    public function scopePartProtocolFilter($query)
    {         
        return $query->with(['sacProtocol.sacosprotocol' =>  function($q){
                   $q->where('sac_os_protocol.is_cancelled', 0)
                     ->where('sac_os_protocol.is_paid', 0);
                }, 'modelParts'])
			->whereHas('modelParts')
            ->where('expedition_confirm', 0)
            ->where('is_approv', 1)
            ->orderBy('id', 'ASC');
    }
	
	public function getDescriptionPartAttribute()
    {   
        if ($this->modelParts) {
            return $this->modelParts->description;
        } else {
            return '';
        }
    }
	
	public function getCodePartAttribute()
    {   
        if ($this->modelParts) {
            return $this->modelParts->code;
        } else {
            return '';
        }
    }

    public function sac_protocol_costs() 
    {
        return $this->hasOne(SacProtocolCosts::class, 'sac_part_protocol_id', 'id');
    }

    public function protocol_cost_field()
    {
        $data = $this->sac_protocol_costs;

        if (!$data) {
            return (object) [
                'number_order' => '-',
                'number_di' => '-', 
                'number_nf' => '-',
                'date_emission_nf' => null,
                'hour_emission_nf' => null,
				'date_billing' => null,
                'hour_billing' => null,
                'value_part' => 0.00,
                'value_shipping' => 0.00,
                'value_shipping_return' => 0.00,
				'observation' => '',
                'update_reason' => ''
            ];
        }
        return $data;
    }

    public function getPartSumTotalAttribute() 
    {   
        return array_sum([
            $this->protocol_cost_field()->value_part,
            $this->protocol_cost_field()->value_shipping,
            $this->SacProtocol()->first()->value_visit_total,
            $this->total,
        ]);
    }
	
	public function getCodeOsAttribute()
    {   
        if ($this->SacOsProtocol) {
            return $this->SacOsProtocol->code;
        } else {
            return '';
        }
    }
}
