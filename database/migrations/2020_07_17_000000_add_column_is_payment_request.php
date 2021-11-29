<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsPaymentRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('sac_os_protocol') ) {
            Schema::table('sac_os_protocol', function($table) {
                $table->integer('is_payment_request')->default('1')->after('is_paid');
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
        // Schema::table('sac_os_protocol', function (Blueprint $table) {
        //     $table->dropColumn('is_payment_request');
        // });
    }
}