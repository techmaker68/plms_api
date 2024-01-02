<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistsInModel implements Rule
{
    protected $model; // The model class name
    protected $key; // The key to check against in the model

    /**
     * Create a new rule instance.
     *
     * @param  string  $model  The model class name
     * @param  string  $key  The key to check against in the model
     * @return void
     */
    public function __construct(string $model, string $key)
    {
        $this->model = $model;
        $this->key = $key;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute  The attribute being validated
     * @param  mixed  $value  The value of the attribute
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $model = resolve($this->model); // Resolve the model instance

        $values = is_array($value) ? $value : explode(',', $value); // Convert the value to an array if it's not already

        foreach ($values as $val) {
            if (!$model::where($this->key, $val)->exists()) { // Check if the value exists in the model
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
        return 'The :attribute does not exist.';
    }
}