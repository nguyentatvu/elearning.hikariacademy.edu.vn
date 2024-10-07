<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHiraganaWritingPracticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiragana_writing_practices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('practice_id');
            $table->integer('number');
            $table->string('character');
            $table->timestamps();

            $table->foreign('practice_id')
                ->references('id')
                ->on('japanese_writing_practices')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hiragana_writing_practices');
    }
}
