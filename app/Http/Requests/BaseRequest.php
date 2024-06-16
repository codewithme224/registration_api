<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $first_error = $validator->errors()->first();
        throw new HttpResponseException(response()->unprocessable($first_error, $errors));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->forbidden('You are not authorized to perform this action.'));
    }
}
