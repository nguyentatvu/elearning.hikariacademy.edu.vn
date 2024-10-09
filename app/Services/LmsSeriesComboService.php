<?php

namespace App\Services;

use App\Http\Resources\SeriesAndTeacherResource;
use App\LmsSeries;
use App\Repositories\LmsSeriesComboRepository;
use DateTime;
use Illuminate\Support\Facades\Auth;

class LmsSeriesComboService extends BaseService
{
    private $lmsSeriesService;

    public function __construct(LmsSeriesComboRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get LmsSeriesService
     *
     * @return LmsSeriesService
     */
    public function getLmsSeriesService()
    {
        if (!$this->lmsSeriesService) {
            $this->lmsSeriesService = app(LmsSeriesService::class);
        }
        return $this->lmsSeriesService;
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

            $seriesAndTeachers = $this->getLmsSeriesService()->getSeriesWithTeachers($seriesArray);
            $series = SeriesAndTeacherResource::collection($seriesAndTeachers);
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
    public function getRedeemedSeries()
    {
        $user = Auth::user();
        $onwedPoints = $user->reward_point + $user->recharge_point;
        $series = $this->repository->getRedeemedSeries();

        $series = $series->map(function ($item) use ($onwedPoints) {
            $item->redeemed_amount = $item->redeem_point * config('constant.redeemed_coin.vnd_convert_rate');
            $item->redeemed_percent = (int) ($item->redeemed_amount / $item->cost * 100);
            $item->is_payable = $onwedPoints >= $item->redeem_point;

            return $item;
        });

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
        return $this->repository->getBySeriesId($seriesComboId, $seriesId, $select);
    }
}
