<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Log;

class SetProduct extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'set_product';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];
    protected $appends = [
        'price_base',
        'condition_in_month',
        'calc_cubage',
		'is_visible'
    ];

    public function setProductOnGroup()
    {
        return $this->belongsToMany(SetProductGroup::class, 'set_product_on_group', 'set_product_id', 'set_product_group_id');
    }

    public function setProductOnGroupFilter()
    {
        return $this->hasOne(SetProductOnGroup::class, 'set_product_id', 'id');
    }

    public function scopeSearchProductPerGroup($query, $group) {

        return $query->whereHas('setProductOnGroupFilter', function ($q) use ($group) {
            $q->whereIn('set_product_group_id', $group);
        });
    }

    public function productAirEvap()
    {
        return $this->setConnection('mysql')->hasOne(\App\Model\ProductAir::class, 'id', 'evap_product_id');
    }

    public function productAirCond()
    {
        return $this->setConnection('mysql')->hasOne(\App\Model\ProductAir::class, 'id', 'cond_product_id');
    }

    public function  getPriceBaseAttribute()
    {

        return number_format($this->evap_product_price + $this->cond_product_price, 2, '.', '');
    }

    /**
     * @return mixed
     *
     * Usado para calcular o acumulado do mês sobre o valor total do conjunto.
     */
    public function getConditionInMonthAttribute() {

        if (\Session::get('commercial_months')) {
            $conditions = \Session::get('commercial_months');
            foreach ($conditions as $d => $value) {

                foreach ($conditions[$d]['model'] as $add) {
                    $product_ids = explode(',', $add->p_ids);

                    if ($add->type_apply == 1 and $this->setProductOnGroup->first()->id == $add->group_id_apply)
                        $conditions[$d]['factors'][] = $add->factor;
                    elseif ($add->type_apply == 2 and $this->is_qf == 1)
                        $conditions[$d]['factors'][] = $add->factor;
                    elseif ($add->type_apply == 3 and $this->is_qf == 0)
                        $conditions[$d]['factors'][] = $add->factor;
                    elseif ($add->type_apply == 4 and $this->capacity == 2 and $this->setProductOnGroup->first()->id == $add->group_id_apply)
                        $conditions[$d]['factors'][] = $add->factor;
                    elseif ($add->type_apply == 5 and $this->capacity == 1 and $this->setProductOnGroup->first()->id == $add->group_id_apply)
                        $conditions[$d]['factors'][] = $add->factor;
                    elseif ($add->type_apply == 6)
                        $conditions[$d]['factors'][] = $add->factor;
                    elseif ($add->type_apply == 7 and in_array($this->id, $product_ids) or $add->type_apply == 7 and empty($add->p_ids))
                        $conditions[$d]['factors'][] = $add->factor;
                }
            }


            return $conditions;
        } else {

            return [];
        }
    }

    private function convertValueToCubage($value) {
        // Converte milimetros(mm) em metros(m)
        return $value / 1000;
    }

    public function getCalcCubageAttribute() {
        $evap = $this->productAirEvap()->first();
        $cond = $this->productAirCond()->first();
        $result = 0.00;
        if ($evap and $cond) {
            $evap_cubage = $this->convertValueToCubage($evap->length_box)
                * $this->convertValueToCubage($evap->width_box)
                * $this->convertValueToCubage($evap->height_box);

            $cond_cubage = $this->convertValueToCubage($cond->length_box)
                * $this->convertValueToCubage($cond->width_box)
                * $this->convertValueToCubage($cond->height_box);

            $result = $evap_cubage + $cond_cubage;
        }

        return $result;
    }
	
	public function getIsVisibleAttribute() {
		
		$visible = true;
        if ($this->is_for_hide == 1) {
			$salesmans = collect(explode(',', $this->show_for_salesmans));
			if ($salesmans->count()) {
				if (\Session::has('salesman_data')) {
					if ($salesmans->search(\Session::get('salesman_data')->id) === false) {
						$visible = false;
					}
				}
			} else {
				$visible = false;
			}
		}

        return $visible;
    }
}
