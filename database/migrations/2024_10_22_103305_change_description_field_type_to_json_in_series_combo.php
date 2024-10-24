<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDescriptionFieldTypeToJsonInSeriesCombo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lmsseries_combo', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('lmsseries_combo', function (Blueprint $table) {
            $table->json('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lmsseries_combo', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('lmsseries_combo', function (Blueprint $table) {
            $table->text('description')->nullable();
        });
    }
}
