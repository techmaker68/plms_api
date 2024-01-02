<?php

namespace Modules\Loi\Rules;

use Illuminate\Contracts\Validation\Rule;

class NationCategoryCheck implements Rule
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
        $nation_categories = is_array($value) ? $value : explode(',', $value);
        
        foreach ($nation_categories as $category) {
            if (!in_array($category, config('loi.nation_categories'))) {
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
