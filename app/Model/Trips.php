<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trips';
	
	public function user()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }

}
