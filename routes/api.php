<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', function() {
    try {
        return response()->json([
            'message' => 'Server is Running'
        ], 200, ['Content-Type' => 'application/json']);
    } catch (\Throwable $th) {
        return response()->json([
            'message' => 'Server is Down'
        ], 502, ['Content-Type' => 'application/json']);
    }
});


Route::middleware(['guest'])->group(function () {
    Route::post('/users/authenticate', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/users/authenticate/logout', [AuthController::class, 'logout']);
});