<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromView
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function view(): View
    {
        return view('gree_commercial.exports.order', [
            'orders' => $this->order,
        ]);
    }
}
