<?php

namespace App\Http\Requests\Nonprofits;

use App\Http\Requests\Request;

class UpdateOpportunityRequest extends Request
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
            'title' => 'required',
            'description' => 'required',
            'photo' => 'mimes:jpeg,png|max:20000|dimensions:min_width=1024,min_height=576',
            'categories' => 'array|max_array_size:3',
            'start_date' => 'date',
            'end_date' => 'date',
            'hours_estimate' => 'integer',
            'contact_email' => 'email',
            'location' => 'required_without:virtual',
            'start_date' => 'required_without:flexible',
        ];
    }

    public function messages()
    {
        return [
            'photo.dimensions' => 'The photo must be at least 1024 x 576 pixels.',
            'location.required_without' => 'Please enter a valid address',
        ];
    }
}
