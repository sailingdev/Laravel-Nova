<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->bigInteger('ad_account_id')->unsigned();
            $table->foreign('ad_account_id')->references('id')->on('ad_accounts')->onDelete('cascade');
            $table->string('site_tag');
            $table->text('supported_markets');
            $table->string('feed');
            $table->string('source_tag');
            $table->string('range_id');
            $table->string('ad_unit_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('websites');
    }
}
