<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Observers\ReadingsObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Models\User;
use App\Models\Gloves;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

#[ObservedBy([ReadingsObserver::class])]
class Readings extends Model
{
    use HasFactory;

    protected $fillable = [
        'gloves_id',
        'finger_1',
        'finger_2',
        'finger_3',
        'finger_4',
        'finger_5',
    ];

    //////////////////////
    // CUSTOM FUNCTIONS //
    //////////////////////

    // Defining the Relationship of Readings to Glove.
    // A reading can be belong to one glove only.
    public function gloves(): BelongsTo {

        return $this->belongsTo(Gloves::class);

    }

    public function user(): HasOneThrough {
        return $this->hasOneThrough(User::class, Gloves::class);
    }
}
