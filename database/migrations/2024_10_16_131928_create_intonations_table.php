<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intonations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pronunciation_detail_id');
            $table->string('word');
            $table->float('start');
            $table->float('end');
            $table->float('average');
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
        Schema::dropIfExists('intonations');
    }
}
