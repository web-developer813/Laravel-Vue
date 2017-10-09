<?php

namespace App\Http\Requests\App;

use App\Http\Requests\Request;

class RegisterUserRequest extends Request
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your full name',
            'email.required' => 'Please enter a valid email',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'This email is already in use, please log in',
            'password.required' => 'Please enter a password',
            'password.min' => 'Your password must be at least :min characters'
        ];
    }
}
