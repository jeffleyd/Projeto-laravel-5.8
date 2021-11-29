<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SacAuthorized extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sac_authorized';
	
	public function sac_os_protocol()
    {
        return $this->hasMany(SacOsProtocol::class, 'authorized_id', 'id');
    }

    public function historic()
    {
        return $this->hasMany(SacAuthorizedHistoric::class, 'authorized_id', 'id');
    }
	
	public function sacTypes()
    {
        return $this->hasMany(SacAuthorizedType::class, 'id_authorized', 'id');
    }

    public function scopeSkillFilter($query, $skill) {

        return $query->whereHas('sacTypes', function($q) use ($skill) {
            $q->where('id_sac_type', $skill);
        });
    }
}
