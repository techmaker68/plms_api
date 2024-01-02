<?php

namespace Modules\BloodTest\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidAttendenceType implements Rule
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
        $types = explode(',', $value);

        foreach ($types as $type) {
            if (!in_array(trim($type), config('BloodTest.attendence_types'))) {
                return false;
            }
        }
        return true;
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
