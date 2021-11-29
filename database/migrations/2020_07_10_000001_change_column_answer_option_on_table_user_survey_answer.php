<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnAnswerOptionOnTableUserSurveyAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('user_survey_answer') ) {
            DB::statement('ALTER TABLE user_survey_answer MODIFY answer_option TEXT;');
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement('ALTER TABLE user_survey_answer MODIFY answer_option varchar(255);');
    }
}