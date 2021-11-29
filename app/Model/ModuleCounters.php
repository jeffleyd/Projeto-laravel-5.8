<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModuleCounters extends Model
{
    protected $table = 'module_counters';
    protected $appends = ['code','sector'];

	public function getCodeAttribute()
	{
        $sector = $this->sector;
        if ($this->attributes['is_custom'])
            return $this->attributes['prefix'].$this->attributes['value'];
        else if ($this->attributes['is_reset'])
            return $this->attributes['prefix'].strtoupper(substr($sector, 0, 3)).date('my').'-'.$this->attributes['value'];
        else
            return strtoupper(substr($sector, 0, 3)).$this->attributes['prefix'].date('ym').$this->attributes['value'];
	}

	public function getSectorAttribute(){
        return "Comercial";
    }
    public function setSectorAttribute($value){
        $this->sector = $value;
    }
    public function setSector($value){
        $this->sector = $value;
    }
}
