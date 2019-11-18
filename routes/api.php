<?php

use App\Queue;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/emails', function () {
    return Queue::all();
})->name('emails.list');

Route::post('/emails', 'EmailController@store')->name('emails.store');

