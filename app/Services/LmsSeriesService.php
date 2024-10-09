<?php

namespace App\Services;

use App\LmsContent;
use App\Repositories\LmsSeriesRepository;

class LmsSeriesService extends BaseService
{
    private $paymentMethodService;
    private $lmsSeriesComboService;

    public function __construct(
        LmsSeriesRepository $repository,
        PaymentMethodService $paymentMethodService,
        LmsSeriesComboService $lmsSeriesComboService
    ) {
        parent::__construct($repository);
        $this->paymentMethodService = $paymentMethodService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
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
        $seriesCombo = $this->lmsSeriesComboService->getBySeriesId($seriesComboId, $seriesId, ['cost', 'time']);

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

        return $seriesDetail;
    }
}