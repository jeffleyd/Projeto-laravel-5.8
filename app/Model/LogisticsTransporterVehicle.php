<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsTransporterVehicle extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_transporter_vehicle';
    protected $appends = [
        'type_vehicle_name',
		'transporter_name'
    ];

    public function getTypeVehicleNameAttribute() {
        $arr = [
            1 => 'VUC',
            2 => 'Caminhão Toco',
            3 => 'Cavalo mecânico dois eixos',
            4 => 'Cavalo mecânico com três eixos',
            5 => 'Cavalo Mecânico Traçado',
            6 => 'Bitrem',
            7 => 'Rodotrem',
            8 => 'Truck',
            9 => 'Bitruck'
        ];

        return isset($arr[$this->type_vehicle]) ? $arr[$this->type_vehicle] : 'Não definido';
    }
	
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
