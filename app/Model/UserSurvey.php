<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserSurvey extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_survey';

    public function users() {
        return $this->hasOne(Users::class, 'r_code', 'user_r_code');
    }

    public function survey() {
        return $this->hasOne(Survey::class, 'id', 'survey_id');
    }

    public function userSurveyAnswer() {
        return $this->hasMany(UserSurveyAnswer::class, 'user_answer_id', 'id');
    }

}
