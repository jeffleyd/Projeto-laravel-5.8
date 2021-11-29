<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SacProtocol extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_protocol';
    protected $appends = [
        'firstsacosprotocol',
        'has_part_analyze',
		'value_part_total',
        'value_shipping_total',
        'value_labor_total',
        'value_visit_total',
        'cost_total',
		'value_total_extra',
		'value_total_gas',
        'date_updated'
    ];

    public function sacMsgs()
    {
        return $this->hasMany(SacMsgProtocol::class, 'sac_protocol_id', 'id')
                    ->where('message_visible', 1)
                    ->orderBy('id', 'ASC');
    }

    public function sacosprotocol()
    {
        return $this->hasMany(SacOsProtocol::class, 'sac_protocol_id', 'id');
    }

    public function getfirstsacosprotocolAttribute()
    {
        $query = $this->sacosprotocol->where('is_cancelled', 0)->first();
        if ($query) {
            return $query->code;
        } else {
            return '';
        }
        
    }
	
	public function userProtocol()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }

    public function clientProtocol()
    {
        return $this->belongsTo(SacClient::class, 'client_id', 'id');
    }

    public function authorizedProtocol()
    {
        return $this->belongsTo(SacAuthorized::class, 'authorized_id', 'id');
    }
	
	public function sacModelProtocol()
    {
        return $this->hasMany(SacModelProtocol::class, 'sac_protocol_id', 'id');
    }
	
	public function SacProblemCategory()
    {
        return $this->hasOne(SacProblemCategory::class, 'id', 'sac_problem_category_id');
    }

    public function getHasPartAnalyzeAttribute()
    {
        $query = $this->sacpartprotocol->where('is_approv', 0)->where('is_repprov', 0)->first();
        return $query;
    }

    public function sacpartprotocol()
    {
        return $this->hasMany(SacPartProtocol::class, 'sac_protocol_id', 'id');
    }
	
	public function scopeProtocolRelOrderfilter($query)
    {   
        return $query->with(['clientProtocol', 'authorizedProtocol', 'sacModelProtocol.sacProductAir', 'SacProblemCategory'])
                    ->groupBy('sac_protocol.id')
                    ->orderBy('sac_protocol.id', 'DESC');
    }

    public function scopeSacProtocolLeftFilter($query, $left, $type_line)
    {   
        if($left != 30) {
            if($left == 5) {
                $dt_start = '5 day';
                $dt_final = '14 day';
            } else {
                $dt_start = '15 day';
                $dt_final = '29 day';
            }
            $query->where('sac_protocol.created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - '.$dt_start.'')))
                  ->where('sac_protocol.created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d'). ' - '.$dt_final.'')));
        } else {
            $query->where('sac_protocol.created_at', '<=', date('Y-m-d', strtotime(date('Y-m-d'). ' - 29 day')));
        }
        
        if($type_line != 0) {
            $query->sacModelProtocolFilter($type_line);
        }

        return $query->where('sac_protocol.is_cancelled', 0)
                    ->where('sac_protocol.is_refund', 0)
                    ->where('sac_protocol.is_completed', 0)
					->where('is_entry_manual', 0);
    }

    public function scopeSacModelProtocolFilter($query, $type) {

        if ($type == 1 or $type == 2) {

            return $query->whereHas('sacModelProtocol', function ($q) use ($type) {
                $q->SacProductAirFilter($type);
            });

        } else {

            return $query->whereHas('sacModelProtocol');

        }
        

    }
	
	public function scopeSacModelsProtocolFilter($query, $input) {

        return $query->whereHas('sacModelProtocol', function ($q) use ($input) {
            $q->where('product_id', $input);
        });

    }


    public function scopeSacPartProtocolAnalyze($query, $type) {

        if ($type == 1) {

            return $query->whereHas('sacpartprotocol', function ($q) {
                    $q->where('is_approv', 1)
                    ->where('is_repprov', 0);
            });
        } else if ($type == 2) {

            return $query->whereHas('sacpartprotocol', function ($q) {
                $q->where('is_approv', 0)
                ->where('is_repprov', 0);
            });

        } else {

            return $query->whereHas('sacpartprotocol', function ($q) {
                $q->where('is_approv', 0)
                    ->where('is_repprov', 0)
                    ->orWhere(function ($query) {
                        $query->where('is_approv', 1)
                            ->where('is_repprov', 0);
                    });
            });
        }
        

    }
	
	public function scopeOnlyMsgNotReadFilter($query) {

        return $query->join(DB::raw('(
            SELECT sac_protocol_id FROM sac_msg_protocol
                where id IN
                (
                    SELECT max(id)
                    FROM `sac_msg_protocol`
                    WHERE is_system = 0 AND message_visible = 1
                    group by sac_protocol_id
                ) AND r_code IS NULL AND authorized_id = 0 AND is_system = 0
            ) smo1'), function ($join) {
            $join->on('sac_protocol.id', '=', 'smo1.sac_protocol_id');
        });
    }

    public function scopeSacOsProtocolFilter($query, $val_filter) {
        return $query->whereHas('sacosprotocol', function ($q) use ($val_filter) {
            $q->where('code', $val_filter);
        });
    }
	
	public function parts() {
        return $this->belongsToMany(Parts::class, 'sac_part_protocol', 'sac_protocol_id', 'part_id');
    }
	
	public function sac_protocol_costs() 
    {
        return $this->hasMany(SacProtocolCosts::class, 'sac_protocol_id', 'id')->orderBy('updated_at', 'DESC');
    }

    public function getValuePartTotalAttribute() 
    {
        return $this->sac_protocol_costs->sum('value_part');        
    }

    public function getValueShippingTotalAttribute()
    {   
        return $this->sac_protocol_costs->unique('number_nf')->sum('value_shipping');  
    }

    public function getValueLaborTotalAttribute() 
    {   
        $labor = $this->sacosprotocol->sum('total') - $this->sacosprotocol->sum('visit_total');
        if($labor <= 0.00) {
            return 0.00;
        }
        return $labor;
    }

    public function getValueVisitTotalAttribute()
    {
        return $this->sacosprotocol->sum('visit_total');
    }
	
	public function getValueTotalExtraAttribute()
    {
        return $this->sacosprotocol->sum('total_extra');
    }
	
	public function getValueTotalGasAttribute()
    {
        return $this->sacosprotocol->sum('total_gas');
    }

    public function getCostTotalAttribute()
    {   
        return $this->value_part_total + 
			   $this->value_shipping_total + 
			   $this->value_labor_total + 
			   $this->value_visit_total + 
			   $this->value_total_extra +
			   $this->value_total_gas;
    }

    public function getDateUpdatedAttribute()
    {   
        if($this->sac_protocol_costs->first()) {
            return $this->sac_protocol_costs->first()->updated_at;
        } else {
            return null;
        }
    }
}
