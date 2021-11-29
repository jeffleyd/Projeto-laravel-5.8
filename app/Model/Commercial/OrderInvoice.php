<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_invoice';
    protected $connection = 'commercial';

    public function orderSales() {
        return $this->belongsTo(OrderSales::class, 'order_sales_id', 'id');
    }
	
	public function orderInvoiceProducts() {
        return $this->hasMany(OrderInvoiceProducts::class, 'order_invoice_id', 'id');
    }
	
    public function orderInvoiceRefund() {
        return $this->hasMany(OrderInvoiceRefund::class, 'order_invoice_id', 'id');
    }
}
