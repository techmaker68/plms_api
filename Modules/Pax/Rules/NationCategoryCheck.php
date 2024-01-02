<?php

namespace Modules\Pax\Rules;

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
        $categories = explode(',', $value);

        foreach ($categories as $category) {
            if (!in_array(trim($category), $this->allowedNationCategories())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Allowed routes list
     *
     * @return array
     */
    private function allowedNationCategories(): array
    {
        return [
            'syrian',
            'arab',
            'foreign'
        ];
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
