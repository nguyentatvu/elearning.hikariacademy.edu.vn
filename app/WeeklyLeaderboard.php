<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeeklyLeaderboard extends Model
{
    protected $table = "weekly_leaderboards";

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'rank', 'reward_point', 'week_start'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get user rank
     * @param int $userId
     * @param string $weekStart
     * @return object
     */
    public static function getUserRank(int $userId, string $weekStart)
    {
        $currentUser = self::with('user')
            ->where('week_start', $weekStart)
            ->where('user_id', $userId)
            ->first();

        return $currentUser;
    }
}
