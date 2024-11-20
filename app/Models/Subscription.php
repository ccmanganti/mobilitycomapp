<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
