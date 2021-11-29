<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TiMaintenanceReplies extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ti_maintenance_replies';

    public function Users()
    {
        return $this->hasOne(Users::class, 'r_code', 'r_code_reply')->first();
    }
}
