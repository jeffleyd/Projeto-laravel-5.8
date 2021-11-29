<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsExpeditionToSacExpeditionRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sac_expedition_request', function (Blueprint $table) {
            if (!Schema::hasColumn('sac_expedition_request', 'is_expedition')) {
                Schema::table('sac_expedition_request', function (Blueprint $table) {
                    $table->integer('is_expedition')->default(1)->after('is_completed')->comment('1 - credenciada, 2 - Ordem de compra');
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
