<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Device token registration endpoint
Route::post('/register-device', [DeviceController::class, 'registerDevice']);
Route::get('/device-tokens', [DeviceController::class, 'getDeviceTokens']);