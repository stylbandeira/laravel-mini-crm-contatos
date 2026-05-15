<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/contacts/{id}/process-score', [ContactController::class, 'processScore']);
Route::apiResource('/contacts', ContactController::class);
