<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductAir extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_air';
    protected $appends = [
        'calc_cubage',
		'model_formated'
    ];

    public function product_control() {

        return $this->belongsTo(ProductControl::class, 'id', 'product_id');
    }

    public function productSubLevel1() {

        return $this->belongsTo(ProductSubLevel1::class, 'product_sub_level_1_id', 'id');
    }

    public function productSubLevel2() {

        return $this->belongsTo(ProductSubLevel2::class, 'product_sub_level_2_id', 'id');
    }
	
	private function convertValueToCubage($value) {
        // Converte milimetros(mm) em metros(m)
        return $value / 1000;
    }

    public function getCalcCubageAttribute() {
		$cubage = $this->convertValueToCubage($this->length_box)
                * $this->convertValueToCubage($this->width_box)
                * $this->convertValueToCubage($this->height_box);

        return round($cubage, 2);
    }
	
	public function getModelFormatedAttribute() {
        if (substr($this->model, -2) == '/I' or substr($this->model, -2) == '/O')
            $format = substr($this->model, 0, -2);
        else
            $format = $this->model;
        
        return $format;
    }
}
