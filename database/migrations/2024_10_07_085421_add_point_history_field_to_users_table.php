<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPointHistoryFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('point_history')->nullable();
        });

        DB::table('users')->update([
            'point_history' => json_encode([
                'total' => 0,
                'used' => 0,
                'exercise_test' => 0,
                'video' => 0,
                'streak' => 0,
                'recharge' => 0
            ])
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->json('point_history')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('point_history');
        });
    }
}
