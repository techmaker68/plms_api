<?php

namespace Modules\Loi\Rules;

use Illuminate\Contracts\Validation\Rule;

class LoiTypeCheck implements Rule
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
        $loi_types = is_array($value) ? $value : explode(',', $value);
        
        foreach ($loi_types as $type) {
            if (!in_array($type, config('loi.loi_types'))) {
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