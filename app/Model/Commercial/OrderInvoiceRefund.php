<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceRefund extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_invoice_refund';
    protected $connection = 'commercial';

    public function orderSales() {
        return $this->belongsTo(OrderSales::class, 'order_sales_id', 'id');
    }

    public function orderInvoice() {
        return $this->belongsTo(OrderInvoice::class, 'order_invoice_id', 'id');
    }
	
	public function orderInvoiceRefundProducts() {
        return $this->hasMany(OrderInvoiceRefundProducts::class, 'order_invoice_refund_id', 'id');
    }
}
