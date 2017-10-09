<?php

namespace App\Http\Requests\Volunteers;

use App\Http\Requests\Request;

class StoreNonprofitRequest extends Request
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
            'name' => 'required',
            'location' => 'required',
            'photo' => 'file|mimes:jpeg,png|max:20000|dimensions:min_width=200,min_height=200',
            'categories' => 'array|max_array_size:3',
            'file_501c3' => 'file|mimes:jpeg,png,pdf,doc,docx|max:20000',
            'email' => 'required|email',
            'phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'photo.dimensions' => 'The photo must be at least 200 x 200 pixels.'
        ];
    }
}
