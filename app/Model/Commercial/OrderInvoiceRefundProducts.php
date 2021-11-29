<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceRefundProducts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_invoice_refund_products';
    protected $connection = 'commercial';
}
