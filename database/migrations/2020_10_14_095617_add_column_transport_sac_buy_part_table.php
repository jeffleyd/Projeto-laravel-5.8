<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTransportSacBuyPartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('sac_buy_part') ) {
            if (!Schema::hasColumn('sac_buy_part', 'transport')) {
                Schema::table('sac_buy_part', function (Blueprint $table) {
                    $table->string('transport')->nullable(true)->after('track_code');
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
        //
    }
}
