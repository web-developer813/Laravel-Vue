<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\PresentableTrait;
use Carbon\Carbon;

use App\Traits\SearchableTrait;
use App\Traits\HasCategories;
use App\Traits\LikableTrait;
use App\Traits\HasLocationTrait;
use App\Jobs\UploadPhotoToS3;
use App\Photo;
use App\Application;

class Opportunity extends Model
{
    use PresentableTrait, SearchableTrait, LikableTrait, HasCategories, HasLocationTrait, SoftDeletes;

    protected $table = 'opportunities';

    protected $fillable = [
        'title', 'description', 'image_id', 'nonprofit_id',
        'location', 'location_lat', 'location_lng', 'location_address', 'location_city', 'location_state', 'location_country', 'location_postal_code', 'location_suite',
        'flexible', 'start_date', 'end_date', 'hours_estimate',
        'contact_name', 'contact_email', 'contact_phone',
        'virtual', 'published', 'max_accepted_applicant'
    ];

    protected $searchable = [
        'title', 'description'
    ];

    protected $visible = [
        'id', 'title', 'description', 'start_date', 'end_date', 'duration', 'hours_estimate', 'virtual', 'created_at', 'categories',
        'nonprofit', 'distance', 'image_url', 'has_image', 'excerpt', 'url', 'published_at', 'has_dates', 'has_multiple_dates',
        'has_location', 'full_address', 'flexible_dates_label', 'remote_location_label', 'formatted_dates', 'published', 'short_location',
        'expired', 'max_accepted_applicant', 'closed',
    ];

    protected $appends = [
        'image_url', 'has_image', 'excerpt', 'url', 'has_dates', 'has_multiple_dates', 'has_location', 'full_address', 'flexible_dates_label',
        'remote_location_label', 'formatted_dates', 'short_location', 'expired', 'closed',
    ];

    protected $casts = [
        'virtual' => 'boolean',
    ];

    protected $presenter = 'App\Presenters\OpportunityPresenter';

    protected $dates = ['start_date', 'end_date'];

    # set virtual
    public function setVirtualAttribute($value)
    {
        $this->attributes['virtual'] = ($value) ? true : false;
    }

    # set start_date
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = ($value) ?: null;
    }

    # set end_date
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = ($value) ?: null;
    }

    # has dates
    public function getHasDatesAttribute()
    {
        return ($this->start_date) ? true : false;
    }

    # get excerpt attribute
    public function getExcerptAttribute()
    {
        return excerpt($this->description, 125);
    }

    # get url attribute
    public function getUrlAttribute()
    {
        return route('opportunities.show', $this->id);
    }

    # has location
    public function getHasLocationAttribute()
    {
        return ($this->location) ? true : false;
    }

    # get full address
    public function getFullAddressAttribute()
    {
        if ($this->has_location)
        {
            $address = $this->location_address;
            $address .= ($this->location_suite) ? ' #' . $this->location_suite : '';
            $address .= ', ' . $this->location_city . ', ' . $this->location_state;
            return trim($address, ' ,');
        }
        return null;
    }

    # get short location
    public function getShortLocationAttribute()
    {
        if ($this->has_location)
        {
            return $this->location_city . ', ' . $this->location_state;
        }
        return null;
    }

    # multiple dates boolean
    public function getHasMultipleDatesAttribute()
    {
        return ($this->start_date && $this->end_date && ($this->start_date != $this->end_date))
            ? true
            : false;
    }

    # get formatted dates attributes
    public function getFormattedDatesAttribute()
    {
        if (!$this->has_dates) return null;

        $dates = $this->start_date->format('D M jS, Y');
        if ($this->has_multiple_dates)
            $dates .= " &mdash; {$this->end_date->format('D M jS, Y')}";

        return $dates;
    }

    # get flexible dates label
    public function getFlexibleDatesLabelAttribute()
    {
        return "It's flexible! We'll work with your schedule.";
    }

    # get remote location label
    public function getRemoteLocationLabelAttribute()
    {
        return "Can be done remotely.";
    }

    # get expired attribute
    public function getExpiredAttribute()
    {
        if (!$this->published || !$this->has_dates) return false;
        return (strtotime($this->end_date) >= strtotime(Carbon::now()->toDateString())) ? false : true;
    }

    # not expired scope
    public function scopeNotExpired($query)
    {
        $query->where(function($q) {
            $q->where('end_date', '>=', Carbon::now()->toDateString());
            $q->orWhere('flexible', 1);
        });
    }

    # get closed attribute
    public function getClosedAttribute()
    {
        if (!$this->max_accepted_applicant) {
            return false;
        }

        $quantityApplicantAccepted = Application::where('opportunity_id', $this->id)
            ->where('accepted', true)
            ->count();
        if ($this->max_accepted_applicant <= $quantityApplicantAccepted) {
            return true;
        }
        return false;
    }

    # ordered
    public function scopeOrderedByCreationDate($query)
    {
        return $query
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    # ordered
    public function scopeOrderedByDistance($query)
    {
        return $query
            ->orderBy('distance', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('title', 'asc')
            ->orderBy('id', 'desc');
    }

    # ordered by start date
    public function scopeOrderedByStartDate($query)
    {
        return $query
            ->orderBy('start_date', 'desc')
            ->orderBy('end_date', 'desc')
            ->orderBy('id', 'asc');
    }

    # published
    public function scopePublished($query)
    {
        return $query->wherePublished(1);
    }

    # nonprofit
    public function nonprofit()
    {
        return $this->belongsTo('App\Nonprofit');
    }

    # volunteers
    public function volunteers()
    {
        return $this->belongsToMany('App\Volunteer', 'applications')
            ->withPivot('accepted', 'reviewed_at')
            ->withTimestamps()
            ->where('applications.accepted', 1)
            ->orderBy('applications.created_at', 'desc');
    }

    # applications
    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    # pending applications
    public function pendingApplications()
    {
        return $this->applications()->pending()->get();
    }

    # has photo
    public function hasImage()
    {
        return ($this->image_id) ? true : false;
    }

    # has photo
    public function getHasImageAttribute()
    {
        return ($this->image_id) ? true : false;
    }

    # get image
    public function getImageAttribute()
    {
        return ($this->hasImage()) ? $this->image()->url : null;
    }

    # get image
    public function getImageUrlAttribute()
    {
        return ($this->hasImage()) ? $this->image()->url : null;
    }

    # image
    protected function image()
    {
        return Photo::find($this->image_id);
    }

    # update image
    public function updateImage($file) {
        // no photo
        if (!$file) return true;

        // create new upload
        $photo = Photo::create([
            'key' => 'photos/' . md5(get_class($this) . $this->id) . '/' . md5(str_random(10) . time()) . '.jpg',
            'photoable_type' => get_class($this),
            'photoable_id' => $this->id
        ]);

        // upload to s3
        dispatch(new UploadPhotoToS3($photo, $file, 1024, 576));

        //  update opportunity
        $this->update(['image_id' => $photo->id]);
    }

    # is published
    public function isPublished()
    {
        return $this->published ? true : false;
    }

    # set published
    public function setPublished($value)
    {
        $nonprofit = $this->nonprofit;

        // first time
        if (!$this->published_at && $value && $nonprofit->verified) {
            $this->published = true;
            $this->published_at = Carbon::now();
            $this->save();
        } 
        else
        {
            $this->published = ($value && $nonprofit->verified) ? true : false;
            $this->save();
        }
    }
}