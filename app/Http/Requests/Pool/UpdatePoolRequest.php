<?php

namespace App\Http\Requests\Pool;

use App\Http\Controllers\API\v1\ResponseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class UpdatePoolRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            ResponseController::response(false,[
                ResponseController::MESSAGE => "Please fill the form properly",
                'errors' => $validator->errors()->getMessages(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'end_price' => 'required|string',
        ];
    }
}
