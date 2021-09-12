<?php
namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
class CorsOrigin implements Rule
{
    public function __construct()
    {
    }
    public function passes($attribute, $value)
    {
        $url_passes = filter_var($value, FILTER_VALIDATE_URL) !== false;
        $wildcard_passes = $value === '*';
        return $url_passes || $wildcard_passes;
    }
    public function message()
    {
        return trans('validation.cors_origin');
    }
}
