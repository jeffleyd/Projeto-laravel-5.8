<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class SalesmanOnState extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salesman_on_state';
    protected $connection = 'commercial';
}
