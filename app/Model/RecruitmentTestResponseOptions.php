<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecruitmentTestResponseOptions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recruitment_test_response_options';

    public function recruitment_test_questions() {
        return $this->belongsTo(RecruitmentTestQuestions::class, 'recruitment_test_questions_id', 'id');
    }

    public function recruitment_test_questions_answer() {
        return $this->belongsTo(RecruitmentTestQuestionsAnswer::class, 'answer_option_id', 'id');
    }

}
