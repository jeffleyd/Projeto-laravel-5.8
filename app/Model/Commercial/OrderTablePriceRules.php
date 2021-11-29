<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTablePriceRules extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_table_price_rules';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];

    public function OrderFieldTablePrice()
    {
        return $this->hasOne(OrderFieldTablePrice::class, 'id', 'field_id');
    }

    public function scopeFieldPriceFilter($query, $request)
    {
        return $query->whereHas('OrderFieldTablePrice', function ($q) use ($request) {
            $q->where('column_salesman_table_price', $request->field);
        });
    }
}
