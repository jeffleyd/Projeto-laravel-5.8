<?php

namespace App\Model\Commercial;

use App\Model\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderSales extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_sales';
    protected $connection = 'commercial';
	protected $appends = [
        'total_order',
        'total_invoice',
        'total_refund'
    ];

    public function salesman() {
        return $this->belongsTo(Salesman::class, 'request_salesman_id', 'id');
    }

    public function user() {
        return $this->setConnection('mysql')->belongsTo(Users::class, 'request_r_code', 'r_code');
    }
	
	public function whoCancel() {
        if ($this->cancel_r_code)
            return $this->setConnection('mysql')->belongsTo(Users::class, 'cancel_r_code', 'r_code')->first();
        elseif ($this->cancel_salesman_id)
            return $this->belongsTo(Salesman::class, 'cancel_salesman_id', 'id')->first();
        else
            return null;
    }

    public function programationMonth() {
        return $this->hasOne(ProgramationMonth::class, 'id', 'programation_month_id');
    }

    public function orderDelivery() {
        return $this->hasOne(OrderDelivery::class, 'order_sales_id', 'id');
    }

    public function orderSalesAttach() {
        return $this->hasMany(OrderSalesAttach::class, 'order_sales_id', 'id');
    }

    public function orderReceiver() {
        return $this->hasOne(OrderReceiver::class, 'order_sales_id', 'id');
    }

    public function orderProducts() {
        return $this->hasMany(OrderProducts::class, 'order_sales_id', 'id');
    }

    public function orderImdAnalyze() {
        return $this->hasMany(OrderImdtAnalyze::class, 'order_sales_id', 'id');
    }

    public function orderCommercialAnalyze() {
        return $this->hasOne(OrderCommercialAnalyze::class, 'order_sales_id', 'id');
    }

    public function orderFinancyAnalyze() {
        return $this->hasOne(OrderFinancyAnalyze::class, 'order_sales_id', 'id');
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    // VALID PROCESS IMDT
    public function imdt_analyze_one()
    {
        return $this->hasOne(OrderImdtAnalyze::class, 'order_sales_id', 'id')->orderBy('id', 'DESC');
    }
	
	public function orderInvoice()
    {
        return $this->hasMany(OrderInvoice::class, 'order_sales_id', 'id');
    }

    public function scopeValidProcessImdt($query, $request, $is_programmed = 1) {

        return $query->whereHas('imdt_analyze_one', function($q) use ($request) {
            $q->ValidProcessImdt($request);
        })->orWhere(function($q) use ($request, $is_programmed) {
            if ($is_programmed == 1) {
                $q->whereHas('programationMonth', function($q1) use ($request) {
                    $q1->whereHas('programation', function($q2) use ($request) {
                        $q2->whereHas('salesman', function($q3) use ($request) {
                            $q3->ValidProcessImdt($request);
                        });
                    });
                })->whereDoesntHave('imdt_analyze_one');
            } else {
                $q->whereHas('salesman', function($q1) use ($request) {
                    $q1->ValidProcessImdt($request);
                })->whereDoesntHave('imdt_analyze_one');
            }

        });
    }
	
	public function getTotalOrderAttribute() {
        return $this->orderProducts->sum('quantity');
    }

    public function getTotalInvoiceAttribute() {
        return $this->orderProducts->sum('quantity_invoice');
    }

    public function getTotalRefundAttribute() {
        return $this->orderProducts->sum('quantity_invoice_refund');
    }
}
