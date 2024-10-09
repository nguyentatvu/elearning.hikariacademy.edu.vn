<?php

namespace App\Http\Controllers;

use App\ExamSeries;
use App\PaymentMethod;
use App\Services\CoinRechargePackageService;
use App\Services\LmsSeriesComboService;
use App\Services\PaymentMethodService;
use App\Services\WeeklyLeaderboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * This method lists all the available mock exam for students
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mockExamList() {
        // INITILIZE BASIC DATA STRUCTURE
        $user = Auth::user();
        $data = [
            'user' => $user,
            'series_cd' => [],
            'exam_check' => null,
            'series_n1' => [],
            'series_n2' => [],
            'series_n3' => [],
            'series_n4' => [],
            'series_n5' => []
        ];

        // LOAD CLASS SPECIFIC EXAMS
        $classesUser = DB::table('classes_user')
        ->where('student_id', $user->id)
            ->latest('id')
            ->first();

        if ($classesUser) {
            $now = Carbon::now();

            $classesExam = ExamSeries::join('classes_exam', 'classes_exam.exam_id', '=', 'examseries.id')
            ->where('classes_exam.classes_id', $classesUser->classes_id)
                ->where('classes_exam.start_date', '<=', $now)
                ->where('classes_exam.end_date', '>=', $now)
                ->get();

            if ($classesExam->isNotEmpty()) {
                $data['series_cd'] = $classesExam;
            }
        }

        // LOAD FREE EXAM DATA
        $now = Carbon::now();

        $examFree = DB::table('exam_free')
        ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();

        $data['exam_time'] = DB::table('exam_free')
        ->select('name', 'start_date', 'end_date')
        ->latest()
            ->first();

        if ($examFree) {
            $data['exam_check'] = 'exam';

            $examIds = [
                1 => $examFree->exam1_1,
                2 => $examFree->exam2_1,
                3 => $examFree->exam3_1,
                4 => $examFree->exam4_1,
                5 => $examFree->exam5_1
            ];

            $allExams = ExamSeries::whereIn('id', array_values($examIds))
                ->get()
                ->groupBy('id');

            foreach ($examIds as $level => $examId) {
                $data["series_n{$level}"] = $allExams->get($examId, collect());
            }
        }

        // HANDLE TEACHER ROLE SPECIFIC LOGIC
        define('CATEGORY_IDS', [1, 2, 3, 4, 5]);
        if ($user->role_id === 6 && checkRole(getUserGrade(6))) {
            $allExams = ExamSeries::whereIn('category_id', CATEGORY_IDS)
                ->get()
                ->groupBy('category_id');

            foreach (CATEGORY_IDS as $categoryId) {
                $data["series_n{$categoryId}"] = $allExams->get($categoryId, collect());
            }

            $data['exam_check'] = 'role_test';
        }

        return view('client.mypage.mock-exam.available-list', $data);
    }


    /**
     * Show mock exam detail
     *
     * @param  string $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mockExamDetail(string $slug) {
        $record = ExamSeries::getRecordWithSlug($slug);
        $examseries_id = $record->id;

        $quizresultfinish_data = DB::table('quizresultfinish')
            ->where('examseri_id', '=', $examseries_id)
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($quizresultfinish_data && $quizresultfinish_data->finish < 3) {
            $finish_current = $quizresultfinish_data->finish + 1;
        }
        else {
            $finish_current = 1;
        }

        $data['finish_current'] = $finish_current;
        $data['active_class']       = 'examslist';
        $data['pay_by']             = '';
        $data['content_record']     = FALSE;
        $data['title']              = change_furigana_admin($record->title);
        $data['item']               = $record;
        $data['right_bar']         = TRUE;
        $data['right_bar_path']   = 'student.exams.exam-series-item-view-right-bar';
        $data['right_bar_data']     = ['item' => $record];

        return view('client.mypage.mock-exam.overview-detail', $data);
    }
}
