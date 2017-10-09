<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Traits\SearchableTrait;

class IncentivePurchase extends Model
{
	use SearchableTrait, SoftDeletes;

	protected $searchable = ['title', 'summary', 'volunteer' => ['name']];

	protected $guarded = ['redeemed'];

	protected $appends = ['image', 'has_image', 'download_url', 'update_url', 'send_url', 'status'];

	protected $dates = ['expires_at'];

	# get download url
	public function getDownloadUrlAttribute()
	{
		return route('incentive-purchases.show', $this->id);
	}

	# get update url
	public function getUpdateUrlAttribute()
	{
		return route('api.forprofits.incentive-purchases.update', [$this->forprofit_id, $this->id]);
	}

	# get send url
	public function getSendUrlAttribute()
	{
		return route('api.volunteers.incentive-purchases.send', $this->id);
	}

	# get status
	public function getStatusAttribute()
	{
		if ($this->redeemed) return 'redeemed';
		elseif ($this->is_valid) return 'valid';
		elseif ($this->is_expired) return 'expired';
		return '';
	}

	# is valid
	public function getIsValidAttribute()
	{
		if ($this->redeemed) return false;

		if (!$this->expires_at) return true;

		return (strtotime($this->expires_at) > strtotime(Carbon::now())) ? true : false;

	}

	# is expired
	public function getIsExpiredAttribute()
	{
		if (!$this->expires_at) return false;

		return (strtotime($this->expires_at) < strtotime(Carbon::now())) ? true : false;
	}

	# set redeemed
	public function setRedeemed($value)
	{
		$this->redeemed = $value;
		$this->redeemed_at = ($value) ? Carbon::now() : null;
		$this->save();
	}

	# scope ordered
	public function scopeOrdered($query)
	{
		return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
	}

	# scope valid
	public function scopeValid($query)
	{
		return $query->whereRedeemed(0)
			->where(function($q) {
				$q->orWhereNull('expires_at');
				$q->orWhere('expires_at', '>', Carbon::now());
			});
	}

	# scope redeemed
	public function scopeRedeemed($query)
	{
		return $query->whereRedeemed(1);
	}

	# scope expired
	public function scopeExpired($query)
	{
		return $query->whereNotNull('expires_at')->where('expires_at', '<', Carbon::now());
	}

	# volunteer
	public function volunteer()
	{
		return $this->belongsTo('App\Volunteer');
	}

	# forprofit
	public function forprofit()
	{
		return $this->belongsTo('App\Forprofit');
	}

	# has image
	public function getHasImageAttribute()
	{
		return ($this->image_id) ? true : false;
	}

	# get image
	public function getImageAttribute()
	{
		return ($this->has_image) ? $this->image()->url : null;
	}

	# get image url
	public function getImageUrlAttribute()
	{
		return ($this->has_image) ? $this->image()->url : null;
	}

	# image
	protected function image()
	{
		return Photo::find($this->image_id);
	}

	# get barcode url
	public function getBarcodeUrlAttribute()
	{
		$upload = FileUpload::find($this->barcode);
		return ($upload) ? $upload->url : null;
	}
}
