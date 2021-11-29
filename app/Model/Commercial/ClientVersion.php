<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientVersion extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_version';
    protected $connection = 'commercial';
    protected $dates = ['deleted_at'];

    public function ClientImdtAnalyze() {
        return $this->hasMany(ClientImdtAnalyze::class, 'client_id', 'client_id');
    }

	public function ClientRevisionAnalyze() {
        return $this->hasMany(ClientRevisionAnalyze::class, 'client_id', 'client_id');
    }
	
	public function ClientJudicialAnalyze() {
        return $this->hasMany(ClientJudicialAnalyze::class, 'client_id', 'client_id');
    }
	
    public function ClientCommercialAnalyze() {
        return $this->hasMany(ClientCommercialAnalyze::class, 'client_id', 'client_id');
    }

    public function ClientFinancyAnalyze() {
        return $this->hasMany(ClientFinancyAnalyze::class, 'client_id', 'client_id');
    }

    public function scopeFilterPerVersion($query, $nmb) {
        return $query->whereHas('ClientImdtAnalyze', function ($q) use($nmb) {
            $q->where('version', $nmb);
        });
    }
}
