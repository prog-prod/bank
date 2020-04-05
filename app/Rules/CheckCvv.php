<?php

namespace App\Rules;

use App\Card;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CheckCvv implements Rule
{

    private $card;

    /**
     * Create a new rule instance.
     *
     * @param int $cardId
     */
    public function __construct(int $cardId)
    {$this->card = Card::find($cardId);

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
        return Hash::check($value, $this->card->cvv);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The CVV of '.$this->card->number.' card is not valid.';
    }
}
