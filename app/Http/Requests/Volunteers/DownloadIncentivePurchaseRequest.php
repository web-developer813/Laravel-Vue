<?php

namespace App\Http\Requests\Volunteers;

use Illuminate\Foundation\Http\FormRequest;

use App\IncentivePurchase;

class DownloadIncentivePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $purchase = IncentivePurchase::findOrFail($this->route('incentive'));

        // volunteer
        if (current_mode('volunteer'))
            return $purchase->volunteer_id == auth()->user()->volunteer->id;

        // forprofit
        if (current_mode('forprofit'))
            return $purchase->forprofit_id == session()->get('auth-forprofit')->id;
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
