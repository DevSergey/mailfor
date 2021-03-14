<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Validation extends Model
{
    protected $fillable = [
        'name', 'validation'
    ];
    protected $hidden = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
