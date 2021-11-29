<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if ( !Schema::hasTable('ti_maintenance') ) {

            Schema::create('ti_maintenance', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('trackid')->unique();
                $table->integer('request_r_code');
                $table->integer('category_id');
                $table->integer('priority');
                $table->integer('printer_model');
                $table->integer('toner_model');
                $table->string('subject');
                $table->text('message');
                $table->integer('registred_r_code');
                $table->integer('status');
                $table->integer('ext_phone');
                $table->string('access_comp');
                $table->integer('sector');  
                $table->integer('unit');
                $table->timestamp('start_reserve')->default(null);
                $table->timestamp('final_reserve')->default(null);

                $table->timestamp('start_time_job')->default(null);
                $table->timestamp('pause_time_job')->default(null);
                $table->timestamp('final_time_job')->default(null);
                
                $table->integer('replies_id');
                $table->integer('notes_id');
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
        // Schema::dropIfExists('ti_maintenance');
    }
}
