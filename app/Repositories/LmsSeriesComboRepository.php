<?php

namespace App\Repositories;

use App\LmsContent;
use App\PaymentMethod;
use Illuminate\Support\Facades\DB;

class LmsSeriesComboRepository extends BaseRepository
{
    /**
     * Get list of series by type
     *
     * @param array $filters
     * @return any
     */
    public function getSeriesCombo(int $userId, array $filters = [])
    {
        $data = $this->model::select(
            'lmsseries_combo.*',
            DB::raw("(SELECT COUNT(id) FROM lmscontents
              WHERE lmscontents.delete_status = 0
              AND type NOT IN (" . LmsContent::LESSON . ", " . LmsContent::LESSON_TOPIC . ")
              AND lmscontents.lmsseries_id IN (lmsseries_combo.n1, lmsseries_combo.n2, lmsseries_combo.n3, lmsseries_combo.n4, lmsseries_combo.n5)) as total_lessons"),
            DB::raw("(SELECT COUNT(id) FROM lmscontents
              WHERE lmscontents.delete_status = 0
              AND lmscontents.el_try = 1
              AND type NOT IN (" . LmsContent::LESSON . ", " . LmsContent::LESSON_TOPIC . ")
              AND lmscontents.lmsseries_id IN (lmsseries_combo.n1, lmsseries_combo.n2, lmsseries_combo.n3, lmsseries_combo.n4, lmsseries_combo.n5)) as trial_lessons"),
            DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
            DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = " . $userId . "   AND payment_method.status = 1
                    AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
        )
            ->where('lmsseries_combo.delete_status', 0)
            ->when(isset($filters['type']), function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            })
            ->when(isset($filters['keyword']) && $filters['keyword'], function ($query) use ($filters) {
                $query->where('lmsseries_combo.title', 'like', '%' . $filters['keyword'] . '%');
            })
            ->distinct()
            ->paginate(10);

        return $data;
    }

    /**
     * Get list of my series
     *
     * @param int $userId
     * @param int $type
     * @return mixed
     */
    public function getMySeries(int $userId, int $type)
    {
        $series = $this->model::join('payment_method', 'payment_method.item_id', '=', 'lmsseries_combo.id')
            ->join('payments', 'payment_method.id', '=', 'payments.payments_method_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'payments.item_id')
            ->select(
                'lmsseries_combo.id as series_combo_id',
                'lmsseries.id as series_id',
                'lmsseries.title',
                'payments.time',
                'payment_method.created_at',
                'payment_method.status',
                'payment_method.month_extend',
                DB::raw("(SELECT COUNT(lmscontents.id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5) ) as total_lessons"),
                DB::raw("(SELECT COUNT(lms_student_view.id)  FROM lms_student_view
                join lmscontents on lms_student_view.lmscontent_id = lmscontents.id
        WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND lms_student_view.users_id = " . $userId . " AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
             ) as completed_lessons")
            )
            ->where([
                ['payment_method.user_id', $userId],
                ['payment_method.status', PaymentMethod::PAYMENT_SUCCESS],
                ['lmsseries_combo.delete_status', 0],
                ['lmsseries_combo.type', $type],
            ])
            ->distinct()
            ->orderBy('payment_method.created_at', 'desc')
            ->get();

        return $series;
    }

    /**
     * Get list of redeemed series
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getRedeemedSeries() {
        $series = $this->model::where('redeem_point', '>', 0)->get();
        return $series;
    }
}
