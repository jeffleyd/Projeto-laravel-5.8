<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromoterRequestItens extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promoter_request_itens';

    public function request_item()
    {
        return $this->belongsTo(PromoterRequestItem::class, 'promoter_request_item_id', 'id');
    }
}
