<?php

namespace App\Helpers\Commercial;

use App;
use App\Http\Controllers\Services\CommercialTrait;
use App\Model\Commercial\OrderAvaibleMonth;
use Exception as ACPBException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplyConditionPriceBase
{
    use CommercialTrait;

    private $fields;
    private $rules;
    private $table;

    public function __construct($fields, $rules, $table)
    {
        $this->fields = $fields;
        $this->rules = $rules;
        $this->table = $table;

    }


    /**
     * @param $price [Valor base do conjunto]
     * @param $setproduct [Model do conjunto]
     * @param $date [Y-m-d H:i:s]
     * @param bool $is_programmed [Programado ou não]
     * @return float
     */
    public function calcPrice(float $price, $setproduct, $date, $is_programmed = true) {

        $adjust_commercial = $this->findValueAndConvertFactor(0, 'adjust_commercial', $this->table->adjust_commercial);
        $average_term = $this->findValueAndConvertFactor(0, 'average_term', $this->table->average_term);
        $contract_vpc = $this->findValueAndConvertFactor(0, 'contract_vpc', $this->table->contract_vpc);
        $pis_confis = $this->findValueAndConvertFactor($this->table->pis_confis);
        $cif_fob = $this->findValueAndConvertFactor($this->table->cif_fob);

        if (!$is_programmed)
            $is_programmed = $this->findValueAndConvertFactor(0, 'is_programmed');
        else
            $is_programmed = 0;

        if ($this->table->is_suframa == 2)
            $suframa = $this->findValueAndConvertFactor(0, 'is_suframa');
        else
            $suframa = 0;

        $icms = $this->findValueAndConvertFactor($this->table->icms);

        $type_client = $this->findValueAndConvertFactor($this->table->type_client);
        $descont_extra = $this->findValueAndConvertFactor(0, 'descont_extra', $this->table->descont_extra);
        $charge = $this->findValueAndConvertFactor($this->table->charge);

        // Primeira parte da formula das taxas de Ajuste comercial, é programado, prazo médio, vpc, pis E ICMS
        $rule1 = $price*((1+($adjust_commercial/100))*(1+($is_programmed/100))*(1+($average_term/100))*(1+($contract_vpc/100)))/(1-($pis_confis/100))/(1-($icms/100));

        if (is_object($setproduct->condition_in_month)) {
            $d =date('Y-n-01', strtotime($date));
            $conditions_month = $setproduct->condition_in_month->$d->factors;
        } else {
            $conditions_month = $setproduct->condition_in_month[date('Y-n-01', strtotime($date))]['factors'];
        }

        // Validar se o produto pode aplicar a regra
        if ($setproduct->has_type_client == 0)
            $type_client = 0;

        // Segunda parte da formula que atribui o fator no final da formula com base no mês/ano e conclui com outros campos.
        if (count($conditions_month) > 0) {
            $new_calc = $rule1*(1-($type_client/100))*(1-($descont_extra/100))*(1-($suframa/100))*(1-($cif_fob/100))*(1+($charge/100));
            foreach ($conditions_month as $cond) {
                $new_calc = $new_calc * (1+($cond/100));
            }
            $price = ceil($new_calc);
        } else {
            $price = ceil($rule1*(1-($type_client/100))*(1-($descont_extra/100))*(1-($suframa/100))*(1-($cif_fob/100))*(1+($charge/100)));
        }

        // Obs. Para dar certo, a formula precisa ser feita separada.
        return $price;
    }

    private function convertFactorLogic($field, $vzs = 1) {

        if ($field) {
            if ($field->is_static) {

                return (pow(1*($field->logic), ($vzs/30))-1) * 100;

            } else {

                $new_factor = 1 + ($vzs * (($field->logic - 1) * 100) /100);
                return ($new_factor - 1) * 100;
            }
        } else return new ACPBException('Não foi possível encontrar o campo no campo de dados!');

    }

    private function findValueAndConvertFactor($field_id = 0, $field_name = '', $multiplay = 1) {

        if (empty($field_name)) {
            if ($field_id)
                return $this->convertFactorLogic($this->rules->where('field_id', $field_id)->first());
            else
                return 0;
        } else {
            $field = $this->fields->where('column_salesman_table_price', $field_name)->first();
            return $this->convertFactorLogic($this->rules->where('field_id', $field->id)->first(), $multiplay);
        }
    }

}
