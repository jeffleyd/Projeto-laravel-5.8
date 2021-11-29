<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientMainClients extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_main_clients';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
}
