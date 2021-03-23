<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('account_id');
            $table->string('timezone');
            $table->text('notes')->nullable();
            $table->text('configurations');
            $table->timestamps();
        });select * from ad_accounts;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_accounts');
    }
}
