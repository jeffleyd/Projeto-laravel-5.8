<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdmRequestAnalyze extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adm_request_analyze';

    public function Users()
    {
        return $this->hasOne(Users::class, 'r_code', 'r_code');
    }

    public function AdmRequestFiles()
    {
        return $this->hasOne(AdmRequestFiles::class, 'adm_requests_id', 'adm_requests_id');
    }

    public function AdmRequests()
    {
        return $this->hasOne(AdmRequests::class, 'id', 'adm_requests_id');
    }
	
	public function scopeAdmRequestFilter($query, array $arr) 
	{
		return $query->whereHas('AdmRequests', function($q) use ($arr) {
			foreach($arr as $i => $value) {
				$q->where($i, $value);
			}
			
		});
	}
    
}
