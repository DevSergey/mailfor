<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Endpoint extends Model
{
    protected $fillable = [
        'name', 'cors_origin', 'subject', 'monthly_limit', 'client_limit', 'time_unit'
    ];
    protected $hidden = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
