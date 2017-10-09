<?php

namespace App\Helpers\Services;

class ValidationService {
    public function max_array_size($attribute, $value, $parameters, $validator){
    	return count($value) <= $parameters[0];
    }
}