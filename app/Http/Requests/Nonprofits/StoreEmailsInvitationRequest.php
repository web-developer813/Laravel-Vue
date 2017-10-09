<?php

namespace App\Http\Requests\Nonprofits;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmailsInvitationRequest extends FormRequest
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
            'emails' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'emails.required' => 'Please enter one email address per line',
        ];
    }
}
