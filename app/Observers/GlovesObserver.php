<?php

namespace App\Observers;

use App\Models\Actions;
use App\Models\Gloves;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Actions\Action;
class GlovesObserver
{
    /**
     * Handle the Gloves "created" event.
     */
    public function created(Gloves $gloves): void
    {

        if ($gloves && $gloves->user) {
            $user = $gloves->user; // Assuming the Glove model has a 'user' relationship

            // Debug log for testing
            \Log::info('Observer triggered: Glove ID - ' . $gloves->id . ' | User ID - ' . $user->id);

            // Step 3: Send notification to the user
            Notification::make()
                ->title('New Glove Registered')
                ->icon('heroicon-m-hand-raised')
                ->iconColor('success')
                ->color('success')
                ->body('A new glove has been registered. Turn on your gloves now to start syncing.')
                ->sendToDatabase($user)
                ->send();
        } else {
            \Log::info('Observer triggered, but no glove or user found.');
        }
    }

    /**
     * Handle the Gloves "updated" event.
     */
    public function updated(Gloves $gloves): void
    {
        //
    }

    /**
     * Handle the Gloves "deleted" event.
     */
    public function deleted(Gloves $gloves): void
    {
        //
    }

    /**
     * Handle the Gloves "restored" event.
     */
    public function restored(Gloves $gloves): void
    {
        //
    }

    /**
     * Handle the Gloves "force deleted" event.
     */
    public function forceDeleted(Gloves $gloves): void
    {
        //
    }
}
