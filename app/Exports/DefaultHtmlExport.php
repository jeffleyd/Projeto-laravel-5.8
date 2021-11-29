<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class DefaultHtmlExport implements FromView, WithMultipleSheets, WithTitle
{
    private $pattern;

    public function __construct(array $pattern)
    {
        $this->pattern = $pattern;
    }

    public function view(): View
    {
		$arr_all = $this->pattern;
        $arr_all['pattern'] = $this->pattern;

        return view($this->pattern['view'], $arr_all);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if (isset($this->pattern['sheet_title']))
            return $this->pattern['sheet_title'];
        else
            return 'Planilha 1';
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new DefaultHtmlExport($this->pattern),
        ];

        if (isset($this->pattern['sheets'])) {
            foreach ($this->pattern['sheets'] as $val) {
                $sheets[] = new DefaultHtmlExport($val);
            }
        }

        return $sheets;
    }
}
