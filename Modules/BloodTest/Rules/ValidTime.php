<?php

namespace Modules\BloodTest\Rules;

use DateTime;
use Illuminate\Contracts\Validation\Rule;

class ValidTime implements Rule
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

    public function passes($attribute, $value)
    {
        $time = DateTime::createFromFormat('H:i:s', $value);

        return $time !== false && $time->format('H:i:s') === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not a valid attendence type.';
    }
}
