<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderImdtAnalyze extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_imdt_analyze';
    protected $connection = 'commercial';

    public function salesman() {

        return $this->belongsTo(Salesman::class, 'salesman_id', 'id');
    }

    public function scopeValidProcessImdt($query, $request) {

        return $query->whereHas('salesman', function($q) use ($request) {
            $q->ValidProcessImdt($request);
        });
    }
}
