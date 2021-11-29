<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class OrderFieldTablePrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_field_table_price';
    protected $connection = 'commercial';
    protected $appends = [
        'column_salesman_name'
    ];

    public function OrderTablePriceRules()
    {
        return $this->hasMany(OrderTablePriceRules::class, 'field_id', 'id');
    }

    public function getColumnSalesmanNameAttribute() {

        return $this->convertColumnName($this->column_salesman_table_price);
    }

    private function convertColumnName($key) {

        $arr = [
            'type_client' => 'Tipo de cliente',
            'descont_extra' => 'Desconto Extra',
            'charge' => 'Carga',
            'contract_vpc' => 'Contrato / VPC',
            'average_term' => 'Prazo médio',
            'pis_confis' => 'PIS / Confis',
            'icms' => 'ICMS',
            'adjust_commercial' => 'Ajuste comercial',
            'is_suframa' => 'É Suframa',
            'cif_fob' => 'CIF / FOB',
            'is_programmed' => 'É PROGRAMADO?',
        ];

        return $arr[$key];

    }
}
