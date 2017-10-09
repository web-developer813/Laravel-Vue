<?php

namespace App\Http\Requests\Nonprofits;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileSettingsRequest extends FormRequest
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
            'photo' => 'mimes:jpeg,png|max:20000|dimensions:min_width=200,min_height=200',
            'file_501c3' => 'mimes:jpeg,png,pdf,doc,docx|max:20000',
        ];
    }

    public function messages()
    {
        return [
            'photo.dimensions' => 'The photo must be at least 200 x 200 pixels.'
        ];
    }
}
