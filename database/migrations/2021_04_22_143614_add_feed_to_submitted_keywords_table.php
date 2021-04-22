<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeedToSubmittedKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submitted_keywords', function (Blueprint $table) {
            $table->string('feed')->nullable()->after('keyword');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submitted_keywords', function (Blueprint $table) {
            $table->dropColumn(['feed']);
        });
    }
}
