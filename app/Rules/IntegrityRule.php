<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

//custom validation rule to check if data exists
class IntegrityRule implements ImplicitRule
{
    protected $type;
    protected $table;
    protected $column;
    protected $id_to_ignore;

    public function __construct($type, $table, $column, $id_to_ignore = null)
    {
        $this->type = $type;
        $this->table = $table;
        $this->column = $column;
        $this->id_to_ignore = $id_to_ignore;
    }

    public function passes($attribute, $value)
    {
        if ($this->column === 'phone') {
            $value = ltrim($value, '0');
        }

        $query = DB::table($this->table)
            ->where($this->column, $value)
            ->whereNotNull($this->column)
            ->whereNull('deleted_at');

        if ($this->id_to_ignore) {
            $query->where('id', '!=', $this->id_to_ignore);
        }

        if ($this->type === 'exists') {
            return $query->exists() || $value == null;
        } elseif ($this->type === 'unique') {
            return !$query->exists();
        }

        return false;
    }


    public function message()
    {
        if ($this->type === 'exists') {
            return 'The provided :attribute is invalid.';
        } elseif ($this->type === 'unique') {
            return 'The provided :attribute is already in use.';
        }
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, $fail): void
    {
        //
    }
}
