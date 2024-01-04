<?php

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('auth.login');
Route::post('/register-user', [AuthController::class, 'register'])->name('auth.register');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json([], ResponseAlias::HTTP_OK);
})->middleware(['auth:api', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return response()->json([], ResponseAlias::HTTP_OK);
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('auth.logout');

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::get('/test', function (Request $request) {
        return $request->user();
    });
});
