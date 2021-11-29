<?php


namespace App\Model\Commercial;
use Illuminate\Database\Eloquent\Model;

class ProgramationMonth extends model
{

    protected $table = 'programation_month';
    protected $connection = 'commercial';
    protected $appends = [
        'y_month'
    ];

    public function programation() {
        return $this->belongsTo(Programation::class, 'programation_id', 'id');
    }

    public function orderSales() {
        return $this->hasMany(OrderSales::class, 'programation_month_id', 'id');
    }

    public function getYMonthAttribute() {
        return date('Y-m', strtotime($this->yearmonth));
    }

}
