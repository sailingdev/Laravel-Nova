<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTemplateCreatedToSubmittedKeywordsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!App::environment('testing')) {
            DB::statement("ALTER TABLE submitted_keywords MODIFY status enum('pending', 'processing', 'processed', 'template_created')  NULL DEFAULT 'pending';");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!App::environment('testing')) {
            DB::statement("ALTER TABLE submitted_keywords MODIFY status enum('pending', 'processing', 'processed') NULL  DEFAULT 'pending';");
        }
    }
}
