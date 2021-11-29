<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class SalesmanImmediate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salesman_immediate';

    protected $connection = 'commercial';

    public function user()
    {
        return $this->hasOne(Salesman::class, 'id', 'salesman_id');
    }

    public function immediate_boss()
    {
        return $this->hasOne(Salesman::class, 'id', 'immediate_id');
    }

    public function scopeValidProcessImdt($query, $request) {

        return $query->whereHas('immediate_boss', function($q) use ($request) {
            $q->where('immediate_id', $request->session()->get('salesman_data')->id);
        });
    }

}
