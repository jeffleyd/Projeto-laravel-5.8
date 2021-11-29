<?php

namespace App\Model\Administration;

use App\Model\Services\Analyze\ProcessAnalyze;
use Illuminate\Foundation\Auth\User;

class ReservationMeetRoom extends ProcessAnalyze
{
    protected $table = 'reservation_meet_room';
	
	public function configClass($type) {
        return [
            'name' => 'Reserva de sala',
			'url' => '/administration/reservation/meetroom/analyze'
        ][$type];
    }

    public function users (){                
        return $this->hasOne(\App\Model\Users::class, 'id', 'users_id');
    }

    public function meet_room (){                
        return $this->hasOne(\App\Model\Administration\MeetRoom::class, 'id', 'meet_room_id');
    }

    public function reason (){                
        return $this->hasOne(\App\Model\Administration\Reason::class, 'id', 'reason_id');
    }

    


    
    
}
