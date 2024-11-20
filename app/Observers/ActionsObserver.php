<?php

namespace App\Observers;

use App\Models\Actions;
use App\Models\Gloves;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request; // Ensure you import the Request class
use Illuminate\Support\Facades\Http;
class ActionsObserver
{
    /**
     * Handle the Actions "created" event.
     */
    public function created(Actions $actions): void
    {
        // Step 1: Find the glove by serial number
        $glove = Gloves::where('id', $actions->gloves_id)->first();

        if ($glove && $glove->user) {
            $user = $glove->user; // Assuming the Glove model has a 'user' relationship
            $subscriptionIds = $user->subscriptions->pluck('subscription_id')->toArray();

        if (!empty($subscriptionIds)) {
            // Call notification function
            $this->sendNotification(
                $subscriptionIds,
                "New Item Created",
                "An item '{$glove}' has been created."
            );
        }
            // Debug log for testing
            \Log::info('Observer triggered: Glove ID - ' . $glove->id . ' | User ID - ' . $user->id);

            // Step 3: Send notification to the user
            Notification::make()
                ->title('New Action Created')
                ->icon('heroicon-m-hand-raised')
                ->iconColor('warning')
                ->body('A new action has been saved for your glove. Register a description for it now.')
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(route('filament.admin.resources.actions.index'), shouldOpenInNewTab: false),
                ])
                ->sendToDatabase($user)
                ->send()
                ;
        } else {
            \Log::info('Observer triggered, but no glove or user found.');
        }
    }

    public function sendNotification(array $subscriptionIds, string $title, string $message)
    {
        $appId = env('ONESIGNAL_APP_ID');
        $apiKey = env('ONESIGNAL_REST_API_KEY');

        $payload = [
            'app_id' => $appId,
            'target_channel' => 'push',
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'include_subscription_ids' => $subscriptionIds,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $apiKey,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.onesignal.com/notifications', $payload);

        return $response->successful()
            ? ['status' => 'success', 'data' => $response->json()]
            : ['status' => 'error', 'data' => $response->json()];
    }


    /**
     * Handle the Actions "updated" event.
     */
    public function updated(Actions $actions): void
    {
        $glove = Gloves::where('id', $actions->gloves_id)->first();

        if ($glove && $glove->user) {
            $user = $glove->user; // Assuming the Glove model has a 'user' relationship

            // Debug log for testing
            \Log::info('Observer triggered: Glove ID - ' . $glove->id . ' | User ID - ' . $user->id);

            // Step 3: Send notification to the user
            Notification::make()
                ->title('Updated Flex Action')
                ->icon('heroicon-m-hand-raised')
                ->iconColor('success')
                ->body('A flex action update has been saved for your glove.')
                ->sendToDatabase($user);
        } else {
            \Log::info('Observer triggered, but no glove or user found.');
        }
    }

    /**
     * Handle the Actions "deleted" event.
     */
    public function deleted(Actions $actions): void
    {
        //
    }

    /**
     * Handle the Actions "restored" event.
     */
    public function restored(Actions $actions): void
    {
        //
    }

    /**
     * Handle the Actions "force deleted" event.
     */
    public function forceDeleted(Actions $actions): void
    {
        //
    }
}
