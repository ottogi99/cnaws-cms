<?php

use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Ots\Management;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    // Route::get('/management', function () {
    //     return view('ots.management');
    // })->name('management');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/management', Management::class)->name('management');
});
