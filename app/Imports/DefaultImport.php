<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DefaultImport implements ToModel, WithStartRow, WithMultipleSheets
{
    private $model;
    private $columns;
    private $funcAnom;
    
    public function __construct($model, array $columns, \Closure $funcAnom = null)
    {
        $this->model = $model;
        $this->columns = $columns;
        $this->funcAnom = $funcAnom;
    }

    public function model(array $row)
    {
		$func = $this->funcAnom;
        $add = new $this->model;        
        foreach ($this->columns as $index => $val) {
            if($func) {
                $response = $func($row[$index], $val, $row, $add);
                if(!$response['result'])
                    break;
                $add = $response['collect'];
            } else {
				$response['result'] = true;
                $add->$val = $row[$index];
            }            
        }
        
        if($response['result'])
            $add->save();
        
        return null;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
}
