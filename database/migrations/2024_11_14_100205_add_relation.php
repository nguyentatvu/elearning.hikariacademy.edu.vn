<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pronunciation_details', function (Blueprint $table) {
            $table->foreign('pronunciation_id')
                ->references('id')
                ->on('pronunciations')
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
        Schema::table('pronunciation_details', function (Blueprint $table) {
            $table->dropForeign(['pronunciation_id']);
        });
    }
}
