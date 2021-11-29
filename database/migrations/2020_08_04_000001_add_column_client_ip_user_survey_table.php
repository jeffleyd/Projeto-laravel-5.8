<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnClientIpUserSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('user_survey') ) {
            Schema::table('user_survey', function($table) {
                $table->string('client_ip')->nullable()->after('survey_id');
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
        // Schema::table('user_survey', function (Blueprint $table) {
        //     $table->dropColumn('client_ip');
        // });
    }
}