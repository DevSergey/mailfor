<?php
namespace App\Traits;
trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable) && $value !== '') {
            if ($value) {
                $value = decrypt($value);
            }
        }
        return $value;
    }
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            if ($value) {
                $value = encrypt($value);
            }
        }
        return parent::setAttribute($key, $value);
    }
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        foreach ($this->encryptable as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = decrypt($attributes[$key]);
            }
        }
        return $attributes;
    }
}
