<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsContainer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_container';
    protected $appends = [
		'company_name',
        'transport'
    ];

    public function logistics_transporter() {
        return $this->belongsTo(LogisticsTransporter::class, 'transporter_id', 'id');
    }
	
	public function getCompanyNameAttribute() {

        if($this->transporter_id) {
            return $this->logistics_transporter ? $this->logistics_transporter->name : '';
        } else {
            return $this->name;
        }
    }

    public function getTransportAttribute() {
        if ($this->transporter_id) {
            return $this->logistics_transporter;
        } else {
            return (object) [
                'name' => $this->name,
                'identity' => $this->identity,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'phone' => $this->phone,
                'ramal' => $this->ramal,
                'email' => $this->email,
            ];
        }
    }
}
