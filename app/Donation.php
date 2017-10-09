<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\SearchableTrait;
use Carbon\Carbon;

class Donation extends Model
{
    use SearchableTrait, SoftDeletes;

    protected $fillable = ['amount','donater_type','donater_name','donater_id','nonprofit_name','nonprofit_id'
    ,'points','fulfilled','fulfiled_in_cash'];

    protected $searchable = ['donater_name', 'nonprofit' => ['name']];

    protected $guarded = [];

    protected $appends = ['edit_url', 'status', 'css_status'];

    # edit url
    public function getEditUrlAttribute()
    {
        return route('forprofits.donations.edit', [$this->donater->id, $this->id]);
    }

    # status
    public function getStatusAttribute()
    {
        return ($this->fulfilled) ? 'completed' : 'pending';
    }

    # status
    public function getCssStatusAttribute()
    {
        return ($this->fulfilled) ? 'positive' : 'neutral';
    }

    # donater
    public function donater()
    {
        return $this->morphTo();
    }

    # nonprofit
    public function nonprofit()
    {
        return $this->belongsTo('App\Nonprofit');
    }

    # scope ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }

    # scope fulfilled
    public function scopeFulfilled($query)
    {
        return $query->whereFulfilled(1);
    }

    # scope pending
    public function scopePending($query)
    {
        return $query->whereFulfilled(0);
    }

    # set fulfilled
    public function setFulfilled($value)
    {
        // first time
        if (!$this->fulfilled_at && $value) {
            $this->fulfilled = true;
            $this->fulfilled_at = Carbon::now();
            $this->save();
        } else {
            $this->fulfilled = ($value) ? true : false;
            $this->save();
        }

        // forprofits points
        if ($this->donater_type == 'App\Forprofit') {
            $forprofit = $this->donater;
            if ($value) {
                $forprofit->points = $forprofit->points + $this->points;
            } else {
                $forprofit->points = $forprofit->points - $this->points;
            }

            $forprofit->save();
        }
    }
}
