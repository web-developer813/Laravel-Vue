<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateForprofitRequest extends FormRequest
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
            'photo' => 'mimes:jpeg,png|max:2000|dimensions:min_width=200,min_height=200',
            'email' => 'required|email',
            'phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'dimensions' => 'The photo must be at least 200 x 200 pixels.'
        ];
    }
}
