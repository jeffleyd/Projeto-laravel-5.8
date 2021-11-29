<?php

namespace App\Services\Departaments\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Jobs\SendMailJob;
use App\Jobs\SendMailCopyJob;
use App\Model\WarehouseEntryExitItems;

Trait WarehouseTrait {

    public function saveEntryExitItems($collect, array $arr_keys, \Closure $funcAnom = null) {

        try {

            $model = new WarehouseEntryExitItems;
            $insert = [];
            foreach($collect as $index => $val) {

                $add = [];
                foreach ($arr_keys as $key => $value) {
                    if($key == 'pair_keys') {
                        foreach ($value as $key_sub => $val_sub) {
                            $add[$val_sub] = $val->$key_sub;
                        }
                    } else {
                        $add[$key] = $value;
                    }
                }

                array_push($insert, $add);
            }
            $model->insert($insert);

            return true;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}