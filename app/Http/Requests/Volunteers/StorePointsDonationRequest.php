<?php

namespace App\Http\Requests\Volunteers;

use Illuminate\Foundation\Http\FormRequest;

class StorePointsDonationRequest extends FormRequest
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
            'points' => 'integer|min:1',
        ];
    }
}
