<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_counters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key',191);
            $table->string('prefix',255);
            $table->integer('value');
            $table->unique('key', 'unique_key');
            $table->timestamps();
        });
        $this->load();
    }

    protected function load()
    {
        DB::table('module_counters')->insert([
            ['key' => 'lending', 'prefix' => 'EM', 'value' => '10001'],
            ['key' => 'refund', 'prefix' => 'RE', 'value' => '10001'],
            ['key' => 'payment', 'prefix' => 'PA', 'value' => '10001'],
            ['key' => 'accountability', 'prefix' => 'PC', 'value' => '10001'],
            ['key' => 'accountability_manual', 'prefix' => 'PCM', 'value' => '10001'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_counters');
    }
}
