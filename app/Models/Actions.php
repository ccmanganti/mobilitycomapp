<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Gloves;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Observers\ActionsObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ActionsObserver::class])]
class Actions extends Model
{
    use HasFactory;

    protected $fillable = [
        'gloves_id',
        'finger_1',
        'finger_2',
        'finger_3',
        'finger_4',
        'finger_5',
        'patient_need',
    ];
 
    //////////////////////
    // CUSTOM FUNCTIONS //
    //////////////////////

    // Defining the Relationship of Actions to Gloves.
    // An action should only be belong to one glove.
    public function gloves(): BelongsTo {

        return $this->belongsTo(Gloves::class);

    }

    public function user(): HasOneThrough {
        return $this->hasOneThrough(User::class, Gloves::class);
    }
}
