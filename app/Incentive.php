<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Jobs\UploadPhotoToS3;
use App\Traits\PresentableTrait;
use App\Traits\HasUploadedFilesTrait;
use App\Traits\SearchableTrait;
use App\Photo;
use Carbon\Carbon;

class Incentive extends Model
{
	use PresentableTrait, HasUploadedFilesTrait, SearchableTrait, SoftDeletes;

	protected $searchable = ['title', 'description', 'summary'];

	protected $presenter = 'App\Presenters\IncentivePresenter';

	protected $guarded = [];

	protected $appends = ['url', 'update_url', 'delete_url', 'has_image', 'image', 'excerpt'];

	# get terms (add days to use at the beginning)
	public function getTermsWithExpirationAttribute()
	{
		if (!$this->days_to_use) return $this->terms;

		$days_to_use = $this->days_to_use . ' ' . str_plural('day', $this->days_to_use);
		$days_terms = "Promotional value expires $days_to_use after purchase.";

		return ($this->terms)
			? $days_terms . ' ' . $this->terms
			: $days_terms;
	}

	# URL
	public function getUrlAttribute()
	{
		return route('incentives.show', $this->id);
	}

	# URL
	public function getEditUrlAttribute()
	{
		return route('forprofits.incentives.edit', [$this->forprofit_id, $this->id]);
	}

	# update URL
	public function getUpdateUrlAttribute()
	{
		return route('api.forprofits.incentives.update', [$this->forprofit_id, $this->id]);
	}

	# delete URL
	public function getDeleteUrlAttribute()
	{
		return route('api.forprofits.incentives.delete', [$this->forprofit_id, $this->id]);
	}

	# purchase URL
	public function getPurchasesUrlAttribute()
	{
		return route('forprofits.incentives.purchases', [$this->forprofit_id, $this->id]);
	}

	# get barcode url
	public function getBarcodeUrlAttribute()
	{
		$upload = FileUpload::find($this->barcode);
		return ($upload) ? $upload->url : null;
	}

	# excerpt
	public function getExcerptAttribute()
	{
		return excerpt($this->description, 125);
	}

	# set percentage off
	public function setPercentOffAttribute($value)
	{
		$this->attributes['percent_off'] = ($value) ?: null;
	}

	# set amount off
	public function setAmountOffAttribute($value)
	{
		$this->attributes['amount_off'] = ($value) ?: null;
	}

	# set days to use
	public function setDaysToUseAttribute($value)
	{
		$this->attributes['days_to_use'] = ($value) ?: null;
	}

	# set quantity
	public function setQuantityAttribute($value)
	{
		$this->attributes['quantity'] = ($value) ?: null;
	}

	# scope ordered
	public function scopeOrdered($query)
	{
		return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
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

	# scope active
	public function scopeActive($query)
	{
		return $query->whereActive(1);
	}

	# scope quantity
	public function scopeQuantity($query)
	{
		return $query->where(function($q) {
			$q->where('quantity', '>', 0);
			$q->orWhereNull('quantity');
		});
	}

	# scope available
	public function scopeAvailable($query)
	{
		// if auth
		if (auth()->check())
		{
			$forprofit_ids = auth()->user()->forprofits->pluck('id');

			if (count($forprofit_ids))
			{
				return $query->active()->quantity()->where(function($q) use ($forprofit_ids) {
					$q->whereEmployeeSpecific(0);
					$q->orWhere(function($q2) use ($forprofit_ids) {
						$q2->whereEmployeeSpecific(1);
						$q2->whereIn('forprofit_id', $forprofit_ids);
					});
				});
			}
		}

		$query->whereEmployeeSpecific(0)->active()->quantity();
	}

	# forprofit
	public function forprofit()
	{
		return $this->belongsTo('App\Forprofit');
	}

	# purchases
	public function purchases()
	{
		return $this->hasMany('App\IncentivePurchase');
	}

	public function scopeAvailablePurchase()
    {
        $quantity = $this->quantity;
        $case = $this->case;
        $incentive_id = $this->id;
        $totalAvailable = 0;
        $numberSold = 0;

        if (!empty($quantity)) {
            switch($case) {
                case 'daily':
                    $numberSold = count(IncentivePurchase::whereDate('created_at', '=', Carbon::today()->toDateTimeString())->where('incentive_id', '=' , $incentive_id)->get());
                    $totalAvailable = $quantity - $numberSold;
                    break;
                case 'weekly' :
                    $startWeek = Carbon::now()->startOfWeek();
                    $endWeek = Carbon::now()->endOfWeek();
                    $numberSold = count(IncentivePurchase::whereDate('created_at', '>= ', $startWeek->toDateTimeString())->whereDate('created_at', '<=', $endWeek->toDateTimeString())->where('incentive_id', '=' , $incentive_id)->get());
                    $totalAvailable = $quantity - $numberSold;
                    break;
                case 'monthly' :
                    $startMonth = Carbon::now()->startOfMonth();
                    $endMonth = Carbon::now()->endOfMonth();
                    $numberSold = count(IncentivePurchase::whereDate('created_at', '>= ', $startMonth->toDateTimeString())->whereDate('created_at', '<=', $endMonth->toDateTimeString())->where('incentive_id', '=' , $incentive_id)->get());
                    $totalAvailable = $quantity - $numberSold;
                    break;
                case 'yearly' :
                    $startYear = Carbon::now()->startOfYear();
                    $endYear = Carbon::now()->endOfYear();
                    $numberSold = count(IncentivePurchase::whereDate('created_at', '>= ', $startYear->toDateTimeString())->whereDate('created_at', '<=', $endYear->toDateTimeString())->where('incentive_id', '=' , $incentive_id)->get());
                    $totalAvailable = $quantity - $numberSold;
                    break;
                case 'flat':
                    $totalAvailable = $quantity;
                    break;
                default:
                    echo "Your number sold is empty";

            }
        }


        if($totalAvailable <=0 ){
            return  ' 0 ';
        }else{
            return $totalAvailable;
        }

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

	# set active
	public function setActive($value)
	{
		$forprofit = $this->forprofit;
		$this->active = ($value && $forprofit->verified) ? true : false;
		$this->save();
	}
}
