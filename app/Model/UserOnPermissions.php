<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserOnPermissions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_on_permissions';

    public function user()
    {
        return $this->hasOne(Users::class, 'r_code', 'user_r_code');
    }
}
