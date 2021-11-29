<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class ClientOnBalanceEquityDreFlow extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_on_balance_equity_dre_flow';
    protected $connection = 'commercial';
}
