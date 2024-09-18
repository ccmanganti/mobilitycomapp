<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;
use App\Models\Actions;
use App\Models\Readings;
use App\Observers\GlovesObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([GlovesObserver::class])]
class Gloves extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'default',
        'serial_number',
    ];

    //////////////////////
    // CUSTOM FUNCTIONS //
    //////////////////////

    // Defining the Relationship of Gloves to User.
    // A glove can be belong to one user only.
    public function user(): BelongsTo {

        return $this->belongsTo(User::class);

    }

    // Defining the Relationship of Glove to Actions.
    // A glove can have multiple actions. One to Many.
    public function actions(): HasMany {
    
        return $this->hasMany(Actions::class);
    
    }

    // Defining the Relationship of Glove to Readings
    // A glove can have multiple readings. One to Many.
    public function readings(): HasMany {

        return $this->hasMany(Readings::class);

    }
}
