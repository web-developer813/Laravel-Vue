<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Log;

abstract class Request extends FormRequest
{
    // protected function failedValidation(Validator $validator)
    // {
    //     // throw new ValidationException($this->response(
    //     //     $this->formatErrors($validator)
    //     // ));
    //     throw new ValidationException($validator);
    // }
    public function forbiddenResponse()
	{
		flash('You do not have permission to access this page', 'error');
		return redirect()->route('home');
	}
}
