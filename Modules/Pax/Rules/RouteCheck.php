<?php

namespace Modules\Pax\Rules;

use Illuminate\Contracts\Validation\Rule;

class RouteCheck implements Rule
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
        return in_array($value, $this->allowedRoutes());
    }

    /**
     * Allowed routes list
     *
     * @return array
     */
    private function allowedRoutes(): array
    {
        return [
            'blood_test',
            'loi_request',
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
