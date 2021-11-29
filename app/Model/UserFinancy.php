<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserFinancy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_financy';

    public function user()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }
}
