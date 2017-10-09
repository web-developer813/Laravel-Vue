<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

use App\Traits\PresentableTrait;
use App\Traits\HasLocationTrait;
use App\Traits\ProfilePhotoTrait;
use App\Traits\HasUploadedFilesTrait;
use App\Traits\SearchableTrait;
use App\Traits\FollowableTrait;
use App\Friendships\Traits\Friendable;
use Cmgmyr\Messenger\Traits\Messagable;

class Volunteer extends Model
{
    use PresentableTrait, HasLocationTrait, Messagable, ProfilePhotoTrait, HasUploadedFilesTrait, Notifiable, SearchableTrait, FollowableTrait, Billable, Friendable, SoftDeletes;

    protected $searchable = ['name', 'description', 'location'];

    protected $fillable = [
        'name', 'description', 'profile_photo_id', 'username', 'location',
        'location_lat', 'location_lng', 'resume', 'status', 'trial_ends_at', 'twilio_id','stripe_id','name_on_cart',
        'card_brand','card_last_four','trial_ends_at'
    ];

    protected $presenter = 'App\Presenters\VolunteerPresenter';

    protected $visible = [
        'id', 'name', 'description', 'username', 'firstname', 'lastname', 'location', 'profilePhoto', 'profile_photo', 'status', 'twilio_id', 'url', 'initials','stripe_id'
    ];

    protected $appends = ['profilePhoto', 'profile_photo', 'firstname', 'lastname', 'url', 'model', 'initials'];

    protected $dates = ['trial_ends_at'];

    # set name
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    # get username
    public function getUsernameAttribute()
    {
        return $this->id;
    }

    # get firstname
    public function getFirstnameAttribute()
    {
        return explode(' ', $this->name)[0];
    }

    # get lastname
    public function getLastnameAttribute()
    {
        if (count(explode(' ', $this->name)) > 1) {
            return explode(' ', $this->name)[1];
        } else {
            return 'none';
        }
    }

    # get url
    public function getUrlAttribute()
    {
        return route('volunteers.show', $this->id);
    }

    # get resume url
    public function getResumeUrlAttribute()
    {
        $upload = FileUpload::findOrFail($this->resume);
        return $upload->url;
    }

    # get model attribute
    public function getModelAttribute()
    {
        return 'volunteer';
    }

    # get email attribute
    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    # initials
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    # email for notifications
    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    # get nonprofit roles
    public function nonprofitRoles($nonprofit_id)
    {
        return $this->belongsToMany('App\Role', 'employees')
            ->whereEmployerType('App\Nonprofit')
            ->whereEmployerId($nonprofit_id)
            ->withTimestamps();
    }

    # get nonprofit role
    public function nonprofitRole($nonprofit_id)
    {
        return $this->nonprofitRoles($nonprofit_id)->first();
    }

    # get forprofit roles
    public function forprofitRoles($forprofit_id)
    {
        return $this->belongsToMany('App\Role', 'employees')
            ->whereEmployerType('App\Forprofit')
            ->whereEmployerId($forprofit_id)
            ->withTimestamps();
    }

    # get forprofit role
    public function forprofitRole($forprofit_id)
    {
        return $this->forprofitRoles($forprofit_id)->first();
    }

    # scope ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('name', 'asc')->orderBy('created_at', 'desc');
    }

    # has location
    public function hasLocation()
    {
        return ($this->location && $this->location_lat && $this->location_lng) ? true : false;
    }

    # user
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # categories
    public function categories()
    {
        return $this->belongsToMany('App\Category')->ordered()->distinct();
    }

    # opportunities
    public function opportunities()
    {
        return $this->belongsToMany('App\Opportunity', 'applications')->with('accepted', 'reviewed_at')->withTimestamps();
    }

    # applications
    public function applications()
    {
        return $this->hasMany('App\Application')->orderBy('created_at', 'desc');
    }

    # has applied for opportunity
    public function hasAppliedForOpportunity($id)
    {
        return ($this->applications()->whereOpportunityId($id)->exists()) ? true : false;
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

    # incentive purchases
    public function incentivePurchases()
    {
        return $this->hasMany('App\IncentivePurchase');
    }

    # donations
    public function donations()
    {
        return $this->morphMany('App\Donation', 'donater');
    }

    # total points donations
    public function getTotalPointsDonationsAttribute()
    {
        return floor($this->donations()->sum('points'));
    }

    # start trial
    public function startTrial($days = 7)
    {
        $this->trial_ends_at = Carbon::now()->addDays(7);
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }

    # get this volunteers likes
    public function likes()
    {
        return $this->hasMany('App\Like', 'user_id');
    }

    # get profile photo
    public function photo()
    {
        return $this->hasOne(Photo::class, 'id', 'profile_photo_id');
    }

    #skill
    public function skills()
    {
        return $this->hasMany('App\Skill');
    }
}
