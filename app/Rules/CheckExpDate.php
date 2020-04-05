<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CheckExpDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if(preg_match('/^[0-9]{2}\/[0-9]{2}$/', $value) === 0)
        {
            return false;
        }

        return Carbon::createFromFormat('d/m/y','01/'.$value)->isAfter(Carbon::today());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Exp date is not valid or is not after today. Try this format mm/yy.';
    }
}
