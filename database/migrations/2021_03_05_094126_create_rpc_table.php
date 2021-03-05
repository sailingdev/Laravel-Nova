<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRpcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpc', function (Blueprint $table) {
            $table->id();
            $table->datetime('date')->nullable();
            $table->string('keyword')->nullable();
            $table->string('rpc', 45)->nullable();
            $table->string('market')->nullable();
            $table->string('feed', 45)->nullable();
            $table->integer('tot_clicks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpc');
    }
}
