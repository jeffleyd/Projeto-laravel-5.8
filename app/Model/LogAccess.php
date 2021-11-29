<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogAccess extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_access';
	
	public function users() {
        return $this->hasOne(Users::class, 'r_code', 'r_code');
    }
}
