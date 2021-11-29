<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeLineSacTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('sac_type') ) {
            if (!Schema::hasColumn('sac_type', 'type_line')) {
                Schema::table('sac_type', function($table) {
                    $table->integer('type_line')->default('1')->after('name');
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
        // Schema::table('sac_type', function (Blueprint $table) {
        //     $table->dropColumn('type_line');
        // });
    }
}