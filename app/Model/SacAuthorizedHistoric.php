<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacAuthorizedHistoric extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_authorized_historic';

    public function owner()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code')->first();
    }

}
