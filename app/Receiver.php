<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public function endpoints()
    {
        return $this->belongsToMany(Endpoint::class)->withTimestamps();
    }
}
