<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventPerson extends Model
{
    protected $table = 'event_person';
    protected $appends = [
        'event_total',
    ];

    public function event_training() {
        return $this->belongsToMany(EventTraining::class, 'event_person_on_training', 'event_person_id', 'event_training_id');
    }

    public function getEventTotalAttribute() {
        
        return $this->event_training()->count();
    }
}
