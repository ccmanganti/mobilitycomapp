<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GlovesController;
use Illuminate\Support\Facades\Route;
use App\Models\Gloves;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect('/home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


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
