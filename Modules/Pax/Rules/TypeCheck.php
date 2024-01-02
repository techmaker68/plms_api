<?php

namespace Modules\Pax\Rules;

use Illuminate\Contracts\Validation\Rule;

class TypeCheck implements Rule
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
        $types = explode(',', $value);
        foreach ($types as $type) {
            if (!in_array(trim($type), config('pax.pax_types'))) {
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
