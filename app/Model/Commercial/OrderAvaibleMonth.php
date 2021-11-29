<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAvaibleMonth extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_avaible_month';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];

    public function group() {

        return $this->belongsTo(SetProductGroup::class, 'group_id_apply', 'id');
    }

    public function scopeTypeApplyFilter($query, $type) {

        if ($type == 1 or $type == 5 or $type == 6)
            return $query->where('type_apply', $type)->whereHas('group');
        else
            return $query->where('type_apply', $type);
    }

}
