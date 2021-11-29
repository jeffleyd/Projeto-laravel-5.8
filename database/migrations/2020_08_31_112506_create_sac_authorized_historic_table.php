<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSacAuthorizedHistoricTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('sac_authorized_historic') ) {
            Schema::create('sac_authorized_historic', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('authorized_id');
                $table->integer('r_code');
                $table->integer('priority');
                $table->text('description');
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
        //Schema::dropIfExists('sac_authorized_historic');
    }
}
