<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecruitmentTestResponse extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recruitment_test_response';

    public function recruitment_test_response_options() {
        return $this->hasMany(RecruitmentTestResponseOptions::class, 'recruitment_test_response_id', 'id');
    }

    public function recruitment_test() {
        return $this->belongsTo(RecruitmentTest::class, 'recruitment_test_id', 'id');
    }
}
