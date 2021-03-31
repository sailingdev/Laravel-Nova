<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name');
            $table->string('page_id');
            $table->string('instagram_id')->nullable();
            $table->integer('running_ads')->nullable()->default(0);
            $table->string('environment');
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
        Schema::dropIfExists('fb_pages');
    }
}
