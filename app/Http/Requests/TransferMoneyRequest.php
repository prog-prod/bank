<?php

namespace App\Http\Requests;

use App\Rules\CheckCvv;
use App\Rules\CheckExpDateCard;
use App\Rules\CheckExpDateFormat;
use Illuminate\Foundation\Http\FormRequest;

class TransferMoneyRequest extends FormRequest
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
            'user_from' => 'required|integer|exists:App\User,id',
            'user_to' => 'required|integer|exists:App\User,id|different:user_from',
            'card_from' => 'required|integer|exists:App\Card,id',
            'card_to' => 'required|integer|exists:App\Card,id|different:card_from',
            'exp_date_from' => ['required', new CheckExpDateFormat(), new CheckExpDateCard($this->card_from)],
            'exp_date_to' => ['required', new CheckExpDateFormat(), new CheckExpDateCard($this->card_to)],
            'cvv_from' => ['required','integer', 'digits:3', new CheckCvv($this->card_from)],
            'cvv_to' => ['required','integer', 'digits:3', new CheckCvv($this->card_to)],
            'amount' => 'required|integer|min:1',
        ];
    }
}
