<?php

namespace App\Http\Requests\Ticker;

use Illuminate\Foundation\Http\FormRequest;

class CreateTickerRequestWeb extends FormRequest
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
            'base_token_id' => 'required|string',
            'target_token_id' => 'required|string',
        ];
    }
}
