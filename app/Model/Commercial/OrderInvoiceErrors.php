<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceErrors extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_invoice_errors';
    protected $connection = 'commercial';
}
