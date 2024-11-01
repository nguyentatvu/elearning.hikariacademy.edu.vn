<?php

namespace App\Services;

use App\LmsContent;
use App\LmsSeries;
use App\Repositories\LmsSeriesRepository;

class LmsSeriesService extends BaseService
{
    private $paymentMethodService;
    private $lmsSeriesComboService;
    private $userRoadmapService;

    public function __construct(
        LmsSeriesRepository $repository,
        PaymentMethodService $paymentMethodService,
        UserRoadmapService $userRoadmapService
    ) {
        parent::__construct($repository);
        $this->paymentMethodService = $paymentMethodService;
        $this->userRoadmapService = $userRoadmapService;
    }

    /**
     * Get LmsSeriesComboService
     *
     * @return LmsSeriesComboService
     */
    public function getLmsSeriesComboService()
    {
        if (!$this->lmsSeriesComboService) {
            $this->lmsSeriesComboService = app(LmsSeriesComboService::class);
        }
        return $this->lmsSeriesComboService;
    }

    /**
     * Get Series with teachers
     *
     * @param array $seriesArray
     * @return Collection
     */
    public function getSeriesWithTeachers(array $seriesArray)
    {
        $seriesAndTeachers = $this->repository->getSeriesWithTeachers($seriesArray);

        return $seriesAndTeachers;
    }

    /**
     * Get series detail
     *
     * @param int $userId
     * @param int $seriesComboId
     * @param int $seriesId
     * @return LmsSeries
     */
    public function getSeriesDetail(int $userId, int $seriesComboId, int $seriesId)
    {
        $seriesCombo = $this->getLmsSeriesComboService()->getBySeriesId($seriesComboId, $seriesId, ['cost', 'time', 'image']);

        if (!$seriesCombo) {
            return null;
        }

        $seriesDetail = $this->repository->getSeriesDetail($seriesId);
        $isValid = $this->paymentMethodService->checkSerieValidity($userId, $seriesComboId);
        $payment = $isValid ? 1 : 0;
        $seriesDetail->payment = $payment;
        $seriesDetail->total_lessons = LmsContent::totalLessons($seriesId)->count();
        $seriesDetail->cost = $seriesCombo->cost;
        $seriesDetail->time = $seriesCombo->time;
        $seriesDetail->image = $seriesCombo->image;

        return $seriesDetail;
    }

    /**
     * Get History Views
     *
     * @param array $viewHistory
     * @param User $user
     * @return Collection
     */
    public function getHistoryViews(array $viewHistory, $user) {
        $seriesIdList = array_column($viewHistory, 'series_id');
        $series = $this->repository->getByColumnIn('id', $seriesIdList);

        $seriesCombo = $this->getLmsSeriesComboService()->getMySeries($user->id, LmsSeries::COURSE_AND_EXAM);
        $seriesCombo = collect($seriesCombo->items());

        $userRoadmaps = $this->userRoadmapService->getAllByConditions(['user_id' => $user->id]);
        // Create a map from 'series_id' to view info
        $viewMap = collect($viewHistory)->keyBy('series_id');

        return $series->map(function ($seriesItem) use ($viewMap, $seriesCombo, $userRoadmaps, $user) {
            $viewInfo = $viewMap->get($seriesItem->id);
            $seriesItem->viewed_time = $viewInfo['viewed_time'] ?? null;

            $userRoadmapItem = $userRoadmaps->where('lmsseries_id', $seriesItem->id)->first();
            $seriesComboItem = $seriesCombo->where('series_id', $seriesItem->id)->first();
            if (!$seriesComboItem) {
                return null;
            }

            $seriesItem->order = $viewInfo['order'] ?? null;
            if (!$seriesComboItem->completed_lessons || !$seriesComboItem->total_lessons) {
                $seriesItem->progressPercent = 0;
            } else {
                $seriesItem->progressPercent = (int) (($seriesComboItem->completed_lessons / $seriesComboItem->total_lessons) * 100);
            }
            $seriesItem->combo_slug = $seriesComboItem->combo_slug ?? '';
            $seriesItem->roadmapChosen = ($userRoadmapItem && $userRoadmapItem->duration_months != null) ? true : false;
            $seriesItem->seriesCombo = $seriesComboItem;

            return $seriesItem;
        })->sortBy('order')->values()->filter();
    }


    /**
     * Get all series with roadmap
     *
     * @return Collection
     */
    public function getAllWithRoadmapsAndLessons()
    {
        return $this->repository->getAllWithRoadmapsAndLessons();
    }

    /**
     * Get series list of series combo
     *
     * @param $seriesComboSlug
     * @return void
     */
    public function getSeriesListOfSeriesComboSlug(string $seriesComboSlug) {
        $seriesCombo = $this->getLmsSeriesComboService()->getByCondition('slug', $seriesComboSlug);

        return $this->repository->getSeriesListOfSeriesCombo($seriesCombo);
    }
}