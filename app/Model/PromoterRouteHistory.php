<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromoterRouteHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promoter_route_history';
    protected $appends = [
        'job_done'
    ];

    public function getJobDoneAttribute() {

        return date('d/m/Y H:i:s', strtotime($this->attach_date));
    }

    public function images()
    {
        return $this->hasMany(PromoterRouteHistoryImg::class, 'promoter_route_history_id', 'id');
    }

}
