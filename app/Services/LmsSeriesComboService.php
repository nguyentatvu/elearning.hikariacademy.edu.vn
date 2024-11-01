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
    private $lmsContentService;
    private $paymentMethodService;

    public function __construct(
        LmsSeriesComboRepository $repository,
        PaymentMethodService $paymentMethodService
    ) {
        parent::__construct($repository);
        $this->paymentMethodService = $paymentMethodService;
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
     * Get LmsContentService
     *
     * @return LmsContentService
     */
    public function getLmsContentService()
    {
        if (!$this->lmsContentService) {
            $this->lmsContentService = app(LmsContentService::class);
        }
        return $this->lmsContentService;
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
    public function getMySeries(int $userId, int $type = LmsSeries::COURSE_AND_EXAM)
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

    /**
     * Get all series by type exclude combo series id
     *
     * @param $type
     * @param $comboSeriesId
     * @return mixed
     */
    public function getAllPaidSeriesByTypeExcludeComboId($type, $comboSeriesId)
    {
        $allPaidComboSeries =  $this->repository->getAllPaidSeriesByTypeExcludeComboId($type, $comboSeriesId);
        $allPaidComboSeries->map(function ($item) {
            $seriesIdList = [];

            for($i = 1; $i <= 5; $i++) {
                if (!is_null($item->{'n'.$i})) {
                    $seriesIdList[] = $item->{'n'.$i};
                }
            }

            if (count($seriesIdList) == 1) {
                $item->content_count = $this->getLmsContentService()->getContentCountBySeries($seriesIdList[0]);
                $item->chapter_count = $this->getLmsContentService()->getChapterCountBySeries($seriesIdList[0]);
                $item->seriesList = [$this->getLmsSeriesService()->findById($seriesIdList[0])];
            } elseif (count($seriesIdList) > 1) {
                $chapterCount = 0;
                $contentCount = 0;
                $seriesList = [];

                foreach ($seriesIdList as $seriesId) {
                    $chapterCount += $this->getLmsContentService()->getChapterCountBySeries($seriesId);
                    $contentCount += $this->getLmsContentService()->getContentCountBySeries($seriesId);
                    $seriesList[] = $this->getLmsSeriesService()->findById($seriesId);
                }

                $item->content_count = $contentCount;
                $item->chapter_count = $chapterCount;
                $item->seriesList = $seriesList;
            }

            if (Auth::check()) {
                $item->valid_payment = $this->paymentMethodService->checkSerieValidity(Auth::user()->id, $item->id);
            } else {
                $item->valid_payment = false;
            }

            return $item;
        });

        return $allPaidComboSeries;
    }

    /**
     * Get all paid series by type
     *
     * @param $type
     * @return mixed
     */
    public function getAllPaidSeriesByType($type)
    {
        $allPaidComboSeries =  $this->repository->getAllPaidSeriesByType($type);
        $allPaidComboSeries->map(function ($item) {
            $seriesIdList = [];

            for($i = 1; $i <= 5; $i++) {
                if (!is_null($item->{'n'.$i})) {
                    $seriesIdList[] = $item->{'n'.$i};
                }
            }

            if (count($seriesIdList) == 1) {
                $item->content_count = $this->getLmsContentService()->getContentCountBySeries($seriesIdList[0]);
                $item->chapter_count = $this->getLmsContentService()->getChapterCountBySeries($seriesIdList[0]);
                $item->seriesList = [$this->getLmsSeriesService()->findById($seriesIdList[0])];
            } elseif (count($seriesIdList) > 1) {
                $chapterCount = 0;
                $contentCount = 0;
                $seriesList = [];

                foreach ($seriesIdList as $seriesId) {
                    $chapterCount += $this->getLmsContentService()->getChapterCountBySeries($seriesId);
                    $contentCount += $this->getLmsContentService()->getContentCountBySeries($seriesId);
                    $seriesList[] = $this->getLmsSeriesService()->findById($seriesId);
                }

                $item->content_count = $contentCount;
                $item->chapter_count = $chapterCount;
                $item->seriesList = $seriesList;
            }

            if (Auth::check()) {
                $item->valid_payment = $this->paymentMethodService->checkSerieValidity(Auth::user()->id, $item->id);
            } else {
                $item->valid_payment = false;
            }

            return $item;
        });

        return $allPaidComboSeries;
    }

    /**
     * Get all series by type
     *
     * @param $type
     * @return mixed
     */
    public function getAllSeriesByType($type)
    {
        $allPaidComboSeries =  $this->repository->getAllSeriesByType($type);
        $allPaidComboSeries->map(function ($item) {
            $seriesIdList = [];

            for($i = 1; $i <= 5; $i++) {
                if (!is_null($item->{'n'.$i})) {
                    $seriesIdList[] = $item->{'n'.$i};
                }
            }

            if (count($seriesIdList) == 1) {
                $item->content_count = $this->getLmsContentService()->getContentCountBySeries($seriesIdList[0]);
                $item->chapter_count = $this->getLmsContentService()->getChapterCountBySeries($seriesIdList[0]);
                $item->seriesList = [$this->getLmsSeriesService()->findById($seriesIdList[0])];
            } elseif (count($seriesIdList) > 1) {
                $chapterCount = 0;
                $contentCount = 0;
                $seriesList = [];

                foreach ($seriesIdList as $seriesId) {
                    $chapterCount += $this->getLmsContentService()->getChapterCountBySeries($seriesId);
                    $contentCount += $this->getLmsContentService()->getContentCountBySeries($seriesId);
                    $seriesList[] = $this->getLmsSeriesService()->findById($seriesId);
                }

                $item->content_count = $contentCount;
                $item->chapter_count = $chapterCount;
                $item->seriesList = $seriesList;
            }

            if (Auth::check()) {
                $item->valid_payment = $this->paymentMethodService->checkSerieValidity(Auth::user()->id, $item->id);
            } else {
                $item->valid_payment = false;
            }

            return $item;
        });

        return $allPaidComboSeries;
    }

    /**
     * Get series combo by slug with its series
     *
     * @param string $combo_slug
     * @return mixed
     */
    public function getSeriesComboBySlugWithSeries(string $combo_slug) {
        $seriesCombo = $this->getByCondition('slug', $combo_slug);

        $seriesList = [];
        for($index = 1; $index <= 5; $index++) {
            if ($seriesCombo->{"n{$index}"}) {
                $series = $this->getLmsSeriesService()->findById($seriesCombo->{"n{$index}"});
                $series->content_count = $this->getLmsContentService()->getContentCountBySeries($series->id);
                $series->comboSeries = $seriesCombo;
                $series->month_duration = config('constant.series_combo.month_duration_map')[$series->comboSeries->time];
                $seriesList[] = $series;
            }
        }
        $seriesCombo->seriesList = $seriesList;
        $seriesCombo->month_duration = config('constant.series_combo.month_duration_map')[$seriesCombo->time];

        return $seriesCombo;
    }

    /**
     * Get single series combo by series id
     *
     * @param string $seriesId
     * @param string $userId
     * @return mixed
     */
    public function getSingleSeriesComboBySeriesId(string $seriesId, string $userId) {
        return $this->repository->getSingleSeriesComboBySeriesId($seriesId, $userId);
    }
}
