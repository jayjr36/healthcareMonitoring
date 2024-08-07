<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;


Route::post('/health_parameters', [DataController::class, 'storeData']);
Route::post('/dosage', [DataController::class, 'storeDose']);
Route::get('/retrieve', [DataController::class, 'retrieveData']);

