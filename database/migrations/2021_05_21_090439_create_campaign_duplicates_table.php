<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignDuplicatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_duplicates', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->string('type_tag');
            $table->string('feed');
            $table->dateTime('campaign_start');
            $table->string('campaign_id');
            $table->enum('type', ['main', 'duplicate']);
            $table->enum('main_batch_status', ['uncompleted', 'completed'])->nullable();
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
        Schema::dropIfExists('campaign_duplicates');
    }
}
