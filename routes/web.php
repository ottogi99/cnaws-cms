<?php

use App\Http\Livewire\Dashboard;
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
    Route::get('/cities', App\Http\Livewire\Ots\Cities::class)->name('cities');
    Route::get('/nonghyups', App\Http\Livewire\Ots\Nonghyups::class)->name('nonghyups');
    Route::get('/expenses', App\Http\Livewire\Ots\Expenses::class)->name('expenses');
    Route::get('/staff', App\Http\Livewire\Ots\Staff::class)->name('staff');
    Route::get('/accounts', App\Http\Livewire\Ots\Accounts::class)->name('account');
    Route::get('/expenditures', App\Http\Livewire\Ots\Expenditures::class)->name('expenditures');
    Route::get('/farmhouses', App\Http\Livewire\Ots\Farmhouses::class)->name('farmhouses');
    Route::get('/management', App\Http\Livewire\Ots\Management::class)->name('management');
});
