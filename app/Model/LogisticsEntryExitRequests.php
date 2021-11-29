<?php

namespace App\Model;

use \App\Model\Services\Analyze\ProcessAnalyze;
use App\Model\Services\Analyze\Model\RequestAnalyzeApprovers;
use App\Model\Services\Analyze\Model\RequestAnalyzeObservers;

class LogisticsEntryExitRequests extends ProcessAnalyze
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_entry_exit_requests';
    protected $appends = [
        'type_reason_name',
        'is_my_request',
        'who_excute_action',
		'who_business'
    ];
	
	public function analyze_approv() {
        return $this->morphMany(RequestAnalyzeApprovers::class, 'analyze');
    }

    public function analyze_observ() {
        return $this->morphMany(RequestAnalyzeObservers::class, 'analyze');
    }

    public function request_user() {
        return $this->belongsTo(Users::class, 'request_r_code', 'r_code');
    }

    public function logistics_warehouse_type_content() {
        return $this->hasOne(LogisticsWarehouseTypeContent::class, 'id', 'warehouse_type_content_id');
    }

    public function logistics_transporter() {
        return $this->hasOne(LogisticsTransporter::class, 'id', 'transporter_id');
    }

    public function logistics_warehouse() {
        return $this->hasOne(LogisticsWarehouse::class, 'id', 'warehouse_id');
    }

    public function logistics_transporter_cart() {
        return $this->hasOne(LogisticsTransporterCart::class, 'id', 'transporter_cart_id');
    }

    public function logistics_container() {
        return $this->hasOne(LogisticsContainer::class, 'id', 'transporter_container_id');
    }

    public function logistics_transporter_driver() {
        return $this->hasOne(LogisticsTransporterDriver::class, 'id', 'transporter_driver_id');
    }

    public function logistics_entry_exit_visit() {
        return $this->hasOne(LogisticsEntryExitVisitant::class, 'id', 'entry_exit_visitant_id');
    }

    public function logistics_entry_exit_requests_items() {
        return $this->hasMany(LogisticsEntryExitRequestsItems::class, 'entry_exit_requests_id', 'id');
    }
	
	public function logistics_entry_exit_requests_attachs() {
        return $this->hasMany(LogisticsEntryExitRequestsAttachs::class, 'entry_exit_requests_id', 'id');
    }
	
	public function logistics_entry_exit_requests_people() {
        return $this->hasMany(LogisticsEntryExitRequestsPeople::class, 'entry_exit_requests_id', 'id');
    }
	
	public function logistics_supplier() {
        return $this->hasOne(LogisticsSupplier::class, 'id', 'supplier_id');
    }
	
    public function logistics_entry_exit_gate() {

        return $this->hasOne(LogisticsEntryExitGate::class, 'id', 'entry_exit_gate_id');
    }

    public function logistics_transporter_vehicle() {
        return $this->hasOne(LogisticsTransporterVehicle::class, 'id', 'transporter_vehicle_id');
    }
	
	public function logistics_entry_exit_requests_schedule() {
        return $this->hasMany(LogisticsEntryExitRequestsSchedule::class, 'entry_exit_requests_id', 'id');
    }

    public function who_approv() {

        return $this->hasOne(LogisticsEntryExitRequestsAnalyze::class, 'entry_exit_requests_id', 'id')
            ->max('position')
            ->where('is_approv', 1);
    }

    public function Users() {
        return $this->hasOne(Users::class, 'id', 'cancelled_r_code');
    }

    public function SecurityGuardLiberateDenied() {
        return $this->hasOne(LogisticsEntryExitSecurityGuard::class, 'id', 'entry_exit_security_guard_id');
    }
	
	public function getWhoBusinessAttribute() {
		$transport = $this->logistics_transporter;
		$supplier = $this->logistics_supplier;
		if ($supplier)
			return $supplier->name;
		elseif ($transport)
			return $transport->name;
		else
			return '';
	}

    public function getWhoExcuteActionAttribute() {
        if ($this->is_liberate or $this->is_denied) {
            $user = $this->SecurityGuardLiberateDenied;
            if ($user)
                return $user->name;
            else
                return '';
        } else if ($this->is_cancelled) {
            $user = $this->Users;
            if ($user)
                return $user->full_name;
            else
                return '';
        } else {
            return '';
        }
    }

    public function getIsMyRequestAttribute() {
        if (\Session::has('security_guard_data')) {
            $gate_id = \Session::get('security_guard_data')->entry_exit_gate_id;
            if ($this->entry_exit_gate_id == $gate_id) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	public function configClass($type) {
		$name = 'Transporte de cargas';
		$url = '/logistics/request/cargo/transport/approv/list';
		if ($this->type_reason == 3 or $this->type_reason == 9 or $this->type_reason == 10) {
			$name = 'Visitante & Prestador';
			$url = '/logistics/request/visitor/service/list/approv';
		}
		
        return [
            'name' => $name,
			'url' => $url,
            'arr_mark' => config('gree.analyze_office_mark')['financy'], // Não usado
            'activemenu' => 'mAdmin,mFinancyRefund,mFinancyRefundApprovers' // Não usado
        ][$type];
    }

    public function getTypeReasonNameAttribute() {
        $arr = [
            1 => 'Entregar compra',
            2 => 'Carregamento',
            3 => 'Visita',
            4 => 'Importação',
            5 => 'Transfêrencia',
            6 => 'Retirada de venda',
            7 => 'Coleta',
            8 => 'Entrega de avaria',
            9 => 'Prestador de serviço',
            10 => 'Seleção para contratação',
			11 => 'Outros',
			12 => 'Manobra'
        ];
        return isset($arr[$this->type_reason]) ? $arr[$this->type_reason] : 'Não definido';
    }
}
