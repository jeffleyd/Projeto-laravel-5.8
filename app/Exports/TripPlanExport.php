<?php

namespace App\Exports;

use App\Model\TripPlan;
use Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class TripPlanExport implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct($r_code, $finality, $start_country, $start_state, $end_country, $end_state, $hotel, $status, $start_d, $end_d)
    {
        $this->r_code = $r_code;
        $this->finality = $finality;
        $this->start_country = $start_country;
        $this->start_state = $start_state;
        $this->end_country = $end_country;
        $this->end_state = $end_state;
        $this->hotel = $hotel;
        $this->status = $status;
        $this->start_d = $start_d;
        $this->end_d = $end_d;
    }

    public function query()
    {
        $plan = (new TripPlan)->newQuery();
        $plan->Join('trips', 'trip_plan.trip_id', '=', 'trips.id')
            ->select('trip_plan.*', 'trips.r_code')
            ->where('trip_plan.is_cancelled', 0);

        // Search for a Plan based on parameter.
        if (!empty($this->r_code)) {
            $plan->where('trips.r_code', $this->r_code);
        }
        if (!empty($this->finality)) {
            $plan->where('trip_plan.finality', $this->finality);
        }
        if (!empty($this->start_country) and !empty($this->start_state)) {
            $plan->where('trip_plan.origin_country', $this->start_country)
            ->where('trip_plan.origin_state', $this->start_state);
        }
        if (!empty($this->end_country) and !empty($this->end_state)) {
            $plan->where('trip_plan.destiny_country', $this->end_country)
            ->where('trip_plan.destiny_state', $this->end_state);
        }
        if (!empty($this->status)) {
            if ($this->status == 1) {
                $plan->where('trip_plan.is_approv', 1);
            } else if ($this->status == 2) {
                $plan->where('trip_plan.is_reprov', 1);
            } else if ($this->status == 3) {
                $plan->where('trip_plan.has_analyze', 1);
            } else if ($this->status == 4) {
                $plan->where('trip_plan.is_completed', 1);
            } else if ($this->status == 5) {
                $plan->where('trip_plan.is_reprov', 0)
                ->where('trip_plan.is_reprov', 0)
                ->where('trip_plan.has_analyze', 0)
                ->where('trip_plan.is_completed', 0);
            }
        }
        if (!empty($this->start_d)) {
            $plan->where('trip_plan.origin_date', '>=', $this->start_d);
        }
        if (!empty($this->end_d)) {
            $plan->where('trip_plan.origin_date', '<=', $this->end_d);
        }
        if (!empty($this->hotel)) {
            if ($this->hotel == 1) {
                $plan->where('trip_plan.has_hotel', 1);
            } else if ($this->hotel == 2) {
                $plan->where('trip_plan.has_hotel', 0);
            }
        }
        return $plan;
    }

    /**
    * @var TripPlan $trips
    */
    public function map($trips): array
    {
        $status = 0;
        if ($trips->is_approv == 1 and $trips->is_completed == 0) {
            $status = __('trip_i.tpe_status_1');
        } else if ($trips->is_reprov == 1) {
            $status = __('trip_i.tpe_status_2');
        } else if ($trips->has_analyze == 1) {
            $status = __('trip_i.tpe_status_3');
        } else if ($trips->is_completed == 1) {
            $status = __('trip_i.tpe_status_4');
        } else {
            $status = __('trip_i.tpe_status_5');
        }

        $enter_hotel = "";
        $exit_hotel = "";
        $checkout_hotel = "";
        $reason_hotel = "";
        $dispatch = "";
        if ($trips->has_hotel == 1) {
            $enter_hotel = date('Y-m-d', strtotime($trips->hotel_date));
            $exit_hotel = date('Y-m-d', strtotime($trips->hotel_exit));
            $checkout_hotel = $trips->hotel_checkout == 1 ? "Normal" :"Later";
            $reason_hotel = $trips->dispatch > 1 ? $trips->dispatch_reason : "";
            $dispatch = $trips->dispatch;
        }

        return [
            [
                $trips->id,
                getENameFull($trips->r_code),
                finalityName($trips->finality),
                $trips->other,
                $trips->goal,
                periodName($trips->origin_period),
                GetCountryName($trips->origin_country),
                GetStateName($trips->origin_country, $trips->origin_state),
                $trips->origin_city,
                date('Y-m-d', strtotime($trips->origin_date)),
                date('Y-m-d', strtotime($trips->destiny_date)),
                periodName($trips->destiny_period),
                GetCountryName($trips->destiny_country),
                GetStateName($trips->destiny_country, $trips->destiny_state),
                $trips->destiny_city,
                $dispatch,
                $reason_hotel,
                $enter_hotel,
                $checkout_hotel,
                GetCountryName($trips->hotel_country),
                GetStateName($trips->hotel_country, $trips->hotel_state),
                $trips->hotel_city,
                $exit_hotel,
                $trips->hotel_address,
                $trips->created_at,
                $status,
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '#ID',
            'Nome',
            __('trip_i.tpec_1'),
            '',
            __('trip_i.tpec_2'),
            __('trip_i.tpec_3'),
            __('trip_i.tpec_4'),
            __('trip_i.tpec_5'),
            __('trip_i.tpec_6'),
            __('trip_i.tpec_7'),
            __('trip_i.tpec_8'),
            __('trip_i.tpec_9'),
            __('trip_i.tpec_10'),
            __('trip_i.tpec_11'),
            __('trip_i.tpec_12'),
            __('trip_i.tpec_13'),
            __('trip_i.tpec_14'),
            __('trip_i.tpec_15'),
            __('trip_i.tpec_16'),
            __('trip_i.tpec_17'),
            __('trip_i.tpec_18'),
            __('trip_i.tpec_19'),
            __('trip_i.tpec_20'),
            __('trip_i.tpec_21'),
            __('trip_i.tpec_22'),
            __('trip_i.tpec_23'),
        ];
    }
}
