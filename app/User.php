<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use EntrustUserTrait;
    use Messagable;
    use Notifiable;

    protected $fillable = [
        'uid',
        'hid',
        'name',
        'username',
        'is_register',
        'is_hocvien',
        'email',
        'password',
        'slug',
        'confirmation_code',
        'level',
        'login_enabled',
        'role_id',
        'parent_id',
        'image',
        'phone',
        'address',
        'class',
        'stripe_active',
        'stripe_id',
        'stripe_plan',
        'paypal_email',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
        'subscription_ends_at',
        'remember_token',
        'settings',
        'point',
        'country_code',
        'country',
        'city',
        'state',
        'ip',
        'sendmail_time',
        'sendmail_free',
        'last_session',
        'deleted_at',
        'created_at',
        'updated_at',
        'email_exits',
        'last_send_status',
        'send_version',
        'reward_point',
        'last_login_date',
        'login_streak',
        'series_order_created_at',
        'recharge_point',
        'redeemed_points',
        'point_history',
        'series_views_history'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['trial_ends_at', 'subscription_ends_at', 'last_login_date'];

    protected $casts = [
        'redeemed_points' => 'array',
        'point_history' => 'array',
        'series_views_history' => 'array'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function staff()
    {
        return $this->hasOne('App\Staff');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user');
    }

    /**
     * Returns the student record from students table based on the relationship
     * @return [type]        [Student Record]
     */
    public function student()
    {
        return $this->hasOne('App\Student');
    }

    public static function getRecordWithSlug($slug)
    {
        return User::where('slug', '=', $slug)->first();
    }

    public function isChildBelongsToThisParent($child_id, $parent_id)
    {
        return User::where('id', '=', $child_id)
            ->where('parent_id', '=', $parent_id)
            ->get()
            ->count();
    }

    public function getLatestUsers($limit = 5)
    {
        return User::where('role_id', '=', getRoleData('student'))
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Relationship with LmsSeries model
     * A user (teacher) can belong to many series.
     */
    public function lmsseries()
    {
        return $this->belongsToMany(LmsSeries::class, 'lmsseries_teacher', 'teacher_id', 'lmsseries_id');
    }
}
