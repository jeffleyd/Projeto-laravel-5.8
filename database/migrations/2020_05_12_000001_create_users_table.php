<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if ( !Schema::hasTable('users') ) {
            Schema::create('users', function (Blueprint $table) {
                $table->charset = 'latin1';
                $table->collation = 'latin1_swedish_ci';

                $table->bigIncrements('id');
                $table->text('token')->nullable();
                $table->text('token_mobile')->nullable();
                $table->string('r_code');
                $table->string('office')->nullable();
                
                $table->integer('sector_id')->default('1');
                $table->integer('sector_id_2')->default('0');
                $table->integer('sector_id_3')->default('0');


                
                $table->timestamp('birthday')->default('0000-00-00 00:00:00');
                $table->string('phone')->nullable();
                $table->integer('gree_id')->default('2');

                $table->string('first_name');
                $table->string('last_name');
                $table->string('picture');
                $table->string('password');
                $table->string('email');

                $table->integer('is_active')->default('0');
                $table->integer('status')->default('0');
                $table->integer('retry')->default('3');
                $table->integer('version')->default('1');

                $table->timestamp('retry_time')->default('0000-00-00 00:00:00');
                $table->string('lang')->default('pt-br');

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
        // Schema::dropIfExists('users');
    }
}
