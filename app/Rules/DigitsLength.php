<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DigitsLength implements Rule
{
    protected $length;

    /**
     * Create a new rule instance.
     *
     * @param  int  $length
     * @return void
     */
    public function __construct($length)
    {
        $this->length = $length;
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
        if (is_null($value)) {
            return true; // Allow null values
        }
        return is_numeric($value) && strlen((string) $value) <= $this->length;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a numeric value with a maximum of ' . $this->length . ' digits.';
    }
}