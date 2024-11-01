<?php

namespace App\Http\Controllers;

use App\ExamSeries;
use App\LmsSeriesCombo;
use App\PaymentMethod;
use App\Services\CoinRechargePackageService;
use App\Services\CommentService;
use App\Services\LmsSeriesComboService;
use App\Services\LmsSeriesService;
use App\Services\PaymentMethodService;
use App\Services\QuizResultFinishService;
use App\Services\UserRoadmapService;
use App\Services\UserService;
use App\Services\WeeklyLeaderboardService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MyPageController extends Controller
{
    private $weeklyLearboardService;
    private $paymentMethodService;
    private $coinRechargeService;
    private $lmsSeriesComboService;
    private $userService;
    private $lmsSeriesService;
    private $commentService;
    private $quizResultFinishService;
    private $userRoadmapService;


    public function __construct(
        WeeklyLeaderboardService $weeklyLearboardService,
        PaymentMethodService $paymentMethodService,
        CoinRechargePackageService $coinRechargeService,
        LmsSeriesComboService $lmsSeriesComboService,
        UserService $userService,
        LmsSeriesService $lmsSeriesService,
        CommentService $commentService,
        QuizResultFinishService $quizResultFinishService,
        UserRoadmapService $userRoadmapService
    )
    {
        $this->weeklyLearboardService = $weeklyLearboardService;
        $this->paymentMethodService = $paymentMethodService;
        $this->coinRechargeService = $coinRechargeService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
        $this->userService = $userService;
        $this->lmsSeriesService = $lmsSeriesService;
        $this->commentService = $commentService;
        $this->quizResultFinishService = $quizResultFinishService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
        $this->userRoadmapService = $userRoadmapService;

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
        $data['redeemed_series_combo'] = $this->lmsSeriesComboService->getRedeemedSeries();
        $data['series_combo_image_url'] = config('constant.series_combo.image_url');

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

    /**
     * Show personal info
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function showPersonal() {
        $randomSeriesType = Arr::random([LmsSeriesCombo::LEARNING_TYPE, LmsSeriesCombo::EXAM_TYPE]);
        $data['other_combo_series'] = $this->lmsSeriesComboService->getAllSeriesByType($randomSeriesType);
        $data['view_series_history'] = $this->lmsSeriesService
            ->getHistoryViews(Auth::user()->series_views_history ?? [], Auth::user());
        $data['roadmap_chosen_list'] = $this->userRoadmapService->userChosenRoadmapList(Auth::id() ?? -1);
        return view('client.mypage.personal', $data);
    }

    /**
     * Show my comments
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function showMyComments() {
        $comments = $this->commentService->getStudentComments(Auth::user()->id);

        return view('client.mypage.my-comments', compact('comments'));
    }

    /**
     * Show my exam result
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function showMyExamResult() {

        $userId = Auth::user()->id;
        $data['user'] = Auth::user();

        // Get data result exam
        $data['results'] = $this->quizResultFinishService->getStudentResultExam($userId);

        if ($data['results'] != null) {
            foreach ($data['results'] as $record) {
                if ($record->category_id <= 3) {
                    $style1 = ($this->checkKijunTen($record->category_id, 1, $record->quiz_1_total)) ? "info" : "danger";
                    $style2 = ($this->checkKijunTen($record->category_id, 2, $record->quiz_2_total)) ? "info" : "danger";
                    $style3 = ($this->checkKijunTen($record->category_id, 3, $record->quiz_3_total)) ? "info" : "danger";
                    $detail = '言語知識（文字・語彙・文法）: <span class="badge bg-' . $style1 . '">' . $record->quiz_1_total . '</span><br><br>読解: <span class="badge bg-' . $style2 . '">' . $record->quiz_2_total . '</span><br><br>聴解: <span class="badge bg-' . $style3 . '">' . $record->quiz_3_total . '</span>';
                } else {
                    $style1 = ($this->checkKijunTen($record->category_id, 1, $record->quiz_1_total)) ? "info" : "danger";
                    $style3 = ($this->checkKijunTen($record->category_id, 2, $record->quiz_3_total)) ? "info" : "danger";
                    $detail = '言語知識（文字・語彙・文法）: <span class="badge bg-' . $style1 . '">' . $record->quiz_1_total . '</span><br><br>聴解: <span class="badge bg-' . $style3 . '">' . $record->quiz_3_total . '</span>';
                }
                $record->detail = $detail;

                if ($record->finish == 3) {
                    if ($this->checkPassingscore($record->category_id, $record->total_marks) && $this->checkKijunTenAnyKubun($record->category_id, $record->quiz_1_total, $record->quiz_2_total, $record->quiz_3_total)) {
                        $ketqua = '<span class="badge bg-success">Đạt</span>';
                    } else {
                        $ketqua = '<span class="badge bg-warning">Chưa đạt</span>';
                    }

                } else {
                    $ketqua = '<span class="badge bg-danger">Chưa hoàn thành</span>';
                }
                $record->ketqua = $ketqua;
            }
        }

        return view('client.mypage.my-exam-result', $data);
    }

    /**
     * Show payment management
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPaymentManagement() {
        $data['payment_list'] = $this->paymentMethodService->getAllMyPayments(Auth::user()->id);

        return view('client.mypage.payment-management', $data);
    }

    /**
     * Update user info
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserInfo(Request $request) {
        $filteredData = array_filter($request->all(), function ($value) {
            return $value !== null && $value !== '';
        });

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\d{10}$/', Rule::unique('users', 'phone')->ignore(Auth::user()->id)],
            'old_password' => ['required_with:password', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Mật khẩu cũ sai!');
                }
            }],
            'password' => ['required_with:old_password', 'different:old_password', 'string', 'min:6', 'confirmed'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        $messages = [
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'old_password.required_with' => 'Nhập thiếu mật khẩu cũ!',
            'password.required_with' => 'Nhập thiếu mật khẩu mới!',
            'password.different' => 'Không được nhập mật khẩu mới trùng với mật khẩu cũ!',
            'password.min' => 'Mật khẩu mới quá ngắn, tối thiểu 6 kí tự!',
            'password.confirmed' => 'Mật khẩu mới xác nhận không trùng!',
            'avatar.mimes' => 'Vui lòng tải ảnh thuộc những định dạng: jpg, jpeg, png!',
            'avatar.max' => 'Vui lòng tải ảnh có dung lượng dưới 2MB!',
            'avatar.uploaded' => 'Vui lòng tải ảnh có dung lượng dưới 2MB!',
        ];

        $attributes = [
            'name' => 'Tên',
            'phone' => 'Số điện thoại',
            'old_password' => 'Mật khẩu cũ',
            'password' => 'Mật khẩu cũ',
            'avatar' => 'Ảnh đại diện',
        ];

        $validator = Validator::make($filteredData, $rules, $messages, $attributes);

        if ($validator->fails()) {
            flash('Thông báo', 'Cập nhật thông tin cá nhân thất bại!', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            if ($request->hasFile('avatar')) {
                $this->userService->updateAvatar(Auth::user()->id, $request->file('avatar'));
            }

            $validatedData = array_intersect_key($filteredData, $validator->valid());
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }
            $this->userService->update(Auth::user()->id, $validatedData);
        }
        catch (Exception $e) {
            app_log()->error('Error updating user avatar: ' . $e->getMessage());
            flash('Thông báo', 'Quá trình cập nhật thông tin của hệ thống gặp lỗi, vui lòng báo lại BQT để giải quyết!', 'error');
        }

        flash('Thông báo', 'Cập nhật thông tin cá nhân thành công!', 'success');
        return redirect()->back();
    }

    /*
        Check if the given score is beyond the jikunten
        level: 1~5
        kubun: 1: 言語知識（文字・語彙・文法）; 2: 読解; 3: 聴解
        score: score to check
        return: true if the given score is over the jikunten and else
    */
    private function checkKijunTen($level, $kubun, $score)
    {
        switch ($level) {
            case 1:
            case 2:
            case 3:
                switch ($kubun) {
                    case 1:
                    case 2:
                    case 3:
                        return ($score > 19) ? true : false;
                        break;
                }
                break;
            case 4:
            case 5:
                switch ($kubun) {
                    case 1:
                        return ($score > 38) ? true : false;
                        break;
                    case 2:
                        return ($score > 19) ? true : false;
                        break;
                        break;
                }
        }
        return false;
    }

    /*
        Check if the given total score is beyond the Passing score
        level: 1~5
        total_score: score to check
        return: true if the given total score is over the Passing score and else
    */
    private function checkPassingscore($level, $total_score)
    {
        switch ($level) {
            case 1:
                return ($total_score >= 100) ? true : false;
                break;
            case 2:
            case 4:
                return ($total_score >= 90) ? true : false;
                break;
            case 3:
                return ($total_score >= 95) ? true : false;
                break;
            case 5:
                return ($total_score >= 80) ? true : false;
                break;
        }
        return false;
    }

    /*
        Check if the given scores is beyond the jikunten in any kubun
        level: 1~5
        score_kubun1~3: score to check
        return: false if the given scores is under any jikunten and else
    */
    private function checkKijunTenAnyKubun($level, $score_kubun1, $score_kubun2, $score_kubun3)
    {
        switch ($level) {
            case 1:
            case 2:
            case 3:
                if (!$this->checkKijunTen($level, 1, $score_kubun1))
                    return false;
                if (!$this->checkKijunTen($level, 2, $score_kubun2))
                    return false;
                if (!$this->checkKijunTen($level, 3, $score_kubun3))
                    return false;
                return true;
                break;
            case 4:
            case 5:
                if (!$this->checkKijunTen($level, 1, $score_kubun1))
                    return false;
                if (!$this->checkKijunTen($level, 2, $score_kubun3))
                    return false;
                return true;
                break;
        }
        return false;
    }
}
