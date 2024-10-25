<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLmscontentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lmscontents', function (Blueprint $table) {
            $table->unsignedInteger('pronunciation_id')->nullable();

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
        Schema::table('lmscontents', function (Blueprint $table) {
            $table->dropForeign(['pronunciation_id']);
            $table->dropColumn('pronunciation_id');
        });
    }
}
