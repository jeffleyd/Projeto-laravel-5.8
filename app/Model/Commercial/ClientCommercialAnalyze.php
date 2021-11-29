<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use \App\Model\Users;

class ClientCommercialAnalyze extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_commercial_analyze';
    protected $connection = 'commercial';

    public function user() {

        return $this->setConnection('mysql')->belongsTo(Users::class, 'r_code', 'r_code');
    }
}
