<?php

namespace App\Model\Services\Analyze\Model;

use App\Model\Users;
use Illuminate\Database\Eloquent\Model;

class RequestAnalyzeApprovers extends Model
{
    protected $table = 'request_analyze_approvers';
    protected $hidden = [
        'analyze_id',
    ];
    protected $fillable = [
        'r_code',
        'position',
        'analyze_type',
        'mark'
    ];

    public function analyze() {
        return $this->morphTo();
    }

    public function users() {
        return $this->hasOne(Users::class, 'r_code', 'r_code');
    }

}