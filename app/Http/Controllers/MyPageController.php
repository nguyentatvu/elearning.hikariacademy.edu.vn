<?php

namespace App\Http\Controllers;

use App\PaymentMethod;
use App\Services\CoinRechargePackageService;
use App\Services\LmsSeriesComboService;
use App\Services\PaymentMethodService;
use App\Services\WeeklyLeaderboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    private $weeklyLearboardService;
    private $paymentMethodService;
    private $coinRechargeService;
    private $lmsSeriesComboService;

    public function __construct(
        WeeklyLeaderboardService $weeklyLearboardService,
        PaymentMethodService $paymentMethodService,
        CoinRechargePackageService $coinRechargeService,
        LmsSeriesComboService $lmsSeriesComboService
    )
    {
        $this->weeklyLearboardService = $weeklyLearboardService;
        $this->paymentMethodService = $paymentMethodService;
        $this->coinRechargeService = $coinRechargeService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
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

    /**
     * Show recharge point package
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rewardPoint() {
        $data['point_history'] = Auth::user()->point_history;
        $data['redeemed_series'] = $this->lmsSeriesComboService->getRedeemedSeries();
        $data['series_upload_path'] = config('constant.series.upload_path');

        return view('client.mypage.reward-point', $data);
    }
}
