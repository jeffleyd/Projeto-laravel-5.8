<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacModelOs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_model_os';

    public function sacProductAir()
    {
        return $this->belongsTo(ProductAir::class, 'product_id', 'id');
    }
    
    public function sacModelProtocol()
    {
        return $this->belongsTo(SacModelProtocol::class, 'sac_model_protocol_id', 'id');
    }

    public function sacPartProtocol()
    {
        return $this->hasMany(SacPartProtocol::class, 'sac_os_protocol_id', 'sac_os_protocol_id');
    }

    public function sacOsProtocol()
    {
        return $this->belongsTo(SacOsProtocol::class, 'sac_os_protocol_id', 'id');
    }
}
