<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePointRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->json('rules');
            $table->timestamps();
        });

        DB::table('point_rules')->insert([
            'rules' => json_encode([
                'registration' => [
                    'points' => 100
                ],
                'daily_login' => [
                    'milestones' => [
                        ['days' => 3, 'points' => 5],
                        ['days' => 8, 'points' => 10],
                        ['days' => 15, 'points' => 15],
                    ]
                ],
                'learning' => [
                    'video' => [
                        'completion_points' => 5
                    ],
                    'exercise' => [
                        'thresholds' => [
                            ['percentage' => 65, 'points' => 5],
                            ['percentage' => 80, 'points' => 10],
                            ['percentage' => 100, 'points' => 15]
                        ]
                    ],
                    'test' => [
                        'thresholds' => [
                            ['percentage' => 65, 'points' => 5],
                            ['percentage' => 80, 'points' => 10],
                            ['percentage' => 100, 'points' => 15]
                        ]
                    ]
                ]
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_rules');
    }
}
