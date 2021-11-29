<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('sac_model_protocol') ) {
            if (!Schema::hasColumn('sac_model_protocol', 'price')) {
                Schema::table('sac_model_protocol', function (Blueprint $table) {
                    $table->double('price', 8,2)->default(0.00)->after('serial_number');
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
