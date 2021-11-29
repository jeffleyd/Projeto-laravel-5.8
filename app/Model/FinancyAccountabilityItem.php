<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FinancyAccountabilityItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_accountability_item';

    protected $appends = [
        'date_formated',

        'total_currency',
        'total_money_currency',
        'total_money',
        'total_formated',
        'currency_description',
        'type_description',
    ];
    protected $dates = [
        'date',
    ];

    public function getDateFormatedAttribute(){
        return date('d/m/Y', strtotime($this->attributes['date']));
    }

    public function getCurrencyDescriptionAttribute(){
        return currency($this->attributes['currency']);
    }

    public function getTypeDescriptionAttribute(){
        if($this->type_entry == 2){
            return "TRANSF. / DEVOLUÇÃO";
        }else{
            return refundType($this->attributes['type']);
        }
        
    }

    public function getTotalMoneyAttribute(){
        if ($this->currency > 1) {
            return formatMoney($this->total * $this->quotation,"");
        }else{
            return formatMoney($this->attributes['total']);
        }
    }
    public function getTotalMoneyCurrencyAttribute(){
        if ($this->currency > 1) {
            return formatMoney($this->attributes['total'],$this->currency)." (".formatMoney($this->total * $this->quotation,"").")";

        }else{
            return formatMoney($this->attributes['total']);
        }
    }
    public function getTotalCurrencyAttribute(){
        if ($this->currency > 1) {
            return formatMoney($this->attributes['total'],$this->currency);

        }else{
            return formatMoney($this->attributes['total']);
        }
    }
    public function getTotalFormatedAttribute(){
        return formatMoney($this->attributes['total'],null,false);
    }

    public function attach()
    {
        return $this->hasMany(FinancyAccountabilityAttach::class, 'financy_accountability_item_id', 'id');
    }

}
