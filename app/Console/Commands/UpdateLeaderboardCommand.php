<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use App\WeeklyLeaderboard;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateLeaderboardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaderboard:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update weekly leaderboard at 00:00 on Monday';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $weekStart = Carbon::now()->startOfWeek();

        $users = User::orderByDesc('reward_point')
            ->where('role_id', Role::STUDENT)
            ->where('reward_point', '!=', 0)
            ->get();

        $data = [];

        foreach ($users as $index => $user) {
            $data[] = [
                'user_id' => $user->id,
                'rank' => $index + 1,
                'reward_point' => $user->reward_point,
                'week_start' => $weekStart,
            ];
        }

        try {
            DB::beginTransaction();

            WeeklyLeaderboard::truncate();
            WeeklyLeaderboard::insert($data);

            $this->info('Leaderboard has been updated at ' . Carbon::now()->toDateTimeString());
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }
}
