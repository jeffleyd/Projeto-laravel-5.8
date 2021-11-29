<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdmRequests extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adm_requests';

    public function AdmRequestFiles()
    {
        return $this->hasOne(AdmRequestFiles::class);
    }

    public function AdmRequestAnalyze()
    {
        return $this->hasMany(AdmRequestAnalyze::class);
    }

    public function AdmRequestObservers()
    {
        return $this->hasMany(AdmRequestObservers::class);
    }

    public function Users()
    {
        return $this->hasOne(Users::class, 'r_code', 'r_code');
    }
}
