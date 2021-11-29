<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacRemittanceParts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_remittance_parts';

    public function product_air()
    {
        return $this->hasOne(ProductAir::class, 'id', 'model');
    }

    public function parts()
    {
        return $this->hasOne(Parts::class, 'id', 'part');
    }

    public function sac_remittance_part()
    {
        return $this->belongsTo(SacRemittancePart::class, 'sac_remittance_part_id', 'id');
    }

    public function scopeSacRemittancePart($query, $value) 
    {
        return $query->whereHas('sac_remittance_part', function ($q) use ($value) {
            $q->where('code', $value);
        });
    }

    public function sac_remittance_part_costs() 
    {
        return $this->hasOne(SacRemittancePartCosts::class, 'sac_remittance_parts_id', 'id');
    }

    public function remittance_cost_field()
    {
        $data = $this->sac_remittance_part_costs;

        if (!$data) {
            return (object) [
                'number_order' => '-',
                'number_di' => '-', 
                'number_nf' => '-',
                'date_emission_nf' => null,
				'hour_emission_nf' => null,
                'date_billing' => null,
				'hour_billing' => null,
                'value_part' => 0.00,
                'value_shipping' => 0.00,
				'value_shipping_return' => 0.00,
				'observation' => ''
            ];
        }
        return $data;
    }

    public function getPartSumTotalAttribute() 
    {   
        return array_sum([
            $this->remittance_cost_field()->value_part,
            $this->remittance_cost_field()->value_shipping,
            $this->service_value
        ]);
    }
}