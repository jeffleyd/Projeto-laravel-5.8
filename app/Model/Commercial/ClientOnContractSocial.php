<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class ClientOnContractSocial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_on_contract_social';
    protected $connection = 'commercial';
}
