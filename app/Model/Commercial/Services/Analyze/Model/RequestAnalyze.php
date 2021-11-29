<?php

namespace App\Model\Commercial\Services\Analyze\Model;

use App\Model\Users;
use Illuminate\Database\Eloquent\Model;

class RequestAnalyze extends Model
{
    protected $table = 'request_analyze';
    protected $connection = 'commercial';
    protected $hidden = [
        'analyze_id',
    ];
    protected $fillable = [
        'r_code',
        'description',
        'is_approv',
        'is_reprov',
        'is_suspended',
        'position',
        'version',
        'is_holiday',
        'analyze_type'
    ];

    public function analyze() {
        return $this->morphTo();
    }

    public function users() {
        return $this->setConnection('mysql')->hasOne(Users::class, 'r_code', 'r_code');
    }

}
