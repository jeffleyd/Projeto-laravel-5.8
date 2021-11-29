<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Casts\NumberFormat;

class FinancyAccountabilityManualEntry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_accountability_manual_entry';

    protected $appends = [
        'type_entry_text',
        'p_method_text',
        'date_formated',
        'total_formated',
    ];

    protected $dates = [
        'date',
    ];


    public function lending()
    {
        return $this->belongsTo(FinancyLending::class, 'financy_lending_id', 'id');
    }
    
    public function attach()
    {
        return $this->hasMany(FinancyAccountabilityAttach::class, 'financy_accountability_manual_entry_id', 'id');
    }

    public function getTypeEntryTextAttribute(){
        $type_entry = [
            1=>"Entrada",
            2=>"Saída",
        ];

        return $type_entry[$this->attributes['type_entry']];
    }
    
    public function getPMethodTextAttribute(){
        $p_method = [
            1=>"Boleto",
            2=>"Transf. / D.Automático",
            3=>"Caixa",
        ];
        return $p_method[$this->attributes['p_method']];
    }

    public function getDateFormatedAttribute(){
        return date('d/m/Y', strtotime($this->attributes['date']));
    }

    public function getTotalFormatedAttribute(){
        return formatMoney($this->attributes['total'],null,false);
    }

}
