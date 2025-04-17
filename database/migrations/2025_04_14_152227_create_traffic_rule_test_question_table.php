<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrafficRuleTestQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traffic_rule_test_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lms_content_id')->nullable();
            $table->integer('parent_question_id')->nullable();
            $table->integer('question_order')->nullable();
            $table->text('content');
            $table->text('point');
            $table->string('option_1', 255)->nullable();
            $table->string('option_2', 255)->nullable();
            $table->integer('answer')->nullable();
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
        Schema::dropIfExists('traffic_rule_test_question');
    }
}
