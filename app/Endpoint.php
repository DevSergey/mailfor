<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Endpoint extends Model
{
    protected $fillable = [
        'name', 'cors_origin'
    ];
    protected $hidden = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
