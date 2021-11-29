<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacMsgOs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_msg_os';
	
	public function sac_os_protocol(){
        return $this->belongsTo(SacOsProtocol::class, 'sac_os_protocol_id', 'id');
    }
}
