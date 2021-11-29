<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesModuleFinancyAccountability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financy_accountability', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',191);
            $table->string('r_code',191);
            $table->float('total_lending',8,2)->default('0.00');
            $table->float('total_pending',8,2)->default('0.00');
            $table->float('total',8,2)->default('0.00');
            $table->float('total_liquid',8,2)->default('0.00');

            $table->integer('has_analyze')->default('0');
            $table->integer('is_approv')->default('0');
            $table->integer('is_reprov')->default('0');
            $table->integer('is_paid')->default('0');
            
            $table->integer('lending_request_id')->default('0');
            $table->integer('payment_request_id')->default('0');
            $table->text('description');
            $table->timestamps();
            
            $table->unique('code', 'unique_code');
            $table->index('r_code','r_code');
            $table->index('lending_request_id','lending_request_id');
            $table->index('payment_request_id','payment_request_id');
            
        });
        Schema::create('financy_accountability_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('financy_accountability_id');
            $table->integer('type_entry')->default('1');
            $table->integer('type')->default('1');
            $table->string('description',255)->nullable();
            $table->string('city',255)->nullable();
            $table->integer('peoples')->default('1');
            $table->integer('currency')->default('1');
            $table->string('quotation',255)->default('0.00');
            $table->float('total',8,2)->default('0.00');
            $table->float('old_total',8,2)->default('0.00');
            $table->date('date')->default('0000-00-00');

            $table->timestamps();
            $table->index('financy_accountability_id','financy_accountability_id');
        });
        Schema::create('financy_accountability_manual_entry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('financy_lending_id')->default('0');
            $table->string('code',255);
            $table->string('r_code',255);
            $table->integer('type_entry')->default('0');
            $table->integer('p_method')->default('0');
            $table->float('total',8,2)->default('0.00');
            $table->string('description',255);
            $table->date('date')->default('0000-00-00');
            $table->timestamps();
            $table->index('financy_lending_id','financy_lending_id');
        });
        Schema::create('financy_accountability_attach', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('financy_accountability_item_id')->nullable();
            $table->integer('financy_accountability_manual_entry_id')->nullable();

            $table->string('name',255);
            $table->string('size',255);
            $table->string('url',255);
            
            $table->timestamps();
            $table->index('financy_accountability_item_id','financy_accountability_item_id');
            $table->index('financy_accountability_manual_entry_id','financy_accountability_manual_entry_id');
            
        });

        Schema::create('financy_users_debtors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('r_code',191);
            $table->float('total_lendings',8,2)->default('0.00');
            $table->float('total_paid',8,2)->default('0.00');
            $table->float('total_analyze',8,2)->default('0.00');
            $table->float('balance_due',8,2)->default('0.00');

            $table->unique('r_code', 'unique_r_code');
            $table->timestamps();
        });

        Schema::create('financy_accountability_observation_history', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('financy_lending_id');
            $table->string('model_class_origin',255);
            $table->integer('model_id');

            $table->string('r_code',255);
            $table->text('description');
            $table->text('old_model_values');
            $table->text('new_model_values');
            
            $table->index('financy_lending_id','financy_lending_id');
            $table->timestamps();
        });
        Schema::create('financy_accountability_receiver_history', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('financy_accountability_id');
            $table->integer('lending_request_id');
            $table->integer('request_payment_id');
            
            $table->string('request_payment_file')->nullable();
            $table->float('total',8,2)->default('0.00');
            $table->integer('p_method')->default('0');
            $table->timestamp('date')->default('0000-00-00 00:00:00');
            
            $table->timestamps();
            $table->index('financy_accountability_id','financy_accountability_id');
            $table->index('lending_request_id','lending_request_id');
            $table->index('request_payment_id','request_payment_id');
        });
          
        if ( Schema::hasTable('financy_lending') ) {
            Schema::table('financy_lending', function($table) {
                $table->integer('is_accountability_paid')->default('0')->after('is_paid');
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
        
        Schema::dropIfExists('financy_accountability_observation_history');
        Schema::dropIfExists('financy_accountability_receiver_history');
        Schema::dropIfExists('financy_accountability_attach');
        Schema::dropIfExists('financy_accountability_manual_entry');
        Schema::dropIfExists('financy_accountability_item');
        Schema::dropIfExists('financy_accountability');

        Schema::dropIfExists('financy_users_debtors');
        
        Schema::table('financy_lending', function (Blueprint $table) {
            $table->dropColumn('is_accountability_paid');
        });
    }
}
