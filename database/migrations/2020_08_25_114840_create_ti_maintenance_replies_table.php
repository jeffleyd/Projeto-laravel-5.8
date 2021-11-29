<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiMaintenanceRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('ti_maintenance_replies') ) {
            Schema::create('ti_maintenance_replies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('maintenance_id');
                $table->integer('r_code_reply');
                $table->text('message');
                $table->string('attach');
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
        //Schema::dropIfExists('ti_maintenance_replies');
    }
}
