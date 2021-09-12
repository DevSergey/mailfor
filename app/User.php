<?php
namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'admin', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function credentials()
    {
        return $this->hasMany(Credential::class)->latest('updated_at');
    }
    public function validations()
    {
        return $this->hasMany(Validation::class)->latest('updated_at');
    }
    public function receivers()
    {
        return $this->hasMany(Receiver::class)->latest('updated_at');
    }
    public function endpoints()
    {
        return $this->hasMany(Endpoint::class)->latest('updated_at');
    }
}
