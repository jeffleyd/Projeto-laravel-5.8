<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskDay extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_day';

    public function user() {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }
	
	public function manager() {
        return $this->belongsTo(Users::class, 'mng_r_code', 'r_code');
    }

    public function immediate() {
        return $this->hasMany(UserImmediate::class, 'user_r_code', 'r_code');
    }

    public function itens() {
        return $this->hasMany(TaskDayItem::class, 'task_day_id', 'id');
    }

    public function scopeInAnalyzes($query, $r_code) {

        return $query->whereHas('immediate', function ($q) use ($r_code) {
           $q->where('immediate_r_code', $r_code);
        });
    }
}
