<?php

namespace App;

use App\Services\LmsSeriesComboService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LmsSeriesCombo extends Model
{
    protected $table = 'lmsseries_combo';

    protected $casts = [
        'description' => 'array'
    ];

    public const LEARNING_TYPE = 0;
    public const EXAM_TYPE = 1;

    public static function getRecordWithSlug($slug)
    {
        return LmsSeriesCombo::where('slug', '=', $slug)->first();
    }

    public function getCheckMultipleComboAttribute() {
        $seriesCount = 0;
        for($i = 1; $i <= 5; $i++) {
            if (!is_null($this->{'n'.$i})) {
                $seriesCount++;
            }
        }

        return $seriesCount > 1;
    }

    public function getCheckPromotionAttribute() {
        if (!$this->timefrom || !$this->timeto) {
            return false;
        }

        return Carbon::now()->between(Carbon::parse($this->timefrom), Carbon::parse($this->timeto));
    }

    public function getActualCostAttribute() {
        return $this->checkPromotion ? $this->selloff : $this->cost;
    }

    public function getRedeemAmountAttribute() {
        return optional($this)->redeem_point
            ? $this->redeem_point * config('constant.redeemed_coin.vnd_convert_rate')
            : null;
    }

    public function getSeriesIdListAttribute() {
        $seriesIdList = [
            $this->n1,
            $this->n2,
            $this->n3,
            $this->n4,
            $this->n5
        ];

        return array_filter($seriesIdList);
    }

    /**
     * Check if all roadmaps for each series in a combo are selected
     *
     * @param array $roadmapChosenList
     * @return boolean
     */
    public function checkAllSeriesRoadmapOfSeriesComboChosen(array $roadmapChosenList) {
        for ($index = 1; $index <= 5; $index++) {
            $seriesId = $this->{'n'.$index};
            if (!is_null($seriesId) && $roadmapChosenList[$seriesId] === false) {
                return false;
            }
        }

        return true;
    }
}
