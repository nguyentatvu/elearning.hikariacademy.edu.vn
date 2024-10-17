<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoadmapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roadmaps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('duration_months');
            $table->integer('lmsseries_id');
            $table->json('contents');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('lmsseries_id')
                ->references('id')
                ->on('lmsseries')
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
        Schema::dropIfExists('roadmap');
    }
}
