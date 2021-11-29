<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityAndStateToSacProtocol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('sac_protocol') ) {
            if (!Schema::hasColumn('sac_protocol', 'city', 'state')) {
                Schema::table('sac_protocol', function (Blueprint $table) {
                    $table->string('city')->nullable();
                    $table->string('state')->nullable();
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
        // Schema::table('sac_protocol', function (Blueprint $table) {
        //     $table->dropColumn(['city',  'state']);
        // });
    }
}
