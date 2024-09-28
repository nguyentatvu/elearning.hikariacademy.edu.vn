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
}
