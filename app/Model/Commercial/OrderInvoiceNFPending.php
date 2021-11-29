<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceNFPending extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_invoice_nf_pending';
    protected $connection = 'commercial';
}
