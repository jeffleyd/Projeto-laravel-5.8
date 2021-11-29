<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SacProblemCategory extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_problem_category';

    protected $dates = ['deleted_at'];

    public function sac_protocol()
    {
        return $this->belongsTo(SacProtocol::class, 'sac_protocol.', 'id');
    }

}