<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePronunciationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pronunciation_details', function (Blueprint $table) {
            $table->string('katakana_text')->nullable();
            $table->json('words')->nullable();
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
            $table->dropColumn('katakana_text');
            $table->dropColumn('words');
        });
    }
}
