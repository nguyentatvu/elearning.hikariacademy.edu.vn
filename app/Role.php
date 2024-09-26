<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public const OWNER = 1;
    public const ADMIN = 2;
    public const PARENT = 4;
    public const STUDENT = 5;
    public const TEST = 6;
    public const EXPORT = 7;
    public const INPUT = 8;

    protected $fillable = ['name', 'display_name', 'description'];

    public static function getRoles()
    {
        return Role::all();
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'role_user');
    }

    /**
     * Get role id from role name
     */
    public static function getRoleId($role_name)
    {
        return Role::where('name', '=', $role_name)->get()->first();
    }
}
