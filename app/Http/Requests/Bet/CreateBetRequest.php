<?php

namespace App\Http\Requests\Bet;

use App\Http\Controllers\API\v1\ResponseController;
use App\Services\AppConfig;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class CreateBetRequest extends FormRequest
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
            'pool_id' => 'required|string',
            'bet_price' => 'required|numeric',
            'choice_outcome' => 'required|string',
            'choice' => 'required|in:' . implode(',', [
                AppConfig::BET_BASE_DOWN,
                AppConfig::BET_BASE_UP,
                AppConfig::BET_TARGET_DOWN,
                AppConfig::BET_TARGET_UP
            ]),
        ];
    }
}
