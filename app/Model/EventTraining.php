<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class EventTraining extends Model
{
    protected $table = 'event_training';

    protected $appends = [
        'person_total',
    ];

    public function getPersonTotalAttribute()
    {
        return $this->event_person()->count();
        
    }

    public function event_person() {
        return $this->belongsToMany(EventTraining::class, 'event_person_on_training', 'event_training_id', 'event_person_id');
    }
}
