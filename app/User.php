<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use JWTAuth;
use DB;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $visible = ['id'];

    protected $dates = ['last_seen'];

    # set password
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    # set email
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    # volunteer
    public function volunteer()
    {
        return $this->hasOne('App\Volunteer');
    }

    # forprofit
    public function forprofit()
    {
        return $this->hasOne(App\ForProfit::class);
    }

    # nonprofit
    public function nonprofit()
    {
        return $this->hasOne(App\Nonprofit);
    }

    # nonprofits
    public function nonprofits()
    {
        return $this->morphedByMany('App\Nonprofit', 'employer', 'employees')
            ->orderBy('name', 'asc')
            ->orderBy('id', 'asc');
    }

    # nonprofits with admin access
    public function nonprofitsWithAdminAccess()
    {
        $roles = Role::whereIn('name', ['nonprofit_owner', 'nonprofit_manager'])->pluck('id');

        return $this->morphedByMany('App\Nonprofit', 'employer', 'employees')
            ->whereIn('employees.role_id', $roles)
            ->orderBy('name', 'asc')
            ->orderBy('id', 'asc');
    }

    # forprofits
    public function forprofits()
    {
        return $this->morphedByMany('App\Forprofit', 'employer', 'employees')
            ->orderBy('name', 'asc')
            ->orderBy('id', 'asc');
    }

    # forprofits with admin access
    public function forprofitsWithAdminAccess()
    {
        $roles = Role::whereIn('name', ['forprofit_owner', 'forprofit_manager'])->pluck('id');

        return $this->morphedByMany('App\Forprofit', 'employer', 'employees')
            ->whereIn('employees.role_id', $roles)
            ->orderBy('name', 'asc')
            ->orderBy('id', 'asc');
    }

    # is admin for
    public function isAdmin()
    {
        return $this->admin ? true : false;
    }

    #posts
    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
