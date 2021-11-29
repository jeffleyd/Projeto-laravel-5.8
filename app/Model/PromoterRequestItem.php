<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromoterRequestItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promoter_request_item';

    public function user()
    {
        return $this->belongsTo(PromoterUsers::class, 'promoter_user_id', 'id');
    }

    public function request_itens()
    {
        return $this->hasMany(PromoterRequestItens::class, 'promoter_request_item_id', 'id');
    }
}
