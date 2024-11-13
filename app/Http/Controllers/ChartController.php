<?php

namespace App\Http\Controllers;

use App\Enums\LearningStatus;
use App\LmsContent;
use App\Payment;
use App\PaymentMethod;
use App\QuizResultfinish;
use App\Role;
use App\Services\UserService;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getChartData()
    {
        $data = [
            'data' => [
                'numberOfStudentsTakingTheExam' => [
                    'title' => 'Biểu đồ số học viên thi',
                    'data' => $this->getNumberOfStudentsTakingTheExam(),
                ],
                'courseStatus' => [
                    'title' => 'Biểu đồ tình trạng khoá học',
                    'data' => $this->getCourseStatus(0),
                ],
                'examStatus' => [
                    'title' => 'Biểu đồ tình trạng khoá luyện thi',
                    'data' => $this->getCourseStatus(1),
                ]
            ],
        ];

        return response()->json($data, 200);
    }

    private function getNumberOfStudentsTakingTheExam()
    {
        $numberOfTests = QuizResultfinish::where('exam_free_id', '>', 0)->get()->count();
        $numberOfStudentsCompleted = QuizResultfinish::where('exam_free_id', '>', 0)->where('finish', '=', 3)->get()->count();
        $numberOfStudentsIncomplete = QuizResultfinish::where('exam_free_id', '>', 0)->where('finish', '<>', 3)->get()->count();
        $numberOfStudentsPassed = QuizResultfinish::where('exam_free_id', '>', 0)->where('finish', '=', 3)->where('status', '=', 1)->get()->count();
        $numberOfStudentsFailed = QuizResultfinish::where('exam_free_id', '>', 0)->where('finish', '=', 3)->where('status', '=', 0)->get()->count();
        $result = [
            [
                'value' => $numberOfStudentsIncomplete,
                'name' => "Số học viên không hoàn thành bài thi"
            ],
            [
                'value' => $numberOfStudentsPassed,
                'name' => "Số học viên thi đạt"
            ],
            [
                'value' => $numberOfStudentsFailed,
                'name' => "Số học viên thi chưa đạt"
            ]
        ];
        return $result;
    }

    private function getCourseStatus($type = 0)
    {
        $studentOfLearningProcess = DB::table('users')
            ->select([
                'users.id as user_id',
                'lmsseries_combo.id as combo_id',
                'lmsseries_combo.time',
                'payment_method.responseTime',
                'payment_method.month_extend',
                DB::raw('CONCAT("[", 
                    NULLIF(
                        CONCAT_WS(",",
                            IF(n1 IS NOT NULL, n1, NULL),
                            IF(n2 IS NOT NULL, n2, NULL),
                            IF(n3 IS NOT NULL, n3, NULL),
                            IF(n4 IS NOT NULL, n4, NULL),
                            IF(n5 IS NOT NULL, n5, NULL)
                        ),
                        ""
                    ),
                "]") as series_ids')
            ])
            ->join('payment_method', function ($join) {
                $join->on('users.id', '=', 'payment_method.user_id')
                    ->where('payment_method.status', '=', PaymentMethod::PAYMENT_SUCCESS);
            })
            ->join('lmsseries_combo', function ($join) use ($type) {
                $join->on('lmsseries_combo.id', '=', 'payment_method.item_id')
                    ->where('lmsseries_combo.delete_status', '=', 0)
                    ->where('lmsseries_combo.type', '=', $type);
                   // ->where('lmsseries_combo.cost', '!=', 0);
            })
            ->whereRaw('(
                IF(n1 IS NOT NULL, 1, 0) +
                IF(n2 IS NOT NULL, 1, 0) +
                IF(n3 IS NOT NULL, 1, 0) +
                IF(n4 IS NOT NULL, 1, 0) +
                IF(n5 IS NOT NULL, 1, 0)
            ) >= 1')
            ->where('users.role_id', '=', Role::STUDENT)
            ->orderBy('lmsseries_combo.id', 'ASC')
            ->get();

        $studentOfLearningProcess = $studentOfLearningProcess->toArray();
        $seriesProgress = [];

        foreach ($studentOfLearningProcess as &$process) {
            $seriesIds = json_decode($process->series_ids);

            foreach ($seriesIds as $seriesId) {
                $totalLessons = DB::table('lmscontents')
                    ->where('lmsseries_id', $seriesId)
                    ->where('delete_status', 0)
                    ->whereNotIn('type', [LmsContent::LESSON, LmsContent::LESSON_TOPIC])
                    ->count();

                $viewedLessons = DB::table('lmscontents')
                    ->join('lms_student_view', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
                    ->where('lmscontents.lmsseries_id', $seriesId)
                    ->where('lms_student_view.users_id', $process->user_id)
                    ->where('lms_student_view.finish', 1)
                    ->where('lmscontents.delete_status', 0)
                    ->count();

                // Check expire date
                $time = $process->time;
                $monthExtend = $process->month_extend;
                $daysToAdd = 0;
                if ($time == 0) {
                    $daysToAdd = 90;
                } else if ($time == 1) {
                    $daysToAdd = 180;
                } else if ($time == 2) {
                    $daysToAdd = 365;
                }

                if ($monthExtend == 0) {
                    $daysToAdd += 0;
                } else if ($monthExtend == 3) {
                    $daysToAdd += 30;
                } else if ($monthExtend == 6) {
                    $daysToAdd += 90;
                } else if ($monthExtend == 9) {
                    $daysToAdd += 180;
                } else if ($monthExtend == 12) {
                    $daysToAdd += 365;
                }

                $expireDate = date('Y-m-d', strtotime("$process->responseTime +$daysToAdd days"));
                $currentDate = date('Y-m-d');

                if (!isset($seriesProgress[$seriesId])) {
                    $seriesProgress[$seriesId] = [
                        LearningStatus::COMPLETED => 0,
                        LearningStatus::IN_PROGRESS => 0,
                        LearningStatus::NOT_COMPLETED => 0,
                    ];
                }

                if ($viewedLessons == $totalLessons) {
                    $seriesProgress[$seriesId][LearningStatus::COMPLETED] += 1;
                } else {
                    if ($expireDate >= $currentDate) {
                        $seriesProgress[$seriesId][LearningStatus::IN_PROGRESS] += 1;
                    } else {
                        $seriesProgress[$seriesId][LearningStatus::NOT_COMPLETED] += 1;
                    }
                }
            }
        }

        $series = DB::table('lmsseries')
            ->select('id', 'title')
            ->where('delete_status', 0)
            ->where('type_series', $type)
            ->get('title');

        // Handle data
        $series = $series->toArray();
        $course = array_column($series, 'title');
        $rows = 3;
        $cols = count($series);
        $result = array_fill(0, $rows, array_fill(0, $cols, 0));

        foreach ($seriesProgress as $key => $value) {
            for ($seriesIndex = 0; $seriesIndex < $cols; $seriesIndex++) {
                if ($key == $series[$seriesIndex]->id) {
                    $result[0][$seriesIndex] += $value[LearningStatus::IN_PROGRESS];
                    $result[1][$seriesIndex] += $value[LearningStatus::COMPLETED];
                    $result[2][$seriesIndex] += $value[LearningStatus::NOT_COMPLETED];
                }
            }
        }

        return [
            'course' => $course,
            'data' => $result,
            'status' => ['Đang học', 'Hoàn thành', 'Chưa hoàn thành'],
        ];
    }

    public function getNumberOfStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date_format:d-m-Y|before_or_equal:end_date',
            'end_date' => 'required|date_format:d-m-Y|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $record = $this->userService->getNewStudentsRegistered($startDate, $endDate);

        $data['list_date_count_user'] = $record->pluck('date');
        $data['list_count_user'] = $record->pluck('count');

        return response()->json([
            'data' => $data,
            'title' => 'Biểu đồ số lượng học viên đăng ký mới',
        ], 200);
    }
}
