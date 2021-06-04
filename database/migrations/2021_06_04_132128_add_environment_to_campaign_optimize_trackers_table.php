<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnvironmentToCampaignOptimizeTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_optimize_trackers', function (Blueprint $table) {
            $table->string('environment')->after('feed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_optimize_trackers', function (Blueprint $table) {
            $table->dropColumn(['environment']);
        });
    }
}
