<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserNotificationExternalMsg extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_notification_external_msg';
    protected $appends = [
        'collaborator_name'
    ];

    public function getCollaboratorNameAttribute()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code')->first()->short_name;
    }
}
