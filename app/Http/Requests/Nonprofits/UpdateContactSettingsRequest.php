<?php

namespace App\Http\Requests\Nonprofits;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactSettingsRequest extends FormRequest
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
            'email' => 'required|email',
            'phone' => 'required',
            'location' => 'required',
            'file_501c3' => 'mimes:jpeg,png,pdf,doc,docx|max:20000',
            'tax_id' => 'required',
        ];
    }
}
