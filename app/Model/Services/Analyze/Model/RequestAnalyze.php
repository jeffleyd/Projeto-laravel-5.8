<?php

namespace App\Model\Services\Analyze\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Users;

class RequestAnalyze extends Model
{
    protected $table = 'request_analyze';
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
        'analyze_type',
		'updated_at',
        'mark'
    ];

    public function analyze() {
        return $this->morphTo();
    }

    public function users() {
        return $this->hasOne(Users::class, 'r_code', 'r_code');
    }

}