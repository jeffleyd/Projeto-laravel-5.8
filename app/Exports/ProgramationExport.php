<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProgramationExport implements FromView
{

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function view(): View
    {
        return view('gree_commercial.exports.programation', [
            'orders' => $this->order
        ]);
    }
}
