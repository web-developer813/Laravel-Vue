<?php

namespace App\Http\Requests\Nonprofits;

use Illuminate\Foundation\Http\FormRequest;

class StoreHoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'opportunity' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'date',
            'hours' => 'required|integer|min:0|max:1000',
            'minutes' => 'required|integer|min:0',
            'volunteers' => 'required|array',
            'volunteers.*' => 'exists:volunteers,id'
        ];
    }
}
