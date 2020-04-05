<?php

namespace App\Rules;

use App\Card;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CheckExpDateCard implements Rule
{

    private $card;

    /**
     * Create a new rule instance.
     *
     * @param int $cardId
     */
    public function __construct(int $cardId)
    {
        $this->card = Card::find($cardId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        return Carbon::createFromFormat('d/m/y H:i:s','01/'.$value.' 00:00:00')->eq(Carbon::parse($this->card->exp_date));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The exp data of '.$this->card->number.' card is not valid.';
    }
}
