<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class SalesmanTablePrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salesman_table_price';
    protected $connection = 'commercial';

    protected $appends = [
        'cif_fob_name',
        'type_client_name',
        'pis_confis_name',
        'charge_name',
        'icms_name',
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function salesman()
    {
        return $this->hasOne(Salesman::class, 'id', 'salesman_id');
    }
	
	public function set_product_price_fixed()
    {
        return $this->hasMany(SetProductPriceFixed::class, 'salesman_table_price_id', 'id');
    }

    public function scopeSalesmanFilter($query, $value)
    {
        return $query->whereHas('salesman', function ($q) use ($value) {
            $q->where('first_name', 'like', '%'.$value.'%')
            ->orWhere('identity', 'like', '%'.$value.'%');
        });
    }

    public function scopeClientFilter($query, $value)
    {
        return $query->whereHas('client', function ($q) use ($value) {
            $q->where('fantasy_name', 'like', '%'.$value.'%')
            ->orWhere('identity', 'like', '%'.$value.'%');
        });
    }

    public function getTypeClientNameAttribute()
    {
        $types = [
            1 => 'Varejo Regional',
            2 => 'Varejo Regional (Abertura)',
            3 => 'Especializado Regional',
            4 => 'Especializado Nacional',
            5 => 'Refrigerista Nacional',
            6 => 'Varejo Nacional',
            7 => 'E-commerce',
            8 => 'VIP',
			9 => 'Colaborador / Parceiro'
        ];
        if (isset($types[$this->type_client]))
            return $types[$this->type_client];
        else
            return '';
    }

    public function getPisConfisNameAttribute()
    {
        $types = [
            15 => 'Lurco Real (CNPJ)',
            16 => 'Lucro Presumido (CNPJ)',
            17 => 'Consumidor (CPF)',
            24 => 'Simplificado (CNPJ)',
            25 => 'Outros Clientes (CNPJ)',
        ];
        if (isset($types[$this->pis_confis]))
            return $types[$this->pis_confis];
        else
            return '';
    }

    public function getChargeNameAttribute()
    {
        $types = [
            10 => 'Carga completa',
            11 => 'Carga de 51% a 90%',
            12 => 'Carga menor que 50%',
        ];
        if (isset($types[$this->charge]))
            return $types[$this->charge];
        else
            return '';
    }

    public function getIcmsNameAttribute()
    {
        $types = [
            18 => '7%',
            19 => '12%',
            20 => '17%',
            21 => '18%',
        ];
        if (isset($types[$this->icms]))
            return $types[$this->icms];
        else
            return '';
    }

    public function getCifFobNameAttribute()
    {
        $types = [
            26 => 'Manaus',
            27 => 'RR/AC/RO/AP/PA',
            28 => 'NORDESTE',
            29 => 'SUDESTE',
            30 => 'CENTROESTE',
            31 => 'SUL',
        ];
        if (isset($types[$this->cif_fob]))
            return 'FOB '.'('.$types[$this->cif_fob].')';
        else
            return 'CIF';
    }
}
