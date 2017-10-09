<?php

namespace App\Http\Requests\Forprofits;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncentiveRequest extends FormRequest
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

        $rules = array();

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'summary' => 'required|max:300',
            'photo' => 'file|mimes:jpeg,png|max:20000|dimensions:min_width=512,min_height=288',
            'barcode' => 'file|mimes:jpeg,png|max:20000',
            'days_to_use' => 'integer',
            'how_to_use' => 'required',
            'price' => 'required|integer|min:0',
            'tag' => 'required',
        ];
        if($this->input('case') != ""){
            $rules['quantity'] = 'required|integer|min:0';
        }
        return $rules;
    }
}
