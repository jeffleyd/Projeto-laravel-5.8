<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsTransporterDriver extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_transporter_driver';
	
	protected $appends = [
        'transporter_name'
    ];
	
	public function logistics_transporter() {
        return $this->belongsTo(LogisticsTransporter::class, 'transporter_id', 'id');
    }
	
	public function logistics_supplier() {
        return $this->belongsTo(LogisticsSupplier::class, 'supplier_id', 'id');
    }

    public function getTransporterNameAttribute() {
        return $this->logistics_transporter ? $this->logistics_transporter->name : '';
    }
}
