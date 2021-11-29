<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceProducts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_invoice_products';
    protected $connection = 'commercial';
	
	public function productAir() {
        return $this->setConnection('mysql')->hasOne(\App\Model\ProductAir::class, 'id', 'product_id');
    }
}
