<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSacExpeditionRequestIdToSacBuyParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sac_buy_parts', function (Blueprint $table) {
            if (!Schema::hasColumn('sac_buy_parts', 'sac_expedition_request_id')) {
                Schema::table('sac_buy_parts', function (Blueprint $table) {
                    $table->integer('sac_expedition_request_id')->default(0)->after('not_part');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sac_buy_parts', function (Blueprint $table) {
            //
        });
    }
}
