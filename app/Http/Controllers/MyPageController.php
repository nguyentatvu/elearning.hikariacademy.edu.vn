<?php

namespace App\Http\Controllers;

use App\PaymentMethod;
use App\Services\CoinRechargePackageService;
use App\Services\PaymentMethodService;
use App\Services\WeeklyLeaderboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    private $weeklyLearboardService;
    private $paymentMethodService;
    private $coinRechargeService;

    public function __construct(
        WeeklyLeaderboardService $weeklyLearboardService,
        PaymentMethodService $paymentMethodService,
        CoinRechargePackageService $coinRechargeService
    )
    {
        $this->weeklyLearboardService = $weeklyLearboardService;
        $this->paymentMethodService = $paymentMethodService;
        $this->coinRechargeService = $coinRechargeService;
        $this->middleware('auth');
    }

    /**
     * Show the leaderboard of rewarded point
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function leaderboard(Request $request) {
        $leaderboard = $this->weeklyLearboardService->getTopRankings();
        $userRank = $this->weeklyLearboardService->getUserRank(Auth::id());

        return view('client.mypage.leaderboard', [
            'leaderboard' => $leaderboard,
            'userRank' => $userRank
        ]);
    }

    /**
     * Show recharge point package
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rechargePoint() {
        $this->paymentMethodService->getAllOverdueCoinPayment()->each(function ($payment) {
            $payment->update(['status' => PaymentMethod::PAYMENT_FAILED]);
        });


        $data['active_coin_packages'] = $this->coinRechargeService->getAllActivePackages();
        return view('client.mypage.recharge-point', $data);
    }
}
