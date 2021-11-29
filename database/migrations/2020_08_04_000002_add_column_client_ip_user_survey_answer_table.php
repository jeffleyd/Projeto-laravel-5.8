<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnClientIpUserSurveyAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('user_survey_answer') ) {
            Schema::table('user_survey_answer', function($table) {
                $table->string('client_ip')->nullable()->after('user_r_code');
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
        // Schema::table('user_survey_answer', function (Blueprint $table) {
        //     $table->dropColumn('client_ip');
        // });
    }
}