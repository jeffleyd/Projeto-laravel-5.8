<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsTransporterCart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_transporter_cart';
    protected $appends = [
        'type_cart_name',
		'transporter_name'
    ];

    public function getTypeCartNameAttribute() {
        $arr = [
            1 => 'Carreta Baú',
            2 => 'Carreta Sider',
            3 => 'Porta Container',
            4 => 'Carreta Prancha',
            5 => 'Carreta Basculhante',
            6 => 'Carreta Plataforma'
        ];

        return isset($arr[$this->type_cart]) ? $arr[$this->type_cart] : 'Não definido';
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
