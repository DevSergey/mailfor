<?php
namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
class EntryBelongsToUser implements Rule
{
    private string $table;
    public function __construct(string $table)
    {
        $this->table = $table;
    }
    public function passes($attribute, $value)
    {
        $property = $this->table;
        if (isset(auth()->user()->$property)) {
            return auth()->user()->$property->contains($value);
        } else {
            return false;
        }
    }
    public function message()
    {
        return trans('validation.entry_belongs_to_user');
    }
}
