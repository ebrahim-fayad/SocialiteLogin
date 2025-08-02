<?php

use App\Http\Controllers\Api\Auth\SocialAuthController;
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
Route::post('/login/social', [SocialAuthController::class, 'handleSocialLogin']);
Route::get('/first', function () {
    return response()->json([
        'status' => 200,
        'message' => 'Welcome to the API',
    ],200);
})->middleware('auth:sanctum');