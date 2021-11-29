<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCountNotifyTableLendings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('financy_lending') ) {

            if (!Schema::hasColumn('financy_lending', 'count_notify')) {
                Schema::table('financy_lending', function (Blueprint $table) {
                    $table->integer('count_notify')->nullable()->default('0')->after('payment_request_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('financy_lending', function (Blueprint $table){
        //     if (Schema::hasColumn('financy_lending', 'count_notify')) {
        //         $table->dropColumn('count_notify');
        //     }
        // });
    }
}
