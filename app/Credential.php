<?php
namespace App;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
class Credential extends Model
{
    use Encryptable;
    protected $fillable = [
        'name', 'host', 'port', 'from_address', 'from_name', 'encryption', 'username', 'password'
    ];
    protected $hidden = [
        'password',
    ];
    protected $encryptable = [
        'password',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
