<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Restore redeemed points
     *
     * @param string $user_id
     * @return void
     */
    public function restoreRedeemedPoints(?string $user_id = null) {
        $user = is_null($user_id) ? Auth::user() : $this->model->find($user_id);
        if ($user->redeemed_points) {
            $user->update([
                'reward_point' => $user->redeemed_points['reward_point'] + $user->reward_point,
                'recharge_point' => $user->redeemed_points['recharge_point'] + $user->recharge_point,
                'redeemed_points' => null,
                'series_order_created_at' => null
            ]);
        }
    }

    /**
     * Update Point History
     *
     * @param array $data
     * @param string $userId
     * @return void
     */
    public function updatePointHistory($data, string $userId) {
        $user = $userId ? $this->findById((int) $userId) : Auth::user();
        $pointHistory = $user->point_history;

        foreach($data as $key => $value) {
            if (array_key_exists($key, $pointHistory) && !in_array($key, ['total', 'used'])) {
                $pointHistory[$key] += $value;
                $pointHistory['total'] += $value;
            } else if ($key == 'used') {
                $pointHistory['used'] += $value;
            }
        }

        $user->update(['point_history' => $pointHistory]);
    }
}
