<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacExpeditionRequest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_expedition_request';

    public function SacPartProtocol() {   
        return $this->belongsTo(SacPartProtocol::class, 'id', 'sac_expedition_request_id');
    } 

    public function SacBuyParts() {   
        return $this->belongsTo(SacBuyParts::class, 'id', 'sac_expedition_request_id');
    }

    public function scopeSacBuyPartsFilter($query) {
        return $query->whereHas('SacBuyParts', function ($q) use ($val_filter) {
            $q->SacBuyPart->code;
        });
    }
}
