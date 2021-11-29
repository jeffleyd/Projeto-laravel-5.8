<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSacMsgOsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('sac_msg_os') ) {
            Schema::create('sac_msg_os', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sac_os_protocol_id');
                $table->string('r_code')->nullable(true);
                $table->integer('authorized_id')->default(0);
                $table->integer('is_system')->default(0);
                $table->integer('message_visible')->default(0);
                $table->text('message')->nullable(true);
                $table->string('message_audio')->nullable(true);
                $table->string('file')->nullable(true);
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
        //Schema::dropIfExists('sac_msg_os');
    }
}
