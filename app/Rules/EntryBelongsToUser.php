<?php
namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
class EntryBelongsToUser implements Rule
{
    private string $table;
    public function __construct(string $table)
    {
        $this->table = $table;
    }
    public function passes($attribute, $value)
    {
        Log::debug($value);
        $property = $this->table;
        if (isset(auth()->user()->$property)) {
            if (is_array($value) || $value instanceof Collection) {
                foreach ($value as $valueItem) {
                    return auth()->user()->$property->contains($valueItem);
                }
            } else {
                return auth()->user()->$property->contains($value);
            }
        } else {
            return false;
        }
    }
    public function message()
    {
        return trans('validation.entry_belongs_to_user');
    }
}
