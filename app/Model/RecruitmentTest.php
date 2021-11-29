<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecruitmentTest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recruitment_test';

	public function Users()
    {
        return $this->belongsTo(Users::class, 'owner_r_code', 'r_code');
    }
	
    public function recruitment_test_questions() {
        return $this->hasMany(RecruitmentTestQuestions::class, 'recruitment_test_id', 'id');
    }

    public function recruitment_test_candidates() {
        return $this->belongsTo(RecruitmentTestCandidates::class, 'id', 'recruitment_test_id');
    }

    public function recruitment_test_candidates_all() {
        return $this->hasMany(RecruitmentTestCandidates::class, 'recruitment_test_id', 'id');
    }
}
