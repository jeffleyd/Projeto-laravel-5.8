<?php

namespace App\Model;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Database\Eloquent\Model;

class SacOsProtocol extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_os_protocol';

    public function osMsgs() {
            
            return $this->hasMany(SacMsgOs::class, 'sac_os_protocol_id', 'id')
                    ->where('message_visible', 1)
                    ->orderBy('id', 'ASC');
    }

    public function osMsgsOne() {
        
        return $this->hasOne(SacMsgOs::class, 'sac_os_protocol_id', 'id')
                    ->where('message_visible', 1)
                    ->orderBy('id', 'DESC');
    }

    public function modelProtocol() {
        
        return $this->belongsTo(SacModelProtocol::class, 'sac_protocol_id', 'sac_protocol_id');
    }
	
	public function sacPartProtocol() {

        return $this->hasMany(SacPartProtocol::class, 'sac_os_protocol_id', 'id');
    }
	
	public function sacProtocol() {

        return $this->belongsTo(SacProtocol::class, 'sac_protocol_id', 'id');
    }    

    public function modelOs()
    {
        return $this->hasMany(SacModelOs::class, 'sac_os_protocol_id', 'id');
    }

    public function authorizedOs() {
        
        return $this->belongsTo(SacAuthorized::class, 'authorized_id', 'id');
    }
	
	public function sacOsAnalyze()
    {
        return $this->hasMany(SacOsAnalyze::class, 'sac_os_protocol_id', 'id');
    }
	
	public function scopeShowAuthorizedOs($query, $id){
        return $query->where('authorized_id', $id);
    }

    public function scopeSacModelOsFilter($query, $serie) {

        return $query->whereHas('modelOs', function ($q) use ($serie) {
            $q->where('serial_number', $serie);
        });
    }

    public function scopeSacProtocolFilter($query, $status, $type = 1) {

        if ($status == 1) {
            return $query->whereHas('SacProtocol', function ($q) use ($status) {
                $q->where('is_cancelled', 0)->where('is_completed', 0)->where('is_refund', 0);
            })->whereHas('modelOs', function ($q2) {
				$q2->whereHas('sacPartProtocol', function ($q3) {
						$q3->where('is_approv', 1)
						->where('is_repprov', 0);
				});
			});
        } else if ($status == 2) {
            return $query->whereHas('SacProtocol', function ($q) use ($status) {
                $q->where('is_cancelled', 0)->where('is_completed', 0)->where('is_refund', 0);
            })->whereHas('modelOs', function ($q2) {
				$q2->whereHas('sacPartProtocol', function ($q3) {
						$q3->where('is_approv', 0)
						->where('is_repprov', 0);
				});
			});
        } else if ($status == 3) {
            return $query->whereHas('SacProtocol', function ($q) use ($status) {
                $q->where('is_cancelled', 0)->where('is_completed', 0)->where('is_refund', 0);
            })->whereHas('modelOs', function ($q2) {
				$q2->whereHas('sacPartProtocol', function ($q3) {
						$q3->where('is_approv', 0)
						->where('is_repprov', 0);
				});
			});
        } else if ($status == 'sacf_see_part') {
            return $query->whereHas('modelOs', function ($q2) {
				$q2->whereHas('sacPartProtocol', function ($q3) {
					$q3->where('is_approv', 0)
						->where('is_repprov', 0)
						->orWhere(function ($query) {
							$query->where('is_approv', 1)
								->where('is_repprov', 0);
						});
				});
			});
        } else if ($status == 'sacf_type_line') {
            return $query->whereHas('SacProtocol', function ($q) use ($type) {
                $q->SacModelProtocolFilter($type);
            });
        } else if ($status == 'code') {
            return $query->whereHas('SacProtocol', function ($q) use ($type) {
                $q->where('code', $type);
            });
        }

    }
	
	public function scopeOnlyMsgNotReadFilter($query) {

        return $query->join(DB::raw('(
            SELECT sac_os_protocol_id FROM sac_msg_os
                where id IN
                (
                    SELECT max(id)
                    FROM `sac_msg_os`
                    WHERE is_system = 0 AND message_visible = 1
                    group by sac_os_protocol_id
                ) AND r_code IS NULL AND authorized_id = 0 AND is_system = 0
            ) smo1'), function ($join) {
            $join->on('sac_os_protocol.id', '=', 'smo1.sac_os_protocol_id');
        });
    }
	
	public function sac_os_analyze() {

        return $this->hasMany(SacOsAnalyze::class, 'sac_os_protocol_id', 'id');
    }

    public function financy_r_payment()
    {
        return $this->hasOne(FinancyRPayment::class, 'id', 'payment_request_id');
    }
	
	public function getStatusOsAttribute() {

        $html = '';

        if ($this->is_cancelled == 1) {
            $html .= $this->typeStatus('danger', 'Cancelado');
        } elseif ($this->is_paid == 1 && $this->is_payment_request == 1 ) {
            $html .= $this->typeStatus('success', 'Pago');
        } elseif ($this->is_paid == 1 && $this->is_payment_request == 0 ) {
            $html .= $this->typeStatus('success', 'Concluído');
        } elseif ($this->has_pending_payment == 1 ) {
            $html .= $this->typeStatus('success', 'Pendente de pagamento');
        } elseif ($this->expedition_invoice == 1) {
            $html .= $this->typeStatus('success', 'Separação & Faturamento');
        } elseif ($this->has_split == 1) {
            $html .= $this->typeStatus('warning', 'Aguardando envio P/ separação');
        } elseif ($this->is_approv == 1 and $this->sacOsAnalyze->count() > 0) {
            $html .= $this->typeStatus('info', 'Peças aprovadas');
        } elseif ($this->sacOsAnalyze->count() == 0) {
            if ($this->sacProtocol->sacpartprotocol->where('is_repprov', 0)->count() > 0) {
                $html .= $this->typeStatus('secondary', 'Suspensos (Sem análises)');
            } else {
                $html .= $this->typeStatus('warning', 'Em andamento');
            }
        } elseif ($this->sacOsAnalyze->count() > 0 and $this->has_analyze_part == 0 and $this->has_pending_payment = 0) {
            $html .= $this->typeStatus('warning', 'Suspenso');
        } elseif ($this->sacOsAnalyze->count() > 0 and $this->has_analyze_part == 1 and $this->has_pending_payment = 0) {
            $html .= $this->typeStatus('warning', 'Análisados (Falta aprovar)');
        } else {
            $html .= $this->typeStatus('warning', 'Em andamento');
        }
        if ($this->has_print == 1) {
            $html .= '<br>'.$this->typeStatus('info', 'Imprimido');
        }
        return $html;
    }

    private function typeStatus($class, $desc) {
        return '<span class="badge badge-light-'.$class.'">'.$desc.'</span>';
    }
}
