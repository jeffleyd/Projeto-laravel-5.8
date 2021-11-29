<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QrcodeProducts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qrcode_products';

    protected $appends = [
      'model_name'
    ];

    public function getModelNameAttribute(){

        $product_id = $this->product_id;
        
        if(isset($product_id) ){
            $product = ProductAir::where('id', $product_id)->first();
            if($product){
                return $product->model;
            }else{
                return "";
            }
            
        }
        return "";
        
    }

}
