<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKanjiWritingPracticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanji_writing_practices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('practice_id');
            $table->integer('number');
            $table->string('full_word');
            $table->string('underlined_word');
            $table->string('kanji');
            $table->timestamps();

            $table->foreign('practice_id')
                ->references('id')
                ->on('japanese_writing_practices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kanji_writing_practices');
    }
}
