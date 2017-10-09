<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Carbon\Carbon;

use App\Traits\SearchableTrait;
use App\Traits\HasCategories;
use App\Traits\HasLocationTrait;
use App\Traits\HasUploadedFilesTrait;
use App\Traits\HasLogoTrait;
use App\Traits\PresentableTrait;
use App\Traits\FollowableTrait;
use App\Volunteer;

class Nonprofit extends Model
{
    use PresentableTrait, SearchableTrait, FollowableTrait, HasCategories, HasLocationTrait, HasLogoTrait, HasUploadedFilesTrait, Billable;

    protected $fillable = [
        'name', 'description', 'mission', 'website_url', 'profile_photo_id', 'email', 'phone',
        'location', 'location_lat', 'location_lng', 'location_address', 'location_city', 'location_state',
        'location_country', 'location_postal_code', 'location_suite', 'file_501c3', 'tax_id',
        'stripe_id','name_on_cart',
        'card_brand','card_last_four','trial_ends_at'
    ];

    protected $searchable = [
        'name', 'description'
    ];

    protected $visible = [
        'id', 'name', 'description', 'mission', 'photo', 'categories', 'distance', 'url', 'profile_photo',
        'full_address', 'formatted_phone', 'formatted_website_url', 'opportunities_count', 'initials'
    ];

    protected $appends = ['url', 'profile_photo', 'full_address', 'formatted_phone',
        'formatted_website_url', 'opportunities_count', 'initials'];

    protected $presenter = 'App\Presenters\NonprofitPresenter';

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

    # get url
    public function getUrlAttribute()
    {
        return route('nonprofits.show', $this->id);
    }

    # get 501c3 url
    public function getFile501c3UrlAttribute()
    {
        $upload = FileUpload::findOrFail($this->file_501c3);
        return $upload->url;
    }

    # initials
    public function getInitialsAttribute()
    {
        return strtoupper($this->name[0]);
    }

    # verified
    public function scopeVerified($query)
    {
        return $query->whereVerified(1);
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

    # ordered
    public function scopeOrdered($query)
    {
        return $query
            ->orderBy('name', 'asc')
            ->orderBy('id', 'desc');
    }

    # has location
    public function getHasLocationAttribute()
    {
        return ($this->location) ? true : false;
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

    # get opportunities count attribute
    public function getOpportunitiesCountAttribute()
    {
        return $this->opportunities()->published()->notExpired()->count();
    }

    # opportunities
    public function opportunities()
    {
        return $this->hasMany('App\Opportunity');
    }

    # applications
    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    # employees
    public function employees()
    {
        return $this->morphToMany('App\Volunteer', 'employer', 'employees')
            ->withTimestamps();
    }

    # volunteers
    public function volunteers()
    {
        return $this->belongsToMany('App\Volunteer', 'hours')->groupBy('user_id');
    }

    # total volunteers
    public function getTotalVolunteersAttribute()
    {
        return count($this->volunteers()->get());
    }

    # invitations
    public function invitations()
    {
        return $this->morphMany('App\Invitation', 'inviter');
    }

    # hours
    public function hours()
    {
        return $this->hasMany('App\Hours');
    }

    # total hours
    public function getTotalHoursAttribute()
    {
        return floor($this->hours()->sum('minutes') / 60);
    }

    # donations
    public function donations()
    {
        return $this->hasMany('App\Donation');
    }

    # start trial
    public function startTrial($days = 7)
    {
        $this->trial_ends_at = Carbon::now()->addDays(7);
        $this->save();
    }
}
