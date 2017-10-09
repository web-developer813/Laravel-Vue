<?php

namespace App\Http\Requests\Volunteers;

use App\Http\Requests\Request;

class UpdateAccountSettingsRequest extends Request
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
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'min:8',
        ];
    }
}
