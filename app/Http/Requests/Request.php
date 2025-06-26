<?php

namespace App\Http\Requests;

use App\Helpers\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

/**
 * Class Request
 * @package App\Http\Requests
 */
class Request extends FormRequest
{
    use Responses;

    public function failedValidation(Validator $validator)
    {
        $this->exceptionResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
