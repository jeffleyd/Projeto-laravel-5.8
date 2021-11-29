<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacOsAnalyze extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_os_analyze';
    protected $appends = [
        'date_formmat',
    ];

    public function sacOsProtocol()
    {
        return $this->belongsTo(SacOsProtocol::class, 'sac_os_protocol_id', 'id');
    }

    public function sacUsers()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }

    public function getDateFormmatAttribute(){
        return date('d/m/Y H:i:s', strtotime($this->created_at));
    }
	
	public function users() {   
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }

}
