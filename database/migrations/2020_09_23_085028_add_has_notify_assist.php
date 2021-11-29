<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasNotifyAssist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('sac_protocol') ) {
            if (!Schema::hasColumn('sac_protocol', 'has_notify_assist')) {
                Schema::table('sac_protocol', function (Blueprint $table) {
                    $table->integer('has_notify_assist')->default(0)->after('is_warranty');
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
        //     $table->dropColumn(['has_notify_assist']);
        // });
    }
}
