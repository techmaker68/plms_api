<?php

namespace Modules\Pax\Rules;

use Illuminate\Contracts\Validation\Rule;

class PaxStatusCheck implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $statuses = explode(',', $value);
        
        foreach ($statuses as $status) {
            if (!in_array($status, config('pax.pax_statuses'))) {
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
        return "The :attribute is invalid. Only '1' or '2' statuses are allowed.";
    }
}
