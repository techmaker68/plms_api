<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BooleanValue implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute  The attribute being validated.
     * @param  mixed  $value  The value of the attribute.
     * @return bool  Returns true if the validation rule passes, false otherwise.
     */
    public function passes($attribute, $value)
    {
        $acceptable = [true, false, 'true', 'false', 'yes', 'no'];
        return in_array(strtolower($value), $acceptable, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string  The validation error message.
     */
    public function message()
    {
        return 'The :attribute must be a boolean value (true, false, yes, no).';
    }
}
