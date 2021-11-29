<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use App\Model\Commercial\Client;
use App\Model\Users;

class SaleVerificationClientCompleted extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sale_verification_client_completed';
    protected $connection = 'commercial';

    public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function users() {
        return $this->setConnection('mysql')->hasOne(Users::class, 'r_code', 'r_code');
    }
}
