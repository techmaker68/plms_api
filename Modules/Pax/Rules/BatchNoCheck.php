<?php

namespace Modules\Pax\Rules;

use Illuminate\Contracts\Validation\Rule;

class BatchNoCheck implements Rule
{
    protected $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->route) {
            return true;
        }
    
        switch ($this->route) {
            case 'blood_test':
                $model = 'Modules\BloodTest\Entities\BloodTest';
                break;
            case 'loi_request':
                $model = 'Modules\Loi\Entities\PLMSLoi';
                break;
            default:
                return true;
        }
    
        return $model::where($attribute, $value)->exists();
    }    

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute is invalid for the route {$this->route}.";
    }
}
