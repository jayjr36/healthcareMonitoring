<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/health_parameters', [DataController::class, 'storeData']);
Route::post('/dosage', [DataController::class. 'storeDose']);
Route::get('/api/retrieve', [DataController::class, 'retrieveData']);

