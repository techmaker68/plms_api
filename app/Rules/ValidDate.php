<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * This method is called when a new instance of the ValidDate rule is created.
     * It can be used to perform any initialization tasks.
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
     * This method is called to check if the given value passes the validation rule.
     * It checks if the value can be parsed as a valid date in the format 'Y-m-d'.
     *
     * @param  string  $attribute  The name of the attribute being validated.
     * @param  mixed  $value  The value of the attribute being validated.
     * @return bool  Returns true if the validation rule passes, false otherwise.
     */
    public function passes($attribute, $value)
    {
        return (bool) date_create_from_format('Y-m-d', $value);
    }

    /**
     * Get the validation error message.
     *
     * This method is called to get the error message when the validation rule fails.
     * It returns a string that describes the validation error.
     *
     * @return string  The validation error message.
     */
    public function message()
    {
        return 'The :attribute is not a valid date. The format should be Y-m-d.';
    }
}
