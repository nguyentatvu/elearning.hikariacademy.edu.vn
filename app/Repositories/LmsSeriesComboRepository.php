<?php

namespace App\Repositories;

use App\LmsContent;
use App\LmsSeries;
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
                'lmsseries_combo.image',
                'lmsseries_combo.cost',
                'lmsseries.id as series_id',
                'lmsseries.title',
                'payments.time',
                'payment_method.created_at',
                'payment_method.status',
                'payment_method.month_extend',
                'payment_method.orderType',
                'lmsseries_combo.slug as combo_slug',
                DB::raw("(SELECT COUNT(lmscontents.id) FROM lmscontents
                            WHERE lmscontents.delete_status = 0
                                AND lmscontents.type NOT IN(0,8)
                                AND lmscontents.lmsseries_id = lmsseries.id)
                            as total_lessons"),
                DB::raw("(SELECT COUNT(lms_student_view.id) FROM lms_student_view
                            join lmscontents on lms_student_view.lmscontent_id = lmscontents.id
                            WHERE lmscontents.delete_status = 0
                                AND lmscontents.type NOT IN(0,8)
                                AND lms_student_view.users_id = " . $userId . "
                                AND lmscontents.lmsseries_id = lmsseries.id)
                            as completed_lessons")
            )
            ->where([
                ['payment_method.user_id', $userId],
                ['payment_method.status', PaymentMethod::PAYMENT_SUCCESS],
                ['lmsseries_combo.delete_status', 0],
            ])
            ->when($type != LmsSeries::COURSE_AND_EXAM, function ($query) use ($type) {
                $query->where('lmsseries_combo.type', $type);
            })
            ->distinct()
            ->orderBy('payment_method.created_at', 'desc')
            ->paginate(10);

        return $series;
    }

    /**
     * Get list of redeemed series
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getRedeemedSeries()
    {
        $series = $this->model::where('redeem_point', '>', 0)->get();
        return $series;
    }

    /**
     * Get series by series id
     *
     * @param int $seriesComboId
     * @param int $seriesId
     * @param array $select
     * @return mixed
     */
    public function getBySeriesId(int $seriesComboId, int $seriesId, array $select = ['*'])
    {
        $seriesCombo = $this->model::select($select)
            ->where('id', $seriesComboId)
            ->where('delete_status', 0)
            ->where(function ($query) use ($seriesId) {
                $query->where('n1', $seriesId)
                    ->orWhere('n2', $seriesId)
                    ->orWhere('n3', $seriesId)
                    ->orWhere('n4', $seriesId)
                    ->orWhere('n5', $seriesId);
            })
            ->first();

        return $seriesCombo;
    }

    /**
     * Get all paid series by type
     *
     * @param $type
     * @return mixed
     */
    public function getAllPaidSeriesByType($type)
    {
        return $this->model::where('type', $type)
            ->where('delete_status', 0)
            ->where('cost', '>', 0)
            ->get();
    }

    /**
     * Get all series by type
     *
     * @param $type
     * @return mixed
     */
    public function getAllSeriesByType($type)
    {
        return $this->model::where('type', $type)
            ->where('delete_status', 0)
            ->get();
    }

    /**
     * Get all series by type exclude combo series id
     *
     * @param $type
     * @param $comboSeriesId
     * @return mixed
     */
    public function getAllPaidSeriesByTypeExcludeComboId($type, $comboSeriesId)
    {
        return $this->model::where('type', $type)
            ->where('id', '<>', $comboSeriesId)
            ->where('delete_status', 0)
            ->where('cost', '>', 0)
            ->get();
    }

    /**
     * Get single series combo by series id
     *
     * @param string $seriesId
     * @return mixed
     */
    public function getSingleSeriesComboBySeriesId(string $seriesId) {
        return $this->model::where(function ($query) use ($seriesId) {
            $query->where('n1', $seriesId)
                ->orWhere('n2', $seriesId)
                ->orWhere('n3', $seriesId)
                ->orWhere('n4', $seriesId)
                ->orWhere('n5', $seriesId);
        })->whereRaw('(
            (n1 is not null) +
            (n2 is not null) +
            (n3 is not null) +
            (n4 is not null) +
            (n5 is not null)) = 1')
            ->first();
    }
}
