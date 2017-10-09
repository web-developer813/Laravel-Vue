<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Carbon\Carbon;

use App\Traits\SearchableTrait;
use App\Traits\HasLocationTrait;
use App\Traits\HasLogoTrait;
use App\Traits\PresentableTrait;
use App\Traits\FollowableTrait;

class Forprofit extends Model
{
    use PresentableTrait, SearchableTrait, FollowableTrait, HasLocationTrait, HasLogoTrait, Notifiable, Billable, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'mission', 'website_url', 'profile_photo_id', 'email', 'phone', 'forprofit_id',
        'location', 'location_lat', 'location_lng', 'location_address', 'location_city', 'location_state', 'location_country',
        'location_postal_code', 'location_suite', 'stripe_id','name_on_cart',
        'card_brand','card_last_four','trial_ends_at'
    ];

    protected $searchable = [
        'name', 'description'
    ];

    protected $visible = [
        'id', 'name', 'description', 'mission', 'profile_photo', 'categories',
        'distance', 'incentives_count', 'url', 'monthly_points', 'monthly_points_remaining', 'points', 'full_address', 'formatted_phone', 'formatted_website_url', 'initials'
    ];

    protected $appends = [
        'profile_photo', 'incentives_count', 'url', 'monthly_points_remaining', 'model', 'full_address', 'formatted_phone', 'formatted_website_url', 'initials'
    ];

    protected $presenter = 'App\Presenters\ForprofitPresenter';

    protected $dates = ['trial_ends_at'];

    # set name
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    # set email
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    # set website url
    public function setWebsiteUrlAttribute($value)
    {
        $this->attributes['website_url'] = addHttp(strtolower($value));
    }

    # get incentive count
    public function getIncentivesCountAttribute()
    {
        // check if auth
        return $this->incentives()->available()->count();
    }

    # get url
    public function getUrlAttribute()
    {
        return route('forprofits.show', $this->id);
    }

    # get monthly points remaining
    public function getMonthlyPointsRemainingAttribute()
    {
        if (!$this->monthly_points) {
            return 0;
        }

        // get remaining budget this month
        $remaining = $this->monthly_points - $this->monthly_points_spent;

        return ($remaining >0) ? $remaining : 0;
    }

    # get monthly points spent attribute
    # if only count fulfilled, donations need to count for month where fulfilled_at vs created at
    public function getMonthlyPointsSpentAttribute()
    {
        return $this->donations()
            ->where('created_at', '>', Carbon::now()->startOfMonth())
            // ->fulfilled()
            ->sum('points');
    }

    # total points
    public function getTotalPointsAttribute()
    {
        return floor($this->donations()->fulfilled()->sum('points'));
    }

    # get model attribute
    public function getModelAttribute()
    {
        return 'forprofit';
    }

    # get full address
    public function getFullAddressAttribute()
    {
        if ($this->has_location) {
            $address = $this->location_address;
            $address .= ($this->location_suite) ? ' #' . $this->location_suite : '';
            $address .= ', ' . $this->location_city . ', ' . $this->location_state . ', ' . $this->location_country;
            return trim($address, ' ,');
        }
        return null;
    }

    # get formatted phone
    public function getFormattedPhoneAttribute()
    {
        return format_phone($this->phone);
    }

    # get formatted website url
    public function getFormattedWebsiteUrlAttribute()
    {
        if (!$this->website_url) {
            return null;
        }
        return strtolower(parse_url($this->website_url, PHP_URL_HOST));
    }

    # initials
    public function getInitialsAttribute()
    {
        return strtoupper($this->name[0]);
    }

    # verified
    public function scopeVerified($query)
    {
        return $query->whereVerified(true);
    }

    # is verified
    public function isVerified()
    {
        return $this->verified ? true : false;
    }

    # set verified
    public function setVerified($value)
    {
        // first time
        if (!$this->verified_at && $value) {
            $this->verified = true;
            $this->verified_at = Carbon::now();
            $this->save();
        } else {
            $this->verified = ($value) ? true : false;
            $this->save();
        }
    }

    # ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('name', 'asc')->orderBy('id', 'desc');
    }

    # ordered by date
    public function scopeOrderedByDate($query)
    {
        return $query
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    # ordered by distance
    public function scopeOrderedByDistance($query)
    {
        return $query
            ->orderBy('distance', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    # employees
    public function employees()
    {
        return $this->morphToMany('App\Volunteer', 'employer', 'employees')
            ->withTimestamps();
    }

    # invitations
    public function invitations()
    {
        return $this->morphMany('App\Invitation', 'inviter');
    }

    # incentives
    public function incentives()
    {
        return $this->hasMany('App\Incentive');
    }

    # incentives
    public function incentivesForAdmin()
    {
        return $this->hasMany('App\Incentive');
    }

    # incentive purchases
    public function incentivePurchases()
    {
        return $this->hasMany('App\IncentivePurchase');
    }

    # total coupons sold
    public function getTotalCouponsSoldAttribute()
    {
        return $this->incentivePurchases()->count();
    }

    # donations
    public function donations()
    {
        return $this->morphMany('App\Donation', 'donater');
    }

    # has location
    public function getHasLocationAttribute()
    {
        return ($this->location) ? true : false;
    }

    # total hours attribute
    public function getTotalHoursAttribute()
    {
        return floor($this->employees()->sum('minutes') / 60);
    }

    # start trial
    public function startTrial($days = 7)
    {
        $this->trial_ends_at = Carbon::now()->addDays(7);
        $this->save();
    }
}
