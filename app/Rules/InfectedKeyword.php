<?php
namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
class InfectedKeyword implements Rule
{
    protected $forbiddenValidationTerms = [
        'unique', 'exists', 'password', 'active_url'
    ];
    public function __construct()
    {
    }
    public function passes($attribute, $value)
    {
        $lower = Str::lower($value);
        return Str::contains($lower, $this->forbiddenValidationTerms) === false;
    }
    public function message()
    {
        return trans('validation.infectedkeyword');
    }
}
