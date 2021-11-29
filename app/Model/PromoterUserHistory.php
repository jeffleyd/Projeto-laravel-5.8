<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromoterUserHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promoter_user_history';
    protected $appends = [
        'route'
    ];

    public function user()
    {
        return $this->belongsTo(PromoterUsers::class, 'id', 'promoter_user_id');
    }

    public function route()
    {
        return $this->belongsTo(PromoterRoute::class, 'promoter_route_id', 'id');
    }

    public function getRouteAttribute() {

        return $this->route()->first();
    }
    
}
