<?php


namespace App\Model\Commercial;


use Illuminate\Database\Eloquent\Model;

class ClientManagers extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_managers';
    protected $connection = 'commercial';
	
	public function salesman() {
        return $this->belongsTo(Salesman::class, 'salesman_id', 'id');
    }

}
