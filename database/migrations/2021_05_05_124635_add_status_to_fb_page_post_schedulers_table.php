<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFbPagePostSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fb_page_post_schedulers', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processed', 'failed'])->nullable()->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fb_page_post_schedulers', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}
