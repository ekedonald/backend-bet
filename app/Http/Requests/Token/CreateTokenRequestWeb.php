<?php

namespace App\Http\Requests\Token;

use Illuminate\Foundation\Http\FormRequest;

class CreateTokenRequestWeb extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'symbol' => 'required|string',
            'tokenObject' => 'nullable|string',
        ];
    }
}
