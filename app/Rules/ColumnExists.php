<?php

namespace App\Rules;

use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Validation\Rule;

class ColumnExists implements Rule
{
    protected $table;
    protected $value;

    /**
     * Create a new rule instance.
     *
     * @param  string  $table  The table name to check for column existence.
     * @return void
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute  The attribute being validated.
     * @param  mixed  $value  The value of the attribute.
     * @return bool  Returns true if the column exists, false otherwise.
     */
    public function passes($attribute, $value)
    {
        $this->value = $value;
        return Schema::hasColumn($this->table, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string  The error message.
     */
    public function message()
    {
        return "The :attribute value '{$this->value}' does not exist as a column in the '{$this->table}' table.";
    }
}
