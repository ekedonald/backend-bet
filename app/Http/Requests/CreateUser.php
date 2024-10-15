<?php

namespace App\Http\Requests;

use App\Http\Controllers\API\v1\ResponseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class CreateUser extends FormRequest
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
            'email' => 'required|unique:users',
            'first_name' => 'required|string',
            'referal' => 'nullable|string',
            'last_name' => 'required|string',
            'password' => 'required|min:6|alpha_num'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'This email address has been used before, Login to continue with your account',
        ];
    }

}
