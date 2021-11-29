<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qrcode';

    protected $appends = [
        'status_text'
    ];

    public function products()
    {
        return $this->hasMany(QrcodeProducts::class, 'qrcode_id', 'id');
    }
  
    public function getStatusTextAttribute(){

        
        if($this->analist_reprov == 1 || $this->mng_reprov == 1){
            return '<span class="badge badge-light-danger">Reprovado</span>';
        }

        if($this->analist_approv == 1 && $this->mng_approv == 1){
            return '<span class="badge badge-light-success">Aprovado</span>';
        }
        if($this->has_suspended == 1 ){
            return '<span class="badge badge-light-info">Suspenso</span>';
        }

        return '<span class="badge badge-light-warning">Em Análise</span>';
    }
    public function setStatusTextAttribute($value){

        $this->status_text = $value;
    }
    public function setStatus($value){

        $this->status_text = $value;

    }

}
