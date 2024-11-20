<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GlovesController;
use Illuminate\Support\Facades\Route;
use App\Models\Gloves;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request; // Ensure you import the Request class
use Illuminate\Support\Facades\Http;
use App\Models\User;

Route::get('/', function () {
    return redirect('/home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/store-subscription', function (Request $request) {
    $request->validate([
        'subscription_id' => 'required|string',
        'user_id' => 'required|integer',
    ]);

    $user = User::findOrFail($request->user_id);

    // Check if the subscription ID already exists
    $existing = $user->subscriptions()->where('subscription_id', $request->subscription_id)->first();

    if ($existing) {
        return response()->json(['message' => 'Subscription ID already exists']);
    }

    // Save the new subscription ID
    $user->subscriptions()->create([
        'subscription_id' => $request->subscription_id,
    ]);

    return response()->json(['message' => 'Subscription saved successfully']);
});



Route::get('/send-notification', function (Request $request) {
    $appId = env('ONESIGNAL_APP_ID'); // Replace with your actual OneSignal App ID
    $apiKey = env('ONESIGNAL_REST_API_KEY'); // Replace with your actual REST API key

    
    // Validate contents input
    $contentsInput = $request->input('contents', ['en' => 'Defsddaaault Message']);
    $contents = is_array($contentsInput) ? $contentsInput : ['en' => $contentsInput];

    // Prepare the payload
    $payload = [
        'app_id' => $appId,
        'target_channel' => $request->input('target_channel', 'push'),
        'headings' => $request->input('headings', ['en' => 'Default Title']),
        'contents' => $contents,
        'include_subscription_ids' => $request->input('include_subscription_ids', [
            '926987af-f0cd-433d-bc00-c242eac8caa4',
        ]),
    ];


    // Send the POST request
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . $apiKey,
        'accept' => 'application/json',
        'content-type' => 'application/json',
    ])->post('https://api.onesignal.com/notifications', $payload);

    // Return the response
    if ($response->successful()) {
        return response()->json(['message' => 'Notification sent successfully', 'data' => $response->json()]);
    }

    return response()->json(['error' => $response->json()], $response->status());
});


Route::get('/key', function(){
    Artisan::call('key:generate');
});

Route::get('/optimize', function(){
    Artisan::call('optimize:clear');
});

Route::get('/migrate', function(){
    Artisan::call('migrate:refresh');
});

Route::get('/seed', function(){
    Artisan::call('db:seed', ['--class=RolesAndPermissionSeeder']);
});

Route::get('/composer-install', function(){
    Artisan::call('composer:install');
});

Route::get('/queue', function(){
    Artisan::call('queue:work');
});
