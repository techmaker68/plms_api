<?php

namespace Modules\Loi\Rules;

use Illuminate\Contracts\Validation\Rule;

class LoiStatusCheck implements Rule
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
        $statuses = is_array($value) ? $value : explode(',', $value);
        
        foreach ($statuses as $status) {
            if (!in_array($status, config('loi.statuses'))) {
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
        return "The selected :attribute is invalid.";
    }
}
