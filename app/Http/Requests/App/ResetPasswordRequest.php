<?php

namespace App\Http\Requests\App;

use App\Http\Requests\Request;

class ResetPasswordRequest extends Request
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
            'resetToken' => 'required|exists:password_resets,token',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ];
    }
}
