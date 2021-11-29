<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserNotificationExternal extends Authenticatable implements JWTSubject
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_notification_external';

    protected $fillable = [
        'r_code', 'password',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            //$this->attributes['password'] = bcrypt($password);
            $this->attributes['password'] = $password;
        }
    }

    public function user_notification_external_msg()
    {
        return $this->hasMany(UserNotificationExternalMsg::class, 'user_notification_external_id', 'id');
    }
}
