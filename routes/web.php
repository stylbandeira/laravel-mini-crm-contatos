<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/monitor', function () {
    return view('monitor');
});

Route::get('/monitor/{contact}', function ($contact) {
    return view('monitor', [
        'contactId' => $contact,
    ]);
});
