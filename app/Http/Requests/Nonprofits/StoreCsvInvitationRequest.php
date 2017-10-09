<?php

namespace App\Http\Requests\Nonprofits;

use Illuminate\Foundation\Http\FormRequest;

class StoreCsvInvitationRequest extends FormRequest
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
            'csv_import' => 'required|mimes:csv,txt',
        ];
    }
}
