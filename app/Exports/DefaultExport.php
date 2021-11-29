<?php

namespace App\Exports;

use App\Jobs\SendMailJob;
use Illuminate\Contracts\View\View;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class DefaultExport implements FromView, ShouldQueue
{
    use Exportable,InteractsWithQueue, Queueable, SerializesModels;

    private $heading;
    private $rows;

    public function __construct($heading, $rows)
    {
        $this->heading = $heading;
        $this->rows = $rows;
    }

    public function view(): View
    {
        return view('gree_i.exports.default', [
            'heading' => $this->heading,
            'rows' => $this->rows
        ]);
    }
}
