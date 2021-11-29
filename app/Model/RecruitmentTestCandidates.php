<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecruitmentTestCandidates extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recruitment_test_candidates';

    public function recruitment_test() {
        return $this->belongsTo(RecruitmentTest::class, 'recruitment_test_id', 'id');
    }
}
