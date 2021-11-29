<?php

namespace App\Services\Departaments\Logistics\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\LogisticsEntryExitRequests;
use Carbon\Carbon;


Trait CargoTransportRules
{
    public function rulesVerifyRegisterAnalyze() {

        $warehouse = $this->model->logistics_warehouse;
        if(!$warehouse) {
            throw new \Exception('Para enviar para aprovação o galpão deve estar cadastrado!');
        }
        
        if($warehouse->analyze_approv->count() == 0) {
            throw new \Exception('Para enviar para aprovação os aprovadores precisam estar cadastrados!');
        }
    }
	
	public function rulesVerifyReleaseHourRequest() {

        $hour_release = new Carbon($this->model->date_hour);
		
		if($this->model->warehouse_type_content_id == 11 or $this->model->type_reason == 6 or $this->model->type_reason == 9 or $this->model->type_reason == 7) {
			return true;
		}
            
		
		if($this->model->is_entry_exit == 1 && $this->model->is_content == 1) {
        
			if($hour_release->dayOfWeek == Carbon::SUNDAY) 
				throw new \Exception('Data de liberação desta solicitação só poderá ser de SEG à SÁB');

			/*if($hour_release->dayOfWeek == Carbon::SATURDAY) {
				if(!$hour_release->between($this->dateHourCarbon($hour_release, '07:00'), $this->dateHourCarbon($hour_release, '13:30')))
					throw new \Exception('Horário de liberação está fora do expediente permitido no sábado');
			}*/

			if(!$hour_release->between($this->dateHourCarbon($hour_release, '07:00'), $this->dateHourCarbon($hour_release, '01:00')->addDay()))
				throw new \Exception('Horário de liberação do recebimento está fora do expediente permitido');

			if($hour_release->between($this->dateHourCarbon($hour_release, '16:00'), $this->dateHourCarbon($hour_release, '17:00')))
				throw new \Exception('Horário de liberação do recebimento não pode ser realizado entre as 16:00 e 17:00');

			$requests = LogisticsEntryExitRequests::where('date_hour', $hour_release)->where('is_liberate', 0)->where('is_denied', 0)
			->where(function($query) {
				$query->where('has_analyze', 1)->orWhere('is_approv', 1);
			});
			if($requests->count() >= 2)
				throw new \Exception('Já existem duas liberações para esta data e horário');
			
		} else {
			return true;
		}	
    }   

    private function dateHourCarbon($date, $hour) {
        return Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() .' '. $hour);
    }
}