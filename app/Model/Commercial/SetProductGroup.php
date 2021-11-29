<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetProductGroup extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'set_product_group';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];


    public function setProductOnGroup()
    {
        return $this->belongsToMany(SetProduct::class, 'set_product_on_group', 'set_product_group_id', 'set_product_id');
    }

    public function scopeSetHasActive($query) {

        return $query->whereHas('setProductOnGroup', function ($q) {
            $q->where('is_active', 1);
        });
    }
}
