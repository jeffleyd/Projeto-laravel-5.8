<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientOwnerAndPartner extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_owner_and_partner';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
}
