<?php

namespace App\Imports;

use App\Model\Parts;
use App\Model\ProductAir;
use App\Model\ProductControl;
use App\Model\ProductParts;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Http\Request;

class PartsImport implements ToCollection, WithChunkReading
{
    function __construct(Request $request) 
    {    
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        foreach($rows as $index => $col)
        {   
            $total_cols = $rows[0]->count();
            if($index != 0) {

                if(!empty($col[0]) and !empty($col[1])) {

                    $part = Parts::where('code', $col[1])->first();

                    if(!$part) {  
                        $part = new Parts;
                        $part->product_category_id = $this->request->product_category;
                        $part->code = $col[1];
                        $part->description = $col[0];
                        $part->save();
                    }

                    if ($col[2] != '') {
                        for ($i=2; $i < $total_cols; $i++) { 

                            if($col[$i] != '') {
        
                                $model = ProductAir::where('model', $col[$i])->first();
                                if(!$model) {
                                    $model = new ProductAir;
                                    $model->model = $col[$i];
                                    $model->unity = $this->request->unity;
                                    $model->product_category_id = $this->request->product_category;
                                    $model->product_sub_level_1_id = $this->request->product_sub_level_1 != null ? $this->request->product_sub_level_1 : 0;
                                    $model->product_sub_level_2_id = $this->request->product_sub_level_2 != null ? $this->request->product_sub_level_2 : 0;
                                    $model->product_sub_level_3_id = $this->request->product_sub_level_3 != null ? $this->request->product_sub_level_3 : 0;
                                    $model->commercial = $this->request->commercial == "" ? 0 : 1;
                                    $model->residential = $this->request->residential == "" ? 0 : 1;
        
                                    if($model->save()) {
                                        $this->saveProductControl($model->id, $this->request->product_category, $part->id);
                                    } else {
                                        DB::rollBack();
                                        return $request->session()->put('error', "Erro ao salvar o modelo!");
                                    }
                                } else {
                                    $this->saveProductControl($model->id, $this->request->product_category, $part->id);
                                }
                            }
                        }
                    }
                }
            }
        }
        DB::commit();
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    protected function saveProductParts($control_id, $part_id) {
        
        $product_parts = ProductParts::where('product_control_id', $control_id)->where('part_id', $part_id)->first();
        if(!$product_parts) {
            $product_parts = new ProductParts;
            $product_parts->product_control_id = $control_id;
            $product_parts->part_id = $part_id;

            if ($product_parts->save()) {
                return true;
            }
            else {
                return $request->session()->put('error', "Erro ao salvar product parts!");
            }
        }
    }

    protected function saveProductControl($model_id, $category_id, $part_id) {

        $product_control = ProductControl::where('product_id', $model_id)->first();                                    
        if(!$product_control) {
            $product_control = new ProductControl;
            $product_control->product_id = $model_id;
            $product_control->product_category_id = $category_id;
            $product_control->voltage_id = 2;
            if($product_control->save()) {
                $this->saveProductParts($product_control->id, $part_id);
            } else {       
                DB::rollBack();
                return $request->session()->put('error', "Erro ao salvar product control!");
            }
        } else {
            $this->saveProductParts($product_control->id, $part_id);
        }
    }    
}