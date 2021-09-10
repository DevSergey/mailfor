<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Receiver extends Model
{
    protected $fillable = [
        'name', 'email'
    ];
    protected $hidden = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
