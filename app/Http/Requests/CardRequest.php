<?php

namespace App\Http\Requests;

use App\Rules\CheckCardNumber;
use App\Rules\CheckExpDate;
use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
        return [
            'number' => ['required','integer','digits:16','unique:cards,number', new CheckCardNumber()],
            'exp_date' => ['required', new CheckExpDate()],
            'cvv' =>'required|integer|digits:3',
        ];
    }
}
