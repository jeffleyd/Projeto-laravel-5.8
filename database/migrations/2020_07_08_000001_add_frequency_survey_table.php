<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFrequencySurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('survey') ) {
            Schema::table('survey', function($table) {
                $table->integer('is_notify')->default('0')->after('description');
                $table->integer('survey_init')->default('0')->after('is_notify');
                $table->integer('survey_frequency')->default('0')->after('survey_init');
                $table->time('frequency_time')->nullable()->after('survey_frequency');
                $table->text('frequency_week')->nullable()->after('frequency_time');
                $table->text('frequency_month')->nullable()->after('frequency_week');

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
        // Schema::table('survey', function (Blueprint $table) {
        //     $table->dropColumn('survey_init');
        //     $table->dropColumn('survey_frequency');
        //     $table->dropColumn('frequency_time');
        //     $table->dropColumn('frequency_week');
        //     $table->dropColumn('frequency_month');
        // });
    }
}