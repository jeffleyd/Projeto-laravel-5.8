<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        
        if ( !Schema::hasTable('permissions') ) {
            Schema::create('permissions', function (Blueprint $table) {

                $table->charset = 'latin1';
                $table->collation = 'latin1_swedish_ci';
    
                $table->bigIncrements('id');
                $table->string('description');
                $table->text('token');
                
    
                $table->timestamp('created_at')->default('0000-00-00 00:00:00');
                $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
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
        // Schema::dropIfExists('permissions');
    }
}
