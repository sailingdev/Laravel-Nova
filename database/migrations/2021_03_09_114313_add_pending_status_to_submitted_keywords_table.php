<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPendingStatusToSubmittedKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submitted_keywords', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE submitted_keywords CHANGE status status ENUM('pending','processing', 'processed') DEFAULT 'pending' NULL
            ");
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
            //
        });
    }
}
