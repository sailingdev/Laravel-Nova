<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbPagePostSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_page_post_schedulers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fb_page_post_id')->unsigned();
            $table->foreign('fb_page_post_id')->references('id')->on('fb_page_posts')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->text('page_groups');
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
        Schema::dropIfExists('fb_page_post_schedulers');
    }
}
