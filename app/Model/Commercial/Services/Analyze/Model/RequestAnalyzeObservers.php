<?php

namespace App\Model\Commercial\Services\Analyze\Model;

use App\Model\Users;
use Illuminate\Database\Eloquent\Model;

class RequestAnalyzeObservers extends Model
{
    protected $table = 'request_analyze_observers';
    protected $connection = 'commercial';
    protected $hidden = [
        'analyze_id',
    ];
    protected $fillable = [
        'r_code',
        'analyze_type'
    ];

    public function analyze() {
        return $this->morphTo();
    }

    public function users() {
        return $this->setConnection('mysql')->hasOne(Users::class, 'r_code', 'r_code');
    }

}
