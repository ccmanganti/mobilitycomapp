<?php

namespace App\Observers;

use App\Models\Readings;
use App\Models\Gloves;
use App\Models\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ReadingsObserver
{
    /**
     * Handle the Readings "created" event.
     */
    public function created(Readings $readings): void
{
    // Define tolerance percentage
    $tolerance = 0.10; // 5% tolerance
    
    // Step 1: Find the glove by serial number
    $glove = Gloves::where('id', $readings->gloves_id)->first();
    $actions = Actions::where('gloves_id', $glove->id)->get();

    if ($glove && $glove->user) {
        $user = $glove->user; // Assuming the Glove model has a 'user' relationship

        // Debug log for testing
        \Log::info('Observer triggered: Glove ID - ' . $glove->id . ' | User ID - ' . $actions);
        // Step 2: Check if reading is close to any actions
        foreach ($actions as $action) {
            $isClose = true;
            foreach (['finger_1', 'finger_2', 'finger_3', 'finger_4', 'finger_5'] as $finger) {
                $actionValue = $action->$finger;
                $readingValue = $readings->$finger;

                // Calculate percentage difference
                $difference = abs($actionValue - $readingValue);
                $percentageDifference = $difference / $actionValue;
                \Log::info($percentageDifference);
                if ($percentageDifference > $tolerance) {
                    $isClose = false;
                    break;
                }
            }
            
            if ($isClose) {
                // Step 3: Send notification to the user
                Notification::make()
                    ->title($glove->name.': ' . $action->patient_need)
                    ->body('The patient needs something.')
                    ->icon('heroicon-c-user')
                    ->iconColor('danger')
                    ->color('primary')
                    ->persistent()
                    ->sendToDatabase($user)
                    ->send();
                // Exit loop once notification is sent
                break;
            }
        }
    } else {
        \Log::info('Observer triggered, but no glove or user found.');
    }
}


    /**
     * Handle the Readings "updated" event.
     */
    public function updated(Readings $readings): void
    {
        //
    }

    /**
     * Handle the Readings "deleted" event.
     */
    public function deleted(Readings $readings): void
    {
        //
    }

    /**
     * Handle the Readings "restored" event.
     */
    public function restored(Readings $readings): void
    {
        //
    }

    /**
     * Handle the Readings "force deleted" event.
     */
    public function forceDeleted(Readings $readings): void
    {
        //
    }
}
