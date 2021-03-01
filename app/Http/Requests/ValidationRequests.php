<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidationRequests extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): ValidationException {
        $errors = $validator->errors()->getMessages();
        if (!empty($errors)) {
            foreach ($errors as $field => $message) {
                $err_message = $message[0];
                break;
            }
        }

        throw new HttpResponseException(response()->json(setErrorResponse($err_message))
            ->setStatusCode(JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
