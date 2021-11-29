<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';
    protected $connection = 'commercial';
}
