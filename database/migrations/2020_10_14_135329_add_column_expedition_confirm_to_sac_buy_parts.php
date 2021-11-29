<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnExpeditionConfirmToSacBuyParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sac_buy_parts', function (Blueprint $table) {
            if (!Schema::hasColumn('sac_buy_parts', 'expedition_confirm')) {
                Schema::table('sac_buy_parts', function (Blueprint $table) {
                    $table->integer('expedition_confirm')->default(0)->after('sac_expedition_request_id');
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
        //
    }
}
