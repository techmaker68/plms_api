<?php

namespace Modules\Visa\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidVisaStatus implements Rule
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
        $statuses = explode(',', $value);

        foreach ($statuses as $status) {
            if (!in_array(trim($status), config('visa.visa_statuses'))) {
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
        return 'The :attribute is not a valid visa status.';
    }
}
