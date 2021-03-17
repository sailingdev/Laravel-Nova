<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_texts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('market_id')->unsigned();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');
            $table->string('title1');
            $table->string('title2');
            $table->text('body1');
            $table->text('body2');
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
        Schema::dropIfExists('ad_texts');
    }
}
