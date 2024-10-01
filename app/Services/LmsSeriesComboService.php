<?php

namespace App\Services;

use App\Http\Resources\SeriesAndTeacherResource;
use App\LmsSeries;
use App\Repositories\LmsSeriesComboRepository;
use DateTime;

class LmsSeriesComboService extends BaseService
{
    private $lmsSeriesService;

    public function __construct(LmsSeriesComboRepository $repository, LmsSeriesService $lmsSeriesService)
    {
        parent::__construct($repository);
        $this->lmsSeriesService = $lmsSeriesService;
    }

    /**
     * Get Series
     *
     * @param int $userId
     * @param array $filters
     * @return any
     */
    public function getSeriesCombo(int $userId, array $filters)
    {
        $seriesCombos = $this->repository->getSeriesCombo($userId, $filters);

        foreach ($seriesCombos as &$seriesCombo) {
            $seriesArray = array_filter([$seriesCombo->n1, $seriesCombo->n2, $seriesCombo->n3, $seriesCombo->n4, $seriesCombo->n5], function ($value) {
                return !is_null($value);
            });

            $seriesAndTeachers = $this->lmsSeriesService->getSeriesWithTeachers($seriesArray);
            $series= SeriesAndTeacherResource::collection($seriesAndTeachers);
            $seriesCombo->series = $series;
        }

        return $seriesCombos;
    }

    /**
     * Get My Series
     *
     * @param int $userId
     * @param int $type
     * @return mixed
     */
    public function getMySeries(int $userId, int $type = LmsSeries::COURSE)
    {
        $mySeries = $this->repository->getMySeries($userId, $type);

        foreach ($mySeries as &$series) {
            $expiryDate = calculateExpiryDate($series->created_at, $series->time, $series->month_extend);
            $expiryDateTime = new DateTime($expiryDate);
            $currentDate = new DateTime();
            $series->expiry_date = $expiryDate;
            $series->is_active = ($expiryDateTime > $currentDate);
        }

        return $mySeries;
    }

    /**
     * Get Redeemed Series
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getRedeemedSeries() {
        $series = $this->repository->getRedeemedSeries();
        $series = $series->map(function ($item) {
            $item->redeemed_amount = $item->redeem_point * config('constant.redeemed_coin.vnd_convert_rate');
            $item->redeemed_percent = (int) ($item->redeemed_amount / $item->cost * 100);

            return $item;
        });

        return $series;
    }
}
