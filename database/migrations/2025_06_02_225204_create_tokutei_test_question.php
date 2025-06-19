<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokuteiTestQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokutei_test_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lms_content_id');
            $table->integer('question_order');
            $table->text('content');
            $table->text('point');
            $table->json('options');
            $table->integer('answer');
            $table->integer('section');
            $table->integer('category');
            $table->integer('tokutei_test_type');
            $table->text('image_url')->nullable();
            $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('create_tokutei_test_question');
    }
}
