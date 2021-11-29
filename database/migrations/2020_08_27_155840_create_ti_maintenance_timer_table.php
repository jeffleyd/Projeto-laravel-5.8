<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiMaintenanceTimerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('ti_maintenance_timer') ) {
            Schema::create('ti_maintenance_timer', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('maintenance_id');
                $table->integer('r_code');
                $table->timestamp('date_time')->default(null);
                $table->integer('status');
                $table->timestamps();
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
        //Schema::dropIfExists('ti_maintenance_timer');
    }
}
