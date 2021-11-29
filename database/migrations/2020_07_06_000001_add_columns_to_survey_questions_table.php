<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSurveyQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('survey_questions') ) {
            Schema::table('survey_questions', function($table) {
                
                $table->text('answer_type')->nullable()->after('is_notify');
                $table->text('json_answer')->nullable()->after('answer_type');
                $table->integer('is_required')->default('1')->after('json_answer');
                $table->integer('show_obs')->default('1')->after('is_required');

            });
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('survey_questions', function (Blueprint $table) {
        //     $table->dropColumn('answer_type');
        //     $table->dropColumn('json_answer');
        //     $table->dropColumn('is_required');
        //     $table->dropColumn('show_obs');

        // });
    }
}