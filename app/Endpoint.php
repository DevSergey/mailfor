<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Endpoint extends Model
{
    protected $fillable = [
        'name', 'cors_origin', 'subject', 'monthly_limit', 'client_limit', 'time_unit', 'credential_id'
    ];
    protected $hidden = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function credential()
    {
        return $this->belongsTo(Credential::class);
    }
    public function receivers()
    {
        return $this->belongsToMany(Receiver::class)->withTimestamps();
    }
}
