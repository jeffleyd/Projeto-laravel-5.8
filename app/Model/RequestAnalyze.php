<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RequestAnalyze extends Model
{
    protected $table = 'request_analyze';
    protected $hidden = [
        'analyze_id',
        'analyze_type',
    ];
    protected $fillable = [
        'r_code',
        'description',
        'is_approv',
        'is_reprov',
        'position',
    ];

    public function analyze() {
        return $this->morphTo();
    }

    public function users() {
        return $this->hasOne(Users::class, 'r_code', 'r_code');
    }

}
