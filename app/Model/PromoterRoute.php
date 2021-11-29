<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromoterRoute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promoter_route';

    protected $appends = [
        'routehistoryfirst',
        'routehistorylast'
    ];

    protected $casts = [
        'date_start' => 'datetime:Y-m-d',
        'date_end' => 'datetime:Y-m-d',
    ];

    public function routeHistory()
    {
        return $this->hasMany(PromoterRouteHistory::class, 'promoter_route_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(PromoterUsers::class, 'promoter_user_id', 'id');
    }

    public function routeHistoryLastOrFirst($type)
    {
        $date = $this->routeHistory;
        if ($date->first()) {
            if ($type == 1) {
                return date('d-m-Y', strtotime($date->first()->created_at));
            } else if ($type == 2) {
                return date('d-m-Y', strtotime($date->last()->created_at));
            }
        } else {
            return '';
        }
    }

    public function getRoutehistoryfirstAttribute()
    {
        $date = $this->routeHistory;
        if ($date->first()) {
            return date('d-m-Y', strtotime($date->first()->created_at));
        } else {
            return '';
        } 
    }

    public function getRoutehistorylastAttribute()
    {
        $date = $this->routeHistory;
        if ($date->last()) {
            return date('d-m-Y', strtotime($date->last()->created_at));
        } else {
            return '';
        } 
    }

    
}
