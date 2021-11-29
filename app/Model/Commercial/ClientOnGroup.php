<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientOnGroup extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_on_group';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
    protected $hidden = ['pivot'];
}
