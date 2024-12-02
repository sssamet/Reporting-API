<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/', function () {
    return redirect()->route('login'); 
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/api/login', [ApiController::class, 'login'])->name('api.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::post('/api/transactions/report', [ApiController::class, 'getTransactionReport']);
Route::post('/api/transaction', [ApiController::class, 'getTransaction']);
Route::post('/api/client', [ApiController::class, 'getClient']);
