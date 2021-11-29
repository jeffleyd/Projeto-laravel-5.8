<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RhHourExtra extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_hour_extra';

    public function user() {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }

    public function immediate() {
        return $this->hasMany(UserImmediate::class, 'user_r_code', 'r_code');
    }

    public function manager() {
        return $this->hasOne(Users::class, 'r_code', 'mng_r_code');
    }

    public function scopeInAnalyzes($query, $r_code) {

        return $query->whereHas('immediate', function ($q) use ($r_code) {
            $q->where('immediate_r_code', $r_code);
        });
    }
}
