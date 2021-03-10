<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittedKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_keywords', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_id');
            $table->string('keyword');
            $table->string('market');
            $table->enum('status', ['pending', 'processing', 'processed'])->default('pending');
            $table->enum('action_taken', ['skipped', 'new'])->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('submitted_keywords');
    }
}
