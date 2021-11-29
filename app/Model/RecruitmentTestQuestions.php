<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecruitmentTestQuestions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recruitment_test_questions';

    public function recruitment_test_questions_answer() {

        return $this->hasMany(RecruitmentTestQuestionsAnswer::class, 'recruitment_test_questions_id', 'id');
    }
}
