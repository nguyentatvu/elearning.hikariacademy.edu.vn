<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLmscontentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lmscontents', function (Blueprint $table) {
            $table->unsignedBigInteger('japanese_writing_practice_id')->nullable();

            $table->foreign('japanese_writing_practice_id')
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
        Schema::table('lmscontents', function (Blueprint $table) {
            $table->dropColumn('japanese_writing_practice_id');
        });
    }
}
