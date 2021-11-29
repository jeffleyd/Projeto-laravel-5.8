<?php

namespace App\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class PromoterUsers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promoter_users';
    protected $hidden = ['password'];

    protected $appends = [
        'lastposition'
    ];

    public function routes()
    {
        return $this->hasMany(PromoterRoute::class, 'promoter_user_id', 'id')
                    ->where('is_completed', 0)
                    ->where('is_cancelled', 0);
    }

    public function positions()
    {
        return $this->hasMany(PromoterUserHistory::class, 'promoter_user_id', 'id');
    } 

    public function SelectOnePositions()
    {
        return $this->hasOne(PromoterUserHistory::class, 'promoter_user_id', 'id')->orderBy('id', 'DESC')->first();
    }

    public function getlastpositionAttribute()
    {
        return $this->SelectOnePositions();
    }

    public function scopeShowOnlyWithPosition($query) {

        return $query->whereExists(function ($q) {
            $q->select(DB::raw(1))
                  ->from('promoter_user_history')
                  ->whereRaw('promoter_user_history.promoter_user_id = promoter_users.id');
        });
    }
    
}
