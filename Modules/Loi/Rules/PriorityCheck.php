<?php

namespace Modules\Loi\Rules;

use Illuminate\Contracts\Validation\Rule;

class PriorityCheck implements Rule
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
        $priorities = is_array($value) ? $value : explode(',', $value);
        
        foreach ($priorities as $priority) {
            if (!in_array($priority, config('loi.priority_types'))) {
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
