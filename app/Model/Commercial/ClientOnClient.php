<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class ClientOnClient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_on_client';
    protected $connection = 'commercial';
}
