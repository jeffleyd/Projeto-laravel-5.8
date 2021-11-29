<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'survey_questions';

    protected $casts = [
        'json_answer' => 'array',
    ];
}
