<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientGroup extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_group';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
    protected $hidden = ['pivot'];

    public function Clients()
    {
        return $this->belongsToMany(Client::class, 'client_on_group', 'client_group_id', 'client_id');
    }
}
