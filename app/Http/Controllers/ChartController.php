<?php

namespace App\Http\Controllers;

use App\Payment;
use App\QuizResultfinish;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
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
        $studentOfLearningProcess = DB::table(DB::raw('(
            SELECT COALESCE(n1, n2, n3, n4, n5) AS lmsseries_id, id
            FROM lmsseries_combo
            WHERE delete_status = 0
              AND `type` = ' . intval($type) . '
              AND (
                    IF(n1 IS NOT NULL, 1, 0) + IF(n2 IS NOT NULL, 1, 0) +
                    IF(n3 IS NOT NULL, 1, 0) + IF(n4 IS NOT NULL, 1, 0) +
                    IF(n5 IS NOT NULL, 1, 0)
                ) = 1
        ) AS active_series'))
            ->join(DB::raw('(
                SELECT *
                FROM lmscontents
                WHERE lmscontents.delete_status = 0
                  AND lmscontents.type NOT IN (0, 8)
            ) AS lmscontents'), 'lmscontents.lmsseries_id', '=', 'active_series.lmsseries_id')
            ->join(DB::raw('(
                SELECT DISTINCT lmscontent_id, users_id
                FROM lms_student_view
            ) AS lms_student_view'), 'lms_student_view.lmscontent_id', '=', 'lmscontents.id')
            ->join('users', 'users.id', '=', 'lms_student_view.users_id')
            ->join(DB::raw('(
                SELECT user_id, item_id, responseTime, month_extend
                FROM (
                    SELECT user_id, item_id, responseTime, month_extend,
                        ROW_NUMBER() OVER (PARTITION BY user_id, item_id ORDER BY responseTime DESC) AS row_number_
                    FROM payment_method
                ) ranked
                WHERE row_number_ = 1
                ORDER BY responseTime DESC
            ) AS payment_method'), function ($join) {
                $join->on('payment_method.user_id', '=', 'users.id')
                    ->on('payment_method.item_id', '=', 'active_series.id');
            })
            ->select(
                'users.id as user_id',
                DB::raw('COUNT(*) AS count'),
                'active_series.lmsseries_id',
                'payment_method.responseTime',
                'payment_method.month_extend'
            )
            ->groupBy('users.id', 'active_series.lmsseries_id', 'payment_method.responseTime', 'payment_method.month_extend')
            ->get();

        $learningProcess = DB::table(DB::raw('(SELECT COALESCE(n1, n2, n3, n4, n5) AS lmsseries_id, id, time, title
        FROM lmsseries_combo
        WHERE delete_status = 0
          AND `type` = ' . intval($type) . '
          AND (
                IF(n1 IS NOT NULL, 1, 0) + IF(n2 IS NOT NULL, 1, 0) +
                IF(n3 IS NOT NULL, 1, 0) + IF(n4 IS NOT NULL, 1, 0) +
                IF(n5 IS NOT NULL, 1, 0)
            ) = 1) AS active_series'))
            ->join('lmscontents', 'lmscontents.lmsseries_id', '=', 'active_series.lmsseries_id')
            ->where('lmscontents.delete_status', 0)
            ->whereNotIn('lmscontents.type', [0, 8])
            ->select(
                'lmscontents.lmsseries_id',
                DB::raw('COUNT(lmscontents.lmsseries_id) AS count'),
                DB::raw('MAX(active_series.time) AS time'),
                'active_series.title'
            )
            ->groupBy('lmscontents.lmsseries_id', 'active_series.title')
            ->get();

        // Handle data
        $studentOfLearningProcessArray = $studentOfLearningProcess->toArray();
        $learningProcessArray = $learningProcess->toArray();
        $course = array_column($learningProcessArray, 'title');
        $rows = 3;
        $cols = count($learningProcessArray);
        $result = array_fill(0, $rows, array_fill(0, $cols, 0));

        for ($studentIndex = 0; $studentIndex < count($studentOfLearningProcessArray); $studentIndex++) {
            $student = $studentOfLearningProcessArray[$studentIndex];
            for ($learningIndex = 0; $learningIndex < $cols; $learningIndex++) {
                if ($student->lmsseries_id == $learningProcessArray[$learningIndex]->lmsseries_id) {
                    $learningInfo = $learningProcessArray[$learningIndex];
                    $time = $learningInfo->time;
                    $monthExtend = $student->month_extend;
                    $daysToAdd = 0;
                    if ($time == 0) {
                        $daysToAdd = 90;
                    } elseif ($time == 1) {
                        $daysToAdd = 180;
                    } elseif ($time == 2) {
                        $daysToAdd = 365;
                    }

                    if ($monthExtend == 0) {
                        $daysToAdd = 0;
                    } elseif ($monthExtend == 3) {
                        $daysToAdd = 30;
                    } elseif ($monthExtend == 6) {
                        $daysToAdd = 90;
                    } elseif ($monthExtend == 9) {
                        $daysToAdd = 180;
                    } elseif ($monthExtend == 12) {
                        $daysToAdd = 365;
                    }

                    $expireDate = date('Y-m-d', strtotime("$student->responseTime +$daysToAdd days"));
                    $currentDate = date('Y-m-d');

                    if ($student->count == $learningInfo->count) {
                        $result[1][$learningIndex] += 1;
                    } else {
                        if ($expireDate >= $currentDate) {
                            $result[0][$learningIndex] += 1;
                        } else {
                            $result[2][$learningIndex] += 1;
                        }
                    }
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
        $endDate = Carbon::parse($request->input('end_date'));

        $record = Payment::join('users', 'users.id', '=', 'payments.user_id')
            ->select(
                DB::raw('DATE(payments.created_at) as date'),
                DB::raw('COUNT(DISTINCT users.id) as user_count')
            )
            ->where('payment_status', PAYMENT_STATUS_SUCCESS)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(payments.created_at)'))
            ->get();

        $data['list_date_count_user'] = $record->pluck('date');
        $data['list_count_user'] = $record->pluck('user_count');

        return response()->json([
            'data' => $data,
            'title' => 'Biểu đồ số lượng học viên mới',
        ], 200);
    }
}
