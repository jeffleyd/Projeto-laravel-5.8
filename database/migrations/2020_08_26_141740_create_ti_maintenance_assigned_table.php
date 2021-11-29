<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiMaintenanceAssignedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('ti_maintenance_assigned') ) {
            Schema::create('ti_maintenance_assigned', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('maintenance_id');
                $table->integer('r_code');
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
        //Schema::dropIfExists('ti_maintenance_assigned');
    }
}
