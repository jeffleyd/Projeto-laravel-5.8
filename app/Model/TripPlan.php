<?php

namespace App\Model;

use App\Model\Services\Analyze\ProcessAnalyze;
use App\Model\Services\Analyze\Model\RequestAnalyzeApprovers;
use App\Model\Services\Analyze\Model\RequestAnalyzeObservers;


class TripPlan extends ProcessAnalyze
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trip_plan';
	
	protected $appends = [
        'position_analyze'
    ];

	
	public function analyze_approv() 
    {
        return $this->morphMany(RequestAnalyzeApprovers::class, 'analyze');
    }

    public function analyze_observ() 
    {
        return $this->morphMany(RequestAnalyzeObservers::class, 'analyze');
    }

    public function trips() 
    {
        return $this->belongsTo(Trips::class, 'trip_id', 'id');
    }
	
	public function configClass($type) {
        return [
            'name' => 'Viagem',
			'url' => '/trip/view',
            'arr_mark' => [],
            'activemenu' => 'mAdmin,mTrip,mTripApprovers'
        ][$type];
    }

	public function getPositionAnalyzeAttribute() {
        return $this->rtd_status['status']['validation']->count() ? $this->rtd_status['status']['validation']->first()->position : 1;
    }

}
