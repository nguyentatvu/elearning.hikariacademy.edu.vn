<?php

namespace App\Services;

use App\ImageSettings;
use App\LmsContent;
use App\LmsSeries;
use App\LmsStudentView;
use App\PointRule;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    private $imageService;
    private $lmsSeriesComboService;

    public function __construct(UserRepository $repository, ImageService $imageService, LmsSeriesComboService $lmsSeriesComboService)
    {
        parent::__construct($repository);
        $this->imageService = $imageService;
        $this->imageService->setDestination(app('App\ImageSettings')->getUploadUserThumbnailPath());
        $this->lmsSeriesComboService = $lmsSeriesComboService;
    }

    /**
     * Update Point
     *
     * @param int $rewardPoint
     * @param int $lmsContentId
     * @param int $userId
     * @return bool
     */
    public function updatePoint(int $rewardPoint, int $lmsContentId, int $userId)
    {
        $result = DB::table('users')
            ->join('lms_student_view', 'lms_student_view.users_id', '=', 'users.id')
            ->where('lms_student_view.lmscontent_id', $lmsContentId)
            ->where('lms_student_view.finish', LmsStudentView::NOT_FINISHED)
            ->where('users.id', $userId)
            ->first();

        if ($result && $result->type) {
            if (in_array(
                $result->type,
                [
                    LmsContent::VOCABULARY,
                    LmsContent::STRUCTURE,
                    LmsContent::KANJI,
                    LmsContent::SUMMARY_AND_INTRODUCTION,
                    LmsContent::TEST,
                    LmsContent::PARTIAL_EXERCISE,
                    LmsContent::SUMMARY_EXERCISE,
                    LmsContent::REVIEW_EXERCISE
                ]
            )) {
                DB::table('users')
                    ->where('id', $userId)
                    ->increment('reward_point', $rewardPoint);
            }

            return true;
        }

        return false;
    }

    /**
     * Update login streak
     *
     * @return void
     */
    public function updateLoginStreak() {
        $user = Auth::user();
        $user->refresh();

        $last_login_date = Carbon::parse($user->last_login_date);
        $loginStreak = $user->login_streak;
        $point = 0;
        $pointRule = getRewardPointRule('daily_login')['milestones'];
        $convertedPointRule = collect($pointRule)->pluck('points', 'days')->all();
        $streak =

        // Check if the last login was yesterday and login streak is 1 or more
        if ($last_login_date->isYesterday() && $loginStreak >= 1) {
            $user->login_streak += 1;
            $reward_point = $this->caculateRewardPoints($user->login_streak, $convertedPointRule);
            $user->reward_point += $reward_point;
            $point = $reward_point;
        } else {
            // Reset login streak to 1 if login is not consecutive
            $user->login_streak = 1;
            $reward_point = $this->caculateRewardPoints($user->login_streak, $convertedPointRule);
            $user->reward_point += $reward_point;
            $point = $reward_point;
        }

        $arrayHistoryPoint = $user->point_history;
        $arrayHistoryPoint['streak'] = $user->login_streak;
        $user->point_history = $arrayHistoryPoint;

        // Update last login date and save the user
        $user->last_login_date = now();
        $user->save();

        return  [
            'point' => $point,
            'streak' => $user->login_streak
        ];
    }


    /**
     * Calculate reward points
     *
     * @param  int $login_streak
     * @param  array $streak_conditions
     * @return int $rewarded_point
     */
    public function caculateRewardPoints(int $login_streak, array $streak_conditions) {
        $rewarded_point = 0;
        foreach ($streak_conditions as $min_streak => $coin_value) {
            if ($login_streak >= $min_streak) {
                $rewarded_point = $coin_value;
            } else {
                break;
            }
        }
        return $rewarded_point;
    }

    /**
     * Create User
     *
     * @param array $data
     * @return User
     */
    public function register(array $data = [])
    {
        DB::beginTransaction();
        try {
            $ipInfo = ip_info('Visitor', "Location");
            $newUID = $this->createUID();
            $newHID = $this->createHID($newUID);
            $password = Str::random(6);
            $data['uid'] = $newUID;
            $data['hid'] = $newHID;
            $data['username'] = $newHID;
            $data['role_id'] = Role::STUDENT;
            $data['is_register'] = 1;
            $data['password'] = bcrypt($password);
            $data['slug'] = createSlug($data['name']);
            $data['login_enabled'] = 1;
            $data['reward_point'] = 0;

            if ($ipInfo) {
                $data['country_code'] = $ipInfo['country_code'];
                $data['country'] = $ipInfo['country'];
                $data['city'] = $ipInfo['city'];
                $data['state'] = $ipInfo['state'];
                $data['ip'] = $ipInfo['ip'];
            }

            $user = $this->repository->create($data);
            $user->roles()->attach($user->role_id);

            $emailSent = sendEmail('registration',
                array(
                    'name' => $user->name,
                    'username' => $user->username,
                    'to_email' => $user->email,
                    'password' => $password,
                    'to_email_bcc' => env('TO_EMAIL_CC', 'dev@hikarinetworks.com')
            ));

            if (!$emailSent) {
                DB::rollBack();
                return false;
            }

            DB::commit();
            return $user;
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update User
     *
     * @param int $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes = [])
    {
        return $this->repository->update($id, $attributes);
    }

    /**
     * Update Avatar
     *
     * @param int $id
     * @param UploadedFile $file
     * @return string
     */
    public function updateAvatar(int $id, UploadedFile $file)
    {
        $filename = $this->uploadImageFile($id, $file);

        return $this->update($id, ['image' => $filename]);
    }

    /**
     * Get My Series
     *
     * @param int $userId
     * @param int $type
     * @return Collection
     */
    public function getMySeries(int $userId, int $type = LmsSeries::COURSE_AND_EXAM)
    {
        return $this->lmsSeriesComboService->getMySeries($userId, $type);
    }

    /**
     * Upload Image File
     *
     * @param int $id
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImageFile(int $id, UploadedFile $file)
    {
        $imageSetting = new ImageSettings();
        $filename = $id . '.' . $file->guessClientExtension();
        $this->imageService->removeAllImagesWithTheSameName($id, $imageSetting->getUploadUserThumbnailPath());
        $this->imageService->save($file, $filename);

        return $filename;
    }

    /**
     * Create UID
     *
     * @return string
     */
    protected function createUID()
    {
        $newUID = '00001';
        $lastUID = DB::table('users')
            ->whereYear('created_at', '=', date('Y'))
            ->where('uid', '<>', null)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastUID) {
            $uid  = $lastUID->uid;
            $uid  = ++$uid;
            $newUID  = str_pad($uid, 5, '0', STR_PAD_LEFT);
            $newUID = '' . $newUID . '';
        }

        return $newUID;
    }

    /**
     * Create HID
     *
     * @param string $uid
     * @return string
     */
    protected function createHID(string $uid)
    {
        $newHID = 'HID' . date('y') . $uid;

        return $newHID;
    }

    /**
     * Restore redeemed points
     *
     * @param string $user_id
     * @return void
     */
    public function restoreRedeemedPoints(?string $user_id = null)
    {
        $this->repository->restoreRedeemedPoints($user_id);
    }

    /**
     * Update Point History
     *
     * @param array $data
     * @param string $userId
     * @return void
     */
    public function updatePointHistory($data, string $userId = '') {
        $this->repository->updatePointHistory($data, $userId);
    }

    /**
     * Update series views history
     *
     * @param array $historyArray
     * @param mixed $seriesId
     * @param string $userId
     * @return void
     */
    public function updateSeriesViewsHistory(array $historyArray, $seriesId, ?string $userId = '') {
        $currentTime = date('Y-m-d H:i:s');

        $seriesIds = is_array($seriesId) ? $seriesId : [$seriesId];

        foreach ($seriesIds as $id) {
            $newItem = [
                'order' => 1,
                'series_id' => $id,
                'viewed_time' => $currentTime
            ];

            $existingIndex = array_search($id, array_column($historyArray, 'series_id'));

            if ($existingIndex !== false) {
                unset($historyArray[$existingIndex]);
            }

            foreach ($historyArray as &$item) {
                $item['order']++;
            }

            array_unshift($historyArray, $newItem);
        }

        foreach ($historyArray as $index => &$item) {
            $item['order'] = $index + 1;
        }

        $userId = $userId ? $userId : Auth::id();

        $this->repository->update($userId, ['series_views_history' => $historyArray]);
    }
}
