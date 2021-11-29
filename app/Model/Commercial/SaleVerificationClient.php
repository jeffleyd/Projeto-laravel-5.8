<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleVerificationClient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sale_verification_client';
    protected $connection = 'filemanager';
}
