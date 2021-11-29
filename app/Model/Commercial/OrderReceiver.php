<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderReceiver extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_receiver';
    protected $connection = 'commercial';
}
